<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailCampaign;
use App\Models\Tenant;
use Auth;

class EmailMarketing extends Mailable
{
    use Queueable, SerializesModels;
    public $email;
    public $tenant;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmailCampaign $email, Tenant $tenant)
    {
        $this->email = $email;
        $this->tenant = $tenant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( $this->tenant->email ?? 'no-reply@cnxretail.com', $this->tenant->company_name ?? 'CNX Retail')
        ->subject($this->email->subject ?? 'No subject')
        ->markdown('mails.email-marketing.mail1');
    }
}
