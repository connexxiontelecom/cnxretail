<?php

namespace App\Imports;

use App\Contact;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Contact as ContactModel;

class ContactImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ContactModel([
            'tenant_id'=>Auth::user()->tenant_id,
            'added_by'=>Auth::user()->id,
            'company_name'=>$row[0],
            'email'=>$row[1],
            'company_phone'=>$row[2],
            'website'=>$row[3],
            'address'=>$row[4],
            'contact_full_name'=>$row[5],
            'contact_email'=>$row[6],
            'contact_mobile'=>$row[7],
            'whatsapp_contact'=>$row[8],
            'slug'=>substr(sha1(time()),32,40).'-'.rand(1,100),
            'contact_position'=>'N/A',
            'communication_channel'=>'N/A'
        ]);
    }
}
