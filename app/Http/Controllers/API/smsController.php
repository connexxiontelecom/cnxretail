<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhoneGroup;
use App\Models\BulkSms;
use App\Models\BulkSmsRecipient;
use APP\Models\User;
use App\Models\EmailCampaign;
use App\Models\EmailRecipient;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Mail\EmailMarketing;

class smsController extends Controller
{

    public function fetchPhoneGroups(Request $request)
    {
        $phonegroups = PhoneGroup::where('tenant_id', $request->tenant_id)->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $phonegroups], 200);
    }

    public function createPhoneGroup (Request $request){

        $this->validate($request,[
            'group_name'=>'required',
            'selectedContacts'=>'required'
        ]);

        $numbers = rtrim(trim($request->selectedContacts), ',');
        $numbers = explode(",",$numbers);
        $unique = array_unique($numbers);
        $phone_numbers = implode(",",$unique);
        $group = new PhoneGroup;
        $group->group_name = $request->group_name;
        $group->phone_numbers = $phone_numbers;
        $group->tenant_id = $request->tenant;
        $group->added_by = $request->id;
        $group->slug = substr(sha1(time()), 12,40);
        $group->save();
        return response()->json(['response'=>'success'], 201);
    }



    public function fetchSms(Request $request)
    {
        $sms = BulkSms::where('tenant_id', $request->tenant_id) ->orderBy('id', 'DESC')->get();
        foreach($sms as $msg){
            $recipients =  BulkSmsRecipient::where('sms_id', $msg['id'])->get();
            $msg['sender'] = User::find($msg["sent_by"])["full_name"];
            $msg['recipients'] =  $recipients ;
        }

        return response()->json(['data'=>$sms], 201);

    }



    public function editPhoneGroup(Request $request){
        $this->validate($request,[
            'groupid'=>'required',
            'phonenumbers'=>'required'
        ]);
        $group =  PhoneGroup::where('id', $request->groupid)->where('tenant_id', $request->tenant)->first();
        //$group->group_name = $request->group_name;
        $group->phone_numbers = $group->phone_numbers .",".$request->phonenumbers;
        $group->tenant_id = $request->tenant;
        $group->added_by = $request->user;
        $group->save();
        return response()->json(['response'=>'success'], 201);
    }



    public function removePhoneContact(Request $request){
        $this->validate($request,[
            'groupid'=>'required',
            'phonenumbers'=>'required'
        ]);
        $group =  PhoneGroup::where('id', $request->groupid)->where('tenant_id', $request->tenant)->first();
        //$group->group_name = $request->group_name;
        $group->phone_numbers = $request->phonenumbers;
        $group->tenant_id = $request->tenant;
        $group->added_by = $request->user;
        $group->save();
        return response()->json(['response'=>'success'], 201);
    }


    public function sendSMS(Request $request){

        $sms = new BulkSms;
        $sms->message = $request->textMessage;
        $sms->tenant_id = $request->tenant_id;
        $sms->sent_by = $request->id;
        $sms->sender_id = $request->senderId ?? 'CNX Retail';
        $sms->slug = substr(sha1(time()),23,40);
        $sms->save();
        $smsId = $sms->id;

        #recipient
        $unique = array_unique(explode(',',$request->phoneNumbers));
        $phone_numbers = implode(",",$unique);
        $recipients = explode(',',$phone_numbers);
        //$recipients = explode(',', $contacts.''.$phone_numbers);
        for($i = 0; $i<count($recipients); $i++){
            $recipient = new BulkSmsRecipient;
            $recipient->sms_id = $smsId;
            $recipient->contact_id = $recipients[$i];
            $recipient->save();
        }



        #bulk SMS
        $mobile = implode(",", $recipients);
        $name = "Joseph";
        $message = $request->textMessage;
        $createdURL = "";
        $ozSURL = "http://www.bbnsms.com/bulksms/bulksms.php";
        $ozUser = "talktojoegee@gmail.com";
        $ozPassw = "Lordofmylife123";
        $ozMessageType = $request->senderId ?? 'CNX Retail';
        $ozRecipient = $mobile;
        $ozMessageData = $message;
        $createdURL = $ozSURL."?username=".trim($ozUser)."&password=".trim($ozPassw)."&sender=".trim($ozMessageType)."&mobile=".trim($ozRecipient)."&message=".$ozMessageData;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $createdURL);
        return response()->json(['response'=>'success'], 201);


    }


    public function getmails(Request $request){

        $emails = EmailCampaign::where('tenant_id', $request->tenant_id)->get();//orderBy('id', 'DESC')->get();

        foreach($emails as $mail){

            $recipients =  EmailRecipient::leftJoin('contacts', function ($join) {
            $join->on('email_recipients.contact_id', '=', 'contacts.id');
            })->where('email_recipients.email_id', $mail['id'])->get();
            $mail['sender'] = User::find($mail["sent_by"])["full_name"];
            $mail['recipients'] =  $recipients ;
        }

        return response()->json(['data'=>$emails], 201);
    }




    public function storeEmail(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'selectedContacts'=>'required',
            'compose_email'=>'required',
            'template'=>'required'
        ]);
        $template = EmailTemplate::where('directory', $request->template)->first();
        $email = new EmailCampaign;
        $email->subject = $request->subject;
        $email->content = $request->compose_email;
        $email->tenant_id = $request->tenant_id;
        $email->sent_by = $request->id;
        $email->slug = substr(sha1(time()),23,40);
        $email->template_id = $template->id ?? 1;
        $email->save();
        $emailId = $email->id;
        #recipient
        for($i = 0; $i<count($request->selectedContacts); $i++){
            $recipient = new EmailRecipient;
            $recipient->email_id = $emailId;
            $recipient->contact_id = $request->selectedContacts[$i];
            $recipient->save();
            #contact
            $contact = Contact::where('tenant_id', $request->tenant)->where('id', $request->selectedContacts[$i])->first();
            \Mail::to($contact)->send(new EmailMarketing($contact, $email));
        }

        return response()->json(['route'=>'mailbox'],201);

    }









}
