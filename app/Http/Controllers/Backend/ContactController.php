<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Service;
use App\Models\InvoiceMaster;
use App\Models\InvoiceDetail;
use App\Models\Prospecting;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Reminder;
use Auth;
use DB;

class ContactController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function allContacts(){
        $contacts = Contact::where('tenant_id',Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        return view('contact.all-contacts', ['contacts'=>$contacts]);
    }

    public function showAddNewContactForm(){
        return view('contact.add-new-contact');
    }

    public function storeNewContact(Request $request){
        $this->validate($request,[
            'company_name'=>'required',
            'company_address'=>'required',
            'company_email'=>'required|email',
            'company_phone_no'=>'required',
            'full_name'=>'required',
            'email_address'=>'required',
            'mobile_no'=>'required',
            'position'=>'required',
            'communication_channel'=>'required',
            'preferred_time'=>'required'
        ]);
        $contact = new Contact;
        $contact->added_by = 1;
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
        $contact->preferred_time = $request->preferred_time;
        $contact->slug = substr(sha1(time()),30,40);
        $contact->tenant_id = Auth::user()->tenant_id;
        $contact->save();
        return response()->json(['route'=>'all-contacts'],201);
    }

    public function viewContact($slug){
        $contact = Contact::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        if(!empty($contact) ){
            return view('contact.view-contact', ['contact'=>$contact]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function storeConversation(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'conversation'=>'required'
        ]);
        $conversation = new Conversation;
        $conversation->user_id = Auth::user()->id;
        $conversation->tenant_id = Auth::user()->tenant_id;
        $conversation->contact_id = $request->contact;
        $conversation->subject = $request->subject;
        $conversation->conversation = $request->conversation;
        $conversation->save();
        return response()->json(['message'=>'Success! Conversation registered.'],201);
    }

    public function convertToLead($slug){
        $contact = Contact::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        $services = Service::where('tenant_id', Auth::user()->tenant_id)->get();
        $invoiceNo = null;
        $invoice = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->first();
        if(!empty($invoice)){
            $invoiceNo = $invoice->invoice_no + 1;
        }else{
            $invoiceNo = 1000;
        }
        if(!empty($contact) ){
            return view('contact.convert-to-lead', ['contact'=>$contact, 'services'=>$services,'invoiceNo'=>$invoiceNo]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function storeInvoice(Request $request){
        $this->validate($request,[
            'issue_date'=>'required|date|',
            'due_date'=>'required|date|after_or_equal:issue_date',
            'currency'=>'required'
        ]);
        $totalAmount = 0;
        for($i = 0; $i<count($request->total); $i++){
            $totalAmount += $request->total[$i];
        }

        $ref_no = strtoupper(substr(sha1(time()), 30,40));
        $invoice = new InvoiceMaster;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->contact_id = $request->contact;
        $invoice->issue_date = $request->issue_date;
        $invoice->due_date = $request->due_date;
        $invoice->currency_id = $request->currency ?? 1;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->invoice_no = $request->invoice_no;
        $invoice->tenant_id = Auth::user()->tenant_id;
        $invoice->issued_by = Auth::user()->id;
        $invoice->ref_no = $ref_no;
        $invoice->issue_date = $request->issue_date;
        $invoice->due_date = $request->due_date;
        $invoice->total = $request->currency != Auth::user()->tenant->currency->id ? ($totalAmount * $request->exchange_rate + ($totalAmount*$request->vat)/100 * $request->exchange_rate ) : ($totalAmount + ($totalAmount*$request->vat)/100 ) ;
        $invoice->sub_total = $request->currency != Auth::user()->tenant->currency->id ? ($totalAmount * $request->exchange_rate) -  (($totalAmount*$request->vat)/100 * $request->exchange_rate ): $totalAmount - ($totalAmount*$request->vat)/100 ;
        $invoice->vat_rate = $request->vat;
        $invoice->vat_amount = $request->currency != Auth::user()->tenant->currency->id ?  ($request->vat*$totalAmount)/100 * $request->exchange_rate : ($request->vat*$totalAmount)/100;
        $invoice->exchange_rate = $request->exchange_rate ?? 1;
        $invoice->slug = substr(sha1(time()),32,40);
        $invoice->save();
        $invoiceId = $invoice->id;
        #Invoice detail
        for($n = 0; $n<count($request->total); $n++){
            $detail = new InvoiceDetail;
            $detail->invoice_id = $invoiceId;
            $detail->contact_id = $request->contact;
            $detail->tenant_id = Auth::user()->tenant_id;
            $detail->service_id = $request->service[$n];
            $detail->quantity = $request->quantity[$n];
            $detail->unit_cost = $request->unit_cost[$n];
            $detail->total = $request->unit_cost[$n] * $request->quantity[$n];
            $detail->save();
        }
        $clientExist = Lead::where('contact_id', $request->contact)->where('tenant_id', Auth::user()->tenant_id)->first();
        if(empty($clientExist)){
            $lead = new Lead;
            $lead->contact_id = $request->contact;
            $lead->tenant_id = Auth::user()->tenant_id;
            $lead->converted_by = Auth::user()->id;
            $lead->save();
        }
        return response()->json(['message'=>'Success! Invoice submitted.']);

    }

    public function invoices(){
        $invoices = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        if(count($invoices) > 0){
            return view('sales-invoice.invoices', ['invoices'=>$invoices]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function prospecting(Request $request){
        $this->validate($request,[
            'date_time'=>'required',
            'remarks'=>'required'
        ]);

        $prospect = new Prospecting;
        $prospect->contact_id = $request->prospectingContact;
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

    public function getContact(Request $request){
        $this->validate($request, [
            'contact'=>'required'
        ]);

        $contact = Contact::where('tenant_id', Auth::user()->tenant_id)->where('id', $request->contact)->first();
        return response()->json(['contact'=>$contact], 200);
    }
}
