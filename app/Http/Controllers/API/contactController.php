<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Prospecting;

class contactController extends Controller
{
    //
    public function addContact(Request $request){
        $this->validate($request,[
            'user_id'=>'required',
            'tenant_id'=>'required',
            'company_name'=>'required',
            'company_address'=>'required',
            'company_email'=>'required|email',
            'company_phone_no'=>'required',
            'full_name'=>'required',
            'email_address'=>'required',
            'mobile_no'=>'required',
            'position'=>'required',
            'communication_channel'=>'required',
            'website'=>'required',
            'preferred_time'=>'required'
        ]);
        $contact = new Contact;
        $contact->added_by = $request->user_id;
        $contact->company_name = $request->company_name;
        $contact->address = $request->company_address;
        $contact->email = $request->company_email;
        $contact->company_phone = $request->company_phone_no;
        $contact->website = $request->website;
        $contact->contact_full_name = $request->full_name;
        $contact->contact_email = $request->email_address;
        $contact->contact_mobile = $request->mobile_no;
        $contact->contact_position = $request->position;
        $contact->communication_channel = $request->communication_channel;
        $contact->preferred_time =  date('H:i:s', strtotime($request->preferred_time));
        $contact->slug = substr(sha1(time()),30,40);
        $contact->tenant_id = $request->tenant_id;
        $contact->save();
        return response()->json(['response'=>'success'],201);
    }


    public function prospecting(Request $request){
        $this->validate($request,[
            'date_time'=>'required',
            'remarks'=>'required'
        ]);

        //check is exist on prospecting table

        $isProspecting  =  Prospecting::where("contact_id", $request->contact)->first();

        if (empty($isProspecting) || $isProspecting){
            $prospect = new Prospecting;
            $prospect->contact_id = $request->contact;
            $prospect->tenant_id = Auth::user()->tenant_id;
            $prospect->remarks = $request->remarks;
            $prospect->date_time = $request->date_time;
            $prospect->save();
            #set lead
            $clientExist = Lead::where('contact_id', $request->prospectingContact)
                ->where('tenant_id', Auth::user()->tenant_id)->first();
            if(empty($clientExist)){
                $lead = new Lead;
                $lead->contact_id = $request->contact;
                $lead->tenant_id = Auth::user()->tenant_id;
                $lead->converted_by = Auth::user()->id;
                $lead->save();
            }
            #set reminder
            if($request->reminder){
                $reminder = new Reminder;
                $reminder->reminder_name = $request->remarks;
                $reminder->remind_at = $request->date_time;
                $reminder->note = 'Prospecting';
                $reminder->set_by = Auth::user()->id;
                $reminder->tenant_id = Auth::user()->tenant_id;
                $reminder->save();
            }
            
            return response()->json(['message'=>'Success! Prospect saved.']);
        }
    }



}
