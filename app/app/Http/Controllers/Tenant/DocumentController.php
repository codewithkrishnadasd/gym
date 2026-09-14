<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Enums\ClubAssignmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Document;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use App\Support\Storage\BucketDisk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Printable documents: payment receipts, report summaries, and stored expense
 * receipts (MEP.md 6.10, 6.11).
 *
 * Uploaded receipts are streamed through the application rather than exposed
 * by public URL, so object-storage objects stay private and every read passes
 * the tenant and policy checks.
 */
class DocumentController extends Controller
{
    public function paymentReceipt(FeePayment $payment): Response
    {
        $this->authorize('view', $payment);

        $organisation = $this->tenant();

        $payment->loadMissing([
            'member:id,name,phone',
            'club:id,name,phone,email,address',
            'subscription.plan:id,name',
            'financialAccount:id,name,account_type',
            'collectedBy.user:id,name',
            'confirmedBy.user:id,name',
        ]);

        $pdf = Pdf::loadView('pdf.receipt', [
            'organisation' => $organisation,
            'payment' => $payment,
        ])->setPaper('a4');

        return $pdf->download('receipt-PMT-'.$payment->id.'.pdf');
    }

    public function reportSummary(Request $request): Response
    {
        $organisation = $this->tenant();

        $this->authorize('viewReports', $organisation);

        [$period, $clubIds] = $this->scope($request, $organisation);

        $metrics = new OrganisationMetrics($organisation, $clubIds, $period);
        $report = $request->string('report')->toString() ?: 'finance';

        $pdf = Pdf::loadView('pdf.report', [
            'organisation' => $organisation,
            'period' => $period,
            'report' => $report,
            'clubNames' => Club::query()->whereIn('id', $clubIds)->orderBy('name')->pluck('name')->implode(', '),
            'metrics' => $metrics,
            'statusTotals' => $metrics->paymentStatusTotals(),
            'clubComparison' => $metrics->clubComparison(),
            'methodSplit' => $metrics->paymentMethodSplit(),
            'categorySplit' => $metrics->expenseCategorySplit(),
            'generatedAt' => now($organisation->timezone),
        ])->setPaper('a4');

        return $pdf->stream($organisation->slug.'-'.$report.'-report.pdf');
    }

    /**
     * Streams an uploaded receipt straight from the configured disk.
     */
    public function expenseReceipt(Expense $expense): StreamedResponse
    {
        $this->authorize('viewAny', Expense::class);

        abort_if($expense->receipt_path === null, 404);

        $disk = Storage::disk(config('filesystems.default'));

        abort_unless($disk->exists($expense->receipt_path), 404);

        return $disk->response(
            $expense->receipt_path,
            'receipt-EXP-'.$expense->id.'.'.pathinfo($expense->receipt_path, PATHINFO_EXTENSION),
            ['Cache-Control' => 'private, max-age=300'],
        );
    }

    /**
     * Streams a member or staff document out of the organisation's own bucket.
     *
     * Proxied rather than redirected to a signed object URL: the policy check
     * has to happen on every fetch, and a signed URL, once issued, is a
     * bearer token for an identity document that outlives the session it was
     * created in.
     */
    public function memberDocument(Document $document): StreamedResponse
    {
        $this->authorize('view', $document);

        $bucket = $document->bucket;

        abort_if($bucket === null, 404);

        $disk = BucketDisk::for($bucket);

        abort_unless($disk->exists($document->path), 404);

        return $disk->response(
            $document->path,
            $document->original_filename,
            [
                'Cache-Control' => 'private, no-store',
                // Never rendered inline from our own origin: an uploaded SVG
                // or HTML file would otherwise run as a same-origin script.
                'Content-Disposition' => 'attachment; filename="'.addslashes($document->original_filename).'"',
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }

    /**
     * @return array{0: ReportPeriod, 1: array<int, int>}
     */
    private function scope(Request $request, Organisation $organisation): array
    {
        /** @var User $user */
        $user = $request->user();

        /** @var OrganisationUser $membership */
        $membership = app()->bound('membership')
            ? app('membership')
            : $user->membershipFor($organisation);

        $period = ReportPeriod::fromStrings(
            $request->string('from')->toString() ?: null,
            $request->string('to')->toString() ?: null,
            $organisation->timezone,
        );

        $available = $membership->isAdmin()
            ? Club::query()->pluck('id')->all()
            : $membership->clubAssignments()->where('status', ClubAssignmentStatus::Active)->pluck('club_id')->all();

        $requested = $request->integer('club');

        return [$period, $requested > 0 && in_array($requested, $available, true) ? [$requested] : $available];
    }

    private function tenant(): Organisation
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return $organisation;
    }
}
