<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imprest extends Model
{
    use HasFactory;

    public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function getUser(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
