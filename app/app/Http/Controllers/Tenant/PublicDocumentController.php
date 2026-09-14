<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\FeePayment;
use App\Models\Invoice;
use App\Models\Organisation;
use App\Support\Documents\PdfDocuments;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Invoices and receipts opened from a shared link — by the member, on their
 * phone, with no account. The token in the URL is the only credential, so
 * every lookup is scoped to the organisation the hostname resolved to and
 * nothing here lists or counts anything.
 */
class PublicDocumentController extends Controller
{
    public function invoice(string $token): View
    {
        $invoice = $this->findInvoice($token);

        return view('public.document', [
            'organisation' => $this->tenant(),
            'kind' => 'invoice',
            'title' => 'Invoice '.$invoice->number,
            'invoice' => $invoice->load(['member:id,name', 'lines']),
            'payment' => null,
            'previewUrl' => route('tenant.public.invoice.pdf', ['token' => $token, 'inline' => 1]),
            'downloadUrl' => route('tenant.public.invoice.pdf', ['token' => $token]),
        ]);
    }

    public function invoicePdf(string $token): Response
    {
        $invoice = $this->findInvoice($token);
        $pdf = PdfDocuments::invoice($invoice, $this->tenant());
        $filename = PdfDocuments::invoiceFilename($invoice);

        return request()->boolean('inline') ? $pdf->stream($filename) : $pdf->download($filename);
    }

    public function receipt(string $token): View
    {
        $payment = $this->findPayment($token);

        return view('public.document', [
            'organisation' => $this->tenant(),
            'kind' => 'receipt',
            'title' => 'Receipt '.$this->tenant()->reference('payment', $payment->id),
            'invoice' => null,
            'payment' => $payment->load(['member:id,name', 'invoice:id,number']),
            'previewUrl' => route('tenant.public.receipt.pdf', ['token' => $token, 'inline' => 1]),
            'downloadUrl' => route('tenant.public.receipt.pdf', ['token' => $token]),
        ]);
    }

    public function receiptPdf(string $token): Response
    {
        $organisation = $this->tenant();
        $payment = $this->findPayment($token);
        $pdf = PdfDocuments::receipt($payment, $organisation);
        $filename = PdfDocuments::receiptFilename($payment, $organisation);

        return request()->boolean('inline') ? $pdf->stream($filename) : $pdf->download($filename);
    }

    private function findInvoice(string $token): Invoice
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->where('organisation_id', $this->tenant()->id)
            ->where('public_token', $token)
            ->firstOrFail();

        return $invoice;
    }

    private function findPayment(string $token): FeePayment
    {
        /** @var FeePayment $payment */
        $payment = FeePayment::query()
            ->where('organisation_id', $this->tenant()->id)
            ->where('public_token', $token)
            ->firstOrFail();

        return $payment;
    }

    private function tenant(): Organisation
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return $organisation;
    }
}
