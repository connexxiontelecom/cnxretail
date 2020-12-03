<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkSms extends Model
{
    use HasFactory;

    public function getSmsRecipients(){
        return $this->hasMany(BulkSmsRecipient::class, 'sms_id');
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sent_by');
    }
}
