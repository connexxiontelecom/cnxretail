<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailCampaign;
use App\Models\EmailRecipient;
use App\Models\Tenant;
use App\Models\Contact;
use Auth;

class EmailMarketing extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $tenant;
    public $contact;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contact $contact, EmailCampaign $email)
    {
        $this->email = $email;
        $this->contact = $contact;

//$this->tenant = $tenant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( Auth::user()->tenant->email ?? 'no-reply@cnxretail.com', Auth::user()->tenant->company_name ?? 'CNX Retail')
        ->subject($this->email->subject ?? 'No subject')
        ->markdown('mails.email-marketing.mail1');
    }
}
