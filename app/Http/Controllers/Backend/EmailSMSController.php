<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BulkSmsPricing;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\EmailCampaign;
use App\Models\EmailRecipient;
use App\Models\EmailTemplate;
use App\Models\BulkSms;
use App\Models\BulkSmsRecipient;
use App\Models\PhoneGroup;
use App\Models\BulkSmsAccount;
use App\Mail\EmailMarketing;
use Auth;
class EmailSMSController extends Controller
{
    private $API_KEY = "TLpMHRqPFlfmSJPxYONy6qCSs94qkaP3oocjtREGoUq7bAneOm6UEo01mzNdJm";
        //"TLfrtWYbF5uWb0GLWjwDigrMb722yJgAp2B3jDoYYRzYOSjIU3PHwRIpGSZlga";
                        //TLpMHRqPFlfmSJPxYONy6qCSs94qkaP3oocjtREGoUq7bAneOm6UEo01mzNdJm

    public function __construct(){
        $this->middleware('auth');
        $this->bulksmspricing = new BulkSmsPricing();
        $this->activitylog = new ActivityLog();
    }

    public function mailbox(){
        $emails = EmailCampaign::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        $this->activitylog->setNewActivityLog('Access Mail box', 'Viewed mailbox');
        return view('email-sms.mailbox', ['mails'=>$emails]);
    }

    public function composeEmail(){
        $templates = EmailTemplate::orderBy('template_name', 'ASC')->get();
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $this->activitylog->setNewActivityLog('Compose Email', 'Opened compose email');
        return view('email-sms.compose-email', ['contacts'=>$contacts, 'templates'=>$templates]);
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
        $email->tenant_id = Auth::user()->tenant_id;
        $email->sent_by = Auth::user()->id;
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
            $contact = Contact::where('tenant_id', Auth::user()->tenant_id)->where('id', $request->selectedContacts[$i])->first();
            \Mail::to($contact)->send(new EmailMarketing($contact, $email));
        }
        $this->activitylog->setNewActivityLog('Email', 'Sent an email.');
        return response()->json(['route'=>'mailbox'],201);

    }

    public function readMail($slug){
        $mail = EmailCampaign::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        if(!empty($mail)){
            $this->activitylog->setNewActivityLog('Read mail', 'Opened a mail with the subject '.$mail->subject ?? '');
            return view('email-sms.read-mail',['mail'=>$mail]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function bulksms(){
        $bulksms = BulkSms::where('tenant_id', Auth::user()->tenant_id)
                            ->orderBy('id', 'DESC')->get();
        $this->activitylog->setNewActivityLog('Bulk SMS', 'Opened bulk SMS');
        return view('email-sms.bulksms', ['bulksms'=>$bulksms]);
    }


    public function composeSms(){
        $account = BulkSmsAccount::all();
        $balance = $account->sum('credit') - $account->sum('debit');
        if(!empty(Auth::user()->tenant->sender_id) && $balance >= 3){ //1 unit/page
            $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
            $phonegroups = PhoneGroup::where('tenant_id', Auth::user()->tenant_id)->get();
            $this->activitylog->setNewActivityLog('Bulk SMS', 'Opened compose SMS ');
            return view('email-sms.compose-sms', ['contacts'=>$contacts, 'phonegroups'=>$phonegroups]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong>  Insufficient balance or missing Sender ID. Check settings for Sender ID.");
            return back();
        }
    }


    public function storeSMS(Request $request){

        $this->validate($request,[
            'sender_id'=>'required',
            'compose_sms'=>'required'
        ]);

        if(empty($request->selectedContacts) && empty($request->phone_groups)){
            session()->flash("error", "<strong>Whoops!</strong> Either select contact or phone group to send SMS.");
            return back();
        }
           if(strlen($request->compose_sms) > 466){
            session()->flash("error", "<strong>Whoops!</strong> You've exceeded the number of characters allowed for SMS.");
            return back();
        }

        $account = BulkSmsAccount::where('tenant_id', Auth::user()->tenant_id)->get();
         $contacts = null;
         $recipients = [];
        $new_numbers = null;
        $pg_count = 0;
        if(!empty($request->phone_groups)){
            for($c = 0; $c<count($request->phone_groups); $c++){
                $number = PhoneGroup::where('id', $request->phone_groups[$c])->where('tenant_id', Auth::user()->tenant_id)->first();
                $contacts .= $number->phone_numbers;
                $pg_count = explode(",", $contacts);
            }
        }

        if(!empty($request->selectedContacts)){
            $unique = array_unique($request->selectedContacts);
            $phone_numbers = implode(",",$unique);
            $recipients = explode(',', $contacts.''.$phone_numbers);
        }
        $cost = 0;
          $merge = $contacts.''.implode(",", $recipients);
        if(strlen($request->compose_sms) <= 160 ){
            $cost = 1 * count(explode(",", $merge));
         }else if(strlen($request->compose_sms) <= 313){
            $cost = 2 * count(explode(",", $merge));
         }else if(strlen($request->compose_sms) <= 466){
            $cost = 3 * count(explode(",", $merge));
         }
        //return dd($cost." ".strlen($request->compose_sms)." ".count(explode(",",$merge)));
         return view('email-sms.compose-sms-preview',
         ['cost'=>$cost,
         'senderId'=>$request->sender_id,
         'message'=>$request->compose_sms,
         'account'=>$account,
         'phone_numbers'=>$recipients,
             'counter'=>count(explode(",",$merge)),
             'merged_contacts'=>$merge
         ]);


    }

    public function sendSMS(Request $request){
        $sms = new BulkSms;
        $sms->message = $request->textMessage;
        $sms->tenant_id = Auth::user()->tenant_id;
        $sms->sent_by = Auth::user()->id;
        $sms->sender_id = $request->senderId ?? 'CNX Retail';
        $sms->slug = substr(sha1(time()),23,40);
        $sms->save();
        $smsId = $sms->id;

        #recipient
        $unique = array_unique(explode(',',$request->phoneNumbers));
        $phone_numbers = implode(",",$unique);
        $recipients = explode(',',$phone_numbers);
        $numbers = $this->strip($recipients);
        //$recipients = explode(',', $contacts.''.$phone_numbers);
        for($i = 0; $i<count($recipients); $i++){
            $recipient = new BulkSmsRecipient;
            $recipient->sms_id = $smsId;
            $recipient->contact_id = $recipients[$i];
            $recipient->save();
        }

        try{
            #bulk SMS
            $mobile = implode(",", $recipients);


            //$name = "Joseph";
            $message = $request->textMessage;
       /*     $messageLength = strlen($request->textMessage);
            $contactCount = explode(", ", $mobile);
            $createdURL = "";
            $ozSURL = "http://www.bbnsms.com/bulksms/bulksms.php";
            $ozUser = "talktojoegee@gmail.com";
            $ozMessageType = $request->sender_id ?? 'CNX Retail';
            $ozPassw = "password123";
            $ozMessageType = $request->senderId ?? 'CNX Retail';
            $ozRecipient = $mobile;
            $ozMessageData = $message;
            $createdURL = $ozSURL."?username=".trim($ozUser)."&password=".trim($ozPassw)."&sender=".trim($ozMessageType)."&mobile=".trim($ozRecipient)."&message=".$ozMessageData;
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $createdURL);*/
            #Make deduction
            /*$account = BulkSmsAccount::where('tenant_id', Auth::user()->tenant_id)->first();
            $account->amount = $this->smsBiller($request->textMessage, $contactCount);
            //$account->debit = $*/
            ////"'.$mobile.'",
            //$phone = $request->phone;
             //  $phone = substr($phone, 1); //strip the first zero
             //  $phone = '234'. $phone;
               //$otp = $this->FourRandomDigits();

               $curl = curl_init();
               $channel = 'generic';
               $sender = $request->senderId ?? 'CNXRetail'; //'CNXRetail'; //;
                $data = array("to" => $numbers, "from" => $sender, "sms"=>$message, "type"=>"plain", "channel"=>$channel, "api_key"=>$this->API_KEY );
                $post_data = json_encode($data);
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://termii.com/api/sms/send',
                CURLOPT_RETURNTRANSFER => true,
                //CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$post_data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
            $res = curl_exec($curl);
            curl_close($curl);
            #update tenant SMS account balance
            $balance = new BulkSmsAccount();
            $balance->tenant_id = Auth::user()->tenant_id;
            $balance->debit = $this->smsBiller($message, $mobile);
            $balance->narration = "Billed for sending SMS.";
            $balance->amount =  $this->smsBiller($message, $mobile);
            $balance->ref_no = substr(sha1(time()),32,40);
            $balance->save();
            $this->activitylog->setNewActivityLog('SMS sent', 'Sent bulk SMS.');
            session()->flash("success", "<strong>Success! </strong> Text message sent.");
            return redirect()->route('bulksms');

        }catch (\Exception $ex){
            session()->flash("error", "<strong>Whoops!</strong> Could not send SMS. Try again.");
            return back();
        }


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
    public function smsCostEvaluator($message){
        if(strlen($message) <= 160 ){
            $cost = 3 * 1;
        }else if(strlen($message) <= 313){
            $cost = 3 * 2;
        }else if(strlen($message) <= 466){
            $cost = 3 * 3;
        }
        return $cost;
    }

    public function readSMS($slug){
        $sms = BulkSms::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        if(!empty($sms)){
            return view('email-sms.read-sms',['sms'=>$sms]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }


    public function phonegroup(){
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $phonegroups = PhoneGroup::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('email-sms.phonegroup', ['contacts'=>$contacts, 'phonegroups'=>$phonegroups]);
    }

    public function storeNewPhoneGroup(Request $request){
        $this->validate($request,[
            'group_name'=>'required',
            'selectedContacts'=>'required'
        ]);
        $unique = array_unique($request->selectedContacts);
        $phone_numbers = implode(",",$unique);
        $group = new PhoneGroup;
        $group->group_name = $request->group_name;
        $group->phone_numbers = $phone_numbers;
        $group->tenant_id = Auth::user()->tenant_id;
        $group->added_by = Auth::user()->id;
        $group->slug = substr(sha1(time()), 12,40);
        $group->save();
        $this->activitylog->setNewActivityLog('Phone group', 'Created a new phonegroup. ('.$group->group_name.')');
        session()->flash("success", "<strong>Success!</strong> Phone group created.");
        return redirect()->route('phonegroup');
    }

    public function updatePhoneGroup($slug){
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $group = PhoneGroup::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        $phonegroups = PhoneGroup::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('email-sms.update-phonegroup', ['contacts'=>$contacts, 'group'=>$group, 'phonegroups'=>$phonegroups]);
    }

    public function editPhoneGroup(Request $request){
        $this->validate($request,[
            'group_name'=>'required',
            'phone_numbers'=>'required'
        ]);
        $group =  PhoneGroup::where('id', $request->groupName)->where('tenant_id', Auth::user()->tenant_id)->first();
        $group->group_name = $request->group_name;
        $group->phone_numbers = $request->phone_numbers;
        $group->tenant_id = Auth::user()->tenant_id;
        $group->added_by = Auth::user()->id;
        $group->save();
        $this->activitylog->setNewActivityLog('Update phone group', 'Made changes to phone group.');
        session()->flash("success", "<strong>Success!</strong> Changes saved.");
        return redirect()->route('phonegroup');
    }


    public function bulksmsBalance(){
        $transactions = BulkSmsAccount::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('email-sms.bulksms-balance', ['transactions'=>$transactions]);
    }
    public function buyUnits(){

        return view('email-sms.bulksms-buy-units', ['plans'=>$this->bulksmspricing->getBulkSmsPricing()]);
    }

    public function buyUnitsTransaction(Request $request){
        $this->validate($request,[
            'sms_quantity'=>'required',
            'totalAmount'=>'required',
            'transaction'=>'required'
        ]);

        $unit = new BulkSmsAccount;
        $unit->ref_no = $request->transaction;
        $unit->credit = $request->sms_quantity;
        //$unit->ref_no = $request->transaction;
        $unit->narration = "Account credited with ".$request->sms_quantity." units.";
        $unit->tenant_id = Auth::user()->tenant_id;
        $unit->amount = $request->totalAmount;
        $unit->save();
        $this->activitylog->setNewActivityLog('Buy units', 'Bought units');
        return response()->json(['route'=>'bulksms-balance'], 201);
    }


    public function emailTemplates(){
        $templates = EmailTemplate::orderBy('template_name', 'ASC')->get();
        return view('email-sms.email-templates', ['templates'=>$templates]);
    }


    public function addNewEmailTemplate(Request $request){
        $this->validate($request,[
            'template_name'=>'required',
            'template'=>'required'
        ]);
        if(!empty($request->file('template'))){
            $extension = $request->file('template');
            $extension = $request->file('template')->getClientOriginalExtension();
            $size = $request->file('template')->getSize();
            $dir = 'assets/uploads/cnxdrive/';
            $filename = uniqid().'_'.time().'_'.date('Ymd').'.'.$extension;
            $request->file('template')->move(public_path($dir), $filename);
        }else{
            $filename = '';
        }
        $template = new EmailTemplate;
        $template->template_name = $request->template_name;
        $template->directory = $filename;
        $template->save();
        return response()->json(['message'=>'Success! Template saved.'],201);
    }
}
