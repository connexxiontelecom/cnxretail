<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkSmsPricing extends Model
{
    use HasFactory;


    /*
     * Use-case methods
     */

    public function getBulkSmsPricing(){
        return BulkSmsPricing::orderBy('min_quantity', 'DESC')->get();
    }
}
