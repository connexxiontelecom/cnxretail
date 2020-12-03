<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    public function getEmailRecipients(){
        return $this->hasMany(EmailRecipient::class, 'email_id');
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sent_by');
    }
}
