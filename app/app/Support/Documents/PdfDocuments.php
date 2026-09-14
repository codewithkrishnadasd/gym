<?php

declare(strict_types=1);

namespace App\Support\Documents;

use App\Models\FeePayment;
use App\Models\Invoice;
use App\Models\Organisation;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfBuilder;

/**
 * Builds the invoice and receipt PDFs. One place, so the copy a member opens
 * from a shared link is byte-for-byte the copy staff download inside the app.
 */
final class PdfDocuments
{
    public static function invoice(Invoice $invoice, Organisation $organisation): PdfBuilder
    {
        $invoice->loadMissing(['lines', 'member:id,name,phone', 'club:id,name', 'createdBy.user:id,name', 'payments']);

        return Pdf::loadView('pdf.invoice', [
            'organisation' => $organisation,
            'invoice' => $invoice,
        ])->setPaper('a4');
    }

    public static function invoiceFilename(Invoice $invoice): string
    {
        return 'invoice-'.$invoice->number.'.pdf';
    }

    public static function receipt(FeePayment $payment, Organisation $organisation): PdfBuilder
    {
        $payment->loadMissing([
            'member:id,name,phone,admission_fee_minor,admission_discount_minor,admission_paid_minor',
            'club:id,name,phone,email,address',
            'subscription.plan:id,name',
            'invoice.lines',
            'financialAccount:id,name,account_type',
            'collectedBy.user:id,name',
            'confirmedBy.user:id,name',
        ]);

        return Pdf::loadView('pdf.receipt', [
            'organisation' => $organisation,
            'payment' => $payment,
        ])->setPaper('a4');
    }

    public static function receiptFilename(FeePayment $payment, Organisation $organisation): string
    {
        return 'receipt-'.$organisation->reference('payment', $payment->id).'.pdf';
    }
}
