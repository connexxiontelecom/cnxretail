<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use App\Models\InvoiceDetail;
use App\Models\PaymentHistory;
use App\Models\Lead;
use App\Models\Contact;
use App\Mail\SendInvoiceMail;
class invoiceController extends Controller
{
    public function createInvoice(Request $request){

        $ref_no = strtoupper(substr(sha1(time()), 30,40));
        $invoice = new InvoiceMaster;
        $invoice->invoice_no = 120031; //$request->invoice_no;
        $invoice->contact_id = $request->contact;
        $invoice->issue_date = $request->issuedate;
        $invoice->due_date = $request->duedate;
        $invoice->currency_id = $request->currency ?? 1;
        $invoice->tenant_id = $request->tenant;
        $invoice->issued_by = $request->issuedby;
        $invoice->ref_no = $ref_no;
        $invoice->total = $request->grandtotal;
        $invoice->sub_total = $request->subtotal;
        $invoice->vat_rate = $request->vat;
        $invoice->vat_amount = $request->vatamount;
        $invoice->exchange_rate = $request->exchangerate ?? 1;
        $invoice->slug = substr(sha1(time()),32,40);
        $invoice->save();
        $invoiceId = $invoice->id;
        #Invoice detail
        foreach ($request->items as $item) {
            $detail = new InvoiceDetail;
            $detail->invoice_id = $invoiceId;
            $detail->contact_id = $request->contact;
            $detail->tenant_id = $request->tenant;
            $detail->service_id = $item['id'];
            $detail->quantity = $item['qty'];
            $detail->unit_cost = $item['unitPrice'];
            $detail->total =  (double)$item['qty'] * (double)$item['unitPrice'] * $request->exchangerate ?? 1;
            $detail->save();
        }


        #Payment history
        $history = new PaymentHistory;
        $history->contact_id = $request->contact;
        $history->amount = $request->grandtotal;
        $history->type = 2;//Receipt
        $history->transaction_date = now();
        $history->narration = "Invoice generated. Invoice No. ".$request->invoice_no;
        $history->tenant_id = $request->tenant;
        $history->save();

        $clientExist = Lead::where('contact_id', $request->contact)->where('tenant_id', $request->tenant)->first();
        if(empty($clientExist)){
            $lead = new Lead;
            $lead->contact_id = $request->contact;
            $lead->tenant_id = $request->tenant;;
            $lead->converted_by = $request->issuedby;
            $lead->save();
        }
        return response()->json(['response'=>'success']);

    }




    public function sendInvoiceAsEmail(Request $request){

        $invoice = InvoiceMaster::where('id', $request->id)->where('tenant_id', $request->tenant_id)->first();
        if(!empty($invoice)){
            $contact = Contact::find($invoice->contact_id);
            \Mail::to($contact)->send(new SendInvoiceMail($invoice));
            return response()->json(['response'=>'success']);
        }else{
            return response()->json(['response'=>'error']);
        }
    }





}
