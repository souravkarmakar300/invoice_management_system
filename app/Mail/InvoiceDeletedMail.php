<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceNo;
    public $customerName;
    public $customerEmail;
    public $companyName;

    public function __construct(
        $invoiceNo,
        $customerName,
        $customerEmail,
        $companyName
    ) {
        $this->invoiceNo = $invoiceNo;
        $this->customerName = $customerName;
        $this->customerEmail = $customerEmail;
        $this->companyName = $companyName;
    }

    public function build()
    {
        return $this->subject(
            'Invoice Deleted - ' . $this->invoiceNo
        )->view('emails.invoice-deleted');
    }
}