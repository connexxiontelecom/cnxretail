<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\InvoiceMaster;
use App\Models\InvoiceDetail;
use App\Models\User;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $invoice, $items;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(InvoiceMaster $invoice, InvoiceDetail $items, User $user)
    {
        $this->user = $user;
        $this->items = $items;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.customer.invoice');
    }
}
