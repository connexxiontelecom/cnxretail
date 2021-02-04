<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
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
    public function __construct(){
        $this->middleware('auth');
    }

    public function mailbox(){
        $emails = EmailCampaign::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        return view('email-sms.mailbox', ['mails'=>$emails]);
    }

    public function composeEmail(){
        $templates = EmailTemplate::orderBy('template_name', 'ASC')->get();
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
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

        return response()->json(['route'=>'mailbox'],201);

    }

    public function readMail($slug){
        $mail = EmailCampaign::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        if(!empty($mail)){
            return view('email-sms.read-mail',['mail'=>$mail]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function bulksms(){
        $bulksms = BulkSms::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        return view('email-sms.bulksms', ['bulksms'=>$bulksms]);
    }


    public function composeSms(){
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $phonegroups = PhoneGroup::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('email-sms.compose-sms', ['contacts'=>$contacts, 'phonegroups'=>$phonegroups]);
    }


    public function storeSMS(Request $request){
       /*  $this->validate($request,[
            'recipient'=>'required',
            'compose_sms'=>'required'
        ]); */
        $contacts = null;
        $new_numbers = null;
        if(!empty($request->phone_groups)){
            for($c = 0; $c<count($request->phone_groups); $c++){
                $number = PhoneGroup::where('id', $request->phone_groups[$c])->where('tenant_id', Auth::user()->tenant_id)->first();
                $contacts .= $number->phone_numbers;
            }
        }
        $unique = array_unique($request->selectedContacts);
        $phone_numbers = implode(",",$unique);
        $recipients = explode(',', $contacts.''.$phone_numbers);
        $sms = new BulkSms;
        $sms->message = $request->compose_sms;
        $sms->tenant_id = Auth::user()->tenant_id;
        $sms->sent_by = Auth::user()->id;
        $sms->sender_id = $request->sender_id ?? 'CNX Retail';
        $sms->slug = substr(sha1(time()),23,40);
        $sms->save();
        $smsId = $sms->id;

        #recipient
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
        $message = $request->compose_sms;
        $createdURL = "";
        $ozSURL = "http://www.bbnsms.com/bulksms/bulksms.php";
        $ozUser = "talktojoegee@gmail.com";
<<<<<<< Updated upstream
        $ozPassw = "Lordofmylife123";
        $ozMessageType = $request->sender_id ?? 'CNX Retail';
=======
        $ozPassw = "password123";
        $ozMessageType = $request->senderId ?? 'CNX Retail';
>>>>>>> Stashed changes
        $ozRecipient = $mobile;
        $ozMessageData = $message;
        $createdURL = $ozSURL."?username=".trim($ozUser)."&password=".trim($ozPassw)."&sender=".trim($ozMessageType)."&mobile=".trim($ozRecipient)."&message=".$ozMessageData;
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $createdURL);
        return response()->json(['route'=>'bulksms'],201);

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
        session()->flash("success", "<strong>Success!</strong> Changes saved.");
        return redirect()->route('phonegroup');
    }


    public function bulksmsBalance(){
        $transactions = BulkSmsAccount::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('email-sms.bulksms-balance', ['transactions'=>$transactions]);
    }
    public function buyUnits(){

        return view('email-sms.bulksms-buy-units');
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
        $unit->ref_no = $request->transaction;
        $unit->narration = "Account credited with ".$request->sms_quantity." units.";
        $unit->tenant_id = Auth::user()->tenant_id;
        $unit->amount = $request->totalAmount;
        $unit->save();
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
