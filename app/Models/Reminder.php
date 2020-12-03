<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;


    public function getSetBy(){
        return $this->belongsTo(User::class, 'set_by');
    }
}
