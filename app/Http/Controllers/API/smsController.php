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
use App\Models\BulkSmsAccount;
use Illuminate\Support\Facades\Auth;

class smsController extends Controller
{

    private $API_KEY = "TLpMHRqPFlfmSJPxYONy6qCSs94qkaP3oocjtREGoUq7bAneOm6UEo01mzNdJm";
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
        $sms->tenant_id = Auth::user()->tenant_id;//$request->tenant_id;
        $sms->sent_by = Auth::user()->id;    //$request->id;
        $sms->sender_id =  Auth::user()->tenant->sender_id;  //$request->senderId;
        $sms->slug = substr(sha1(time()),23,40);
        $sms->save();
        $smsId = $sms->id;

        #recipient
        $unique = array_unique(explode(',',$request->phoneNumbers));
        $phone_numbers = implode(",",$unique);
        $recipients = explode(',',$phone_numbers);
        $this->sendMsg($recipients, $request->textMessage, $smsId);
        return response()->json(['response'=>'success'], 201);



       /* //$recipients = explode(',', $contacts.''.$phone_numbers);
        for($i = 0; $i<count($recipients); $i++){
            $recipient = new BulkSmsRecipient;
            $recipient->sms_id = $smsId;
            $recipient->contact_id = $recipients[$i];
            $recipient->save();
        }*/



       /* #bulk SMS
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
        $response = $client->request('GET', $createdURL);*/
    }


    function strip($phonenumbers){
        $new_phonenumbers = array();
        $i = 0;
        foreach($phonenumbers as $phonenumber):
            $split = str_split($phonenumber);
            if(($split[0] == "0") || ($split[0] == "+") ):
                if($split[0] == "+"):
                    unset($split[0]);
                    $split =  array_values($split);
                    $new_p = (implode($split));
                    $new_phonenumbers[$i] = $new_p;
                endif;

                if($split[0] == "0"):
                    unset($split[0]);
                    $split = array_values($split);
                    while(count($split) > 10):
                        unset($split[0]);
                        $split = array_values($split);
                    endwhile;
                    $new_p = (implode($split));
                    $new_phonenumbers[$i] = '234'.$new_p;
                endif;

            else:
                $new_p = (implode($split));
                $new_phonenumbers[$i] = $new_p;
            endif;
            $i++;
        endforeach;
        return $new_phonenumbers;
    }

    public function sendMsg($numbers, $message, $smsId)
    {
        /*for($i = 0; $i<count($numbers); $i++){
            $phone =  $numbers[$i];
            $phone = substr($phone, 1); //strip the first zero
            $phone = '234'. $phone;
            $numbers[$i] = $phone;
        }*/

        $numbers = $this->strip($numbers);

        for($i = 0; $i<count($numbers); $i++){
            $recipient = new BulkSmsRecipient;
            $recipient->sms_id = $smsId;
            $recipient->contact_id = $numbers[$i];
            $recipient->save();
        }

        if(Auth::user()->tenant->sender_id !=null && Auth::user()->tenant->sender_id_verified == 1)
        {
            $curl = curl_init();
            $channel = 'generic';
            $sender = Auth::user()->tenant->sender_id;
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://termii.com/api/sms/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>' {
                  "to": "'.$numbers.'",
                   "from": "'.$sender.'",
                   "sms":  "'.$message.'",
                   "type": "plain",
                   "channel": "'.$channel.'",
                   "api_key": "'.$this->API_KEY.'"
               }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
           //$response = curl_exec($curl);
            curl_close($curl);
            //return response()->json(compact("response"));
            //return response()->json(compact('otp', "response"));

            //debit the account holder
            $balance = new BulkSmsAccount();
            $balance->tenant_id = Auth::user()->tenant_id;
            $balance->debit = $this->smsBiller($message, $numbers);
            $balance->narration = "Billed for sending SMS.";
            $balance->amount =  $this->smsBiller($message, $numbers);
            $balance->ref_no = substr(sha1(time()),32,40);
            $balance->save();
        }
        else{
            return;
        }
    }


    public function smsBiller($message, $contact){
        if(strlen($message) <= 160 ){
            $cost = 3 * count(explode(",", $contact)) * 1;
        }else if(strlen($message) <= 313){
            $cost = 3 * count(explode(",", $contact)) * 2;
        }else if(strlen($message) <= 466){
            $cost = 3 * count(explode(",", $contact)) * 3;
        }
        return $cost;
    }



    public function getmails(Request $request){

        $emails = EmailCampaign::where('tenant_id', $request->tenant_id)->orderBy('id', 'DESC')->get();

        foreach($emails as $mail){
            $recipients =  EmailRecipient::leftJoin('contacts', function ($join) {
            $join->on('email_recipients.contact_id', '=', 'contacts.id');
            })->where('email_recipients.email_id', $mail['id'])->get();
            $mail['sender'] = User::find($mail["sent_by"])["full_name"];
            $mail['recipients'] =  $recipients ;
        }
        return response()->json(['data'=>$emails], 201);
    }




    public function sendEmail(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'selectedContacts'=>'required',
            'email'=>'required',
            //'template'=>'required'
        ]);
        //$template = EmailTemplate::where('directory', $request->template)->first();
        $template = EmailTemplate::where('id', 1)->first();
        $email = new EmailCampaign;
        $email->subject = $request->subject;
        $email->content = $request->email;
        $email->tenant_id = $request->tenant;
        $email->sent_by = $request->id;
        $email->slug = substr(sha1(time()),23,40);
        $email->template_id = $template->id ?? 1;
        $email->save();
        $emailId = $email->id;
        #recipient
        for($i = 0; $i<count($request->selectedContacts); $i++){
            $recipient = new EmailRecipient;
            $recipient->email_id = $emailId;
            $recipient->contact_id = $request->selectedContacts[$i]['id'];
            $recipient->save();
            #contact
            $contact = Contact::where('tenant_id', $request->tenant)->where('id', $request->selectedContacts[$i]['id'])->first();
            \Mail::to($contact)->send(new EmailMarketing($contact, $email));
        }
        return response()->json(['response'=>'success'], 201);

    }



    public function bulksmsBalance(Request $request){
        $account = BulkSmsAccount::where('tenant_id', $request->tenant_id)->orderBy('id', 'DESC')->get();
        return response()->json(['data'=>$account], 201);
    }


    public function saveTransaction(Request $request){
        $this->validate($request,[
            'sms_quantity'=>'required',
            'totalAmount'=>'required',
            'transaction'=>'required'
        ]);

        $unit = new BulkSmsAccount;
        $unit->ref_no = $request->transaction;
        $unit->credit = $request->sms_quantity;
        $unit->narration = "Account credited with ".$request->sms_quantity." units.";
        $unit->tenant_id = $request->tenant_id;
        $unit->amount = $request->totalAmount;
        $unit->save();
        return response()->json(['response'=>'success'], 201);
    }






    public function verifyTransactionReference(Request $request){

        $reference =  $request->reference;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/transaction/verify/:"."$reference",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer sk_test_cf6ad1fbbf398673fcb9b35b8cdbc0f91cbbb995",
            "Cache-Control: no-cache",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
        return response()->json(['error'=>$err], 200);
         // echo "cURL Error #:" . $err;
        } else {
            response()->json(['data'=>$response], 200);
         // echo $response;
        }
    }





}
