<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\InvoiceMaster;
use App\Models\Contact;

class SendInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $invoice, $items;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(InvoiceMaster $invoice)
    {
        //$this->user = $user;
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@me.com')
       // return $this->from(Auth::user()->tenant->email, Auth::user()->tenant->company_name)
        ->subject('Invoice')
        ->markdown('mails.customer.invoice');
    }
}
