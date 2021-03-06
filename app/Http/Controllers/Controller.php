<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\SendInvoiceMail;
use App\Models\InvoiceMaster;
use App\Models\InvoiceDetail;
use App\Models\ReceiptMaster;
use App\Models\ReceiptDetail;
use App\Models\PayMaster;
use App\Models\BillMaster;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Service;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Bank;
use App\Models\PaymentHistory;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function storeNewReceipt(Request $request){
        $this->validate($request,[
            'payment'=>'required',
            'payment_date'=>'required|date',
            'payment_method'=>'required',
            'reference_no'=>'required',
        ]);
        $totalAmount = 0;
        for($i = 0; $i<count($request->payment); $i++){
            $totalAmount += $request->payment[$i];
        }
        $receipt = new ReceiptMaster;
        $receipt->contact_id = $request->contact;
        $receipt->tenant_id = Auth::user()->tenant_id;
        $receipt->issued_by = Auth::user()->id;
        $receipt->ref_no = $request->reference_no;
        $receipt->issue_date = $request->payment_date;
        $receipt->amount = $totalAmount * $request->exchange_rate[0];
        $receipt->exchange_rate = $request->exchange_rate[0];
        $receipt->currency_id = $request->currency[0];
        $receipt->payment_type = $request->payment_method;
        $receipt->bank_id = $request->bank;
        $receipt->slug = substr(sha1(time()),34,40);
        $receipt->save();
        $receiptId = $receipt->id;
        for($n = 0; $n<count($request->payment); $n++){
            $detail = new ReceiptDetail;
            $detail->invoice_id = $request->invoice[$n];
            $detail->receipt_id = $receiptId;
            $detail->tenant_id = Auth::user()->tenant_id;
            $detail->payment = $request->payment[$n];
            $detail->exchange_rate = $request->exchange_rate[$n];
            $detail->currency_id = $request->currency[$n];
            $detail->save();
            #update invoice
            $updateInvoice = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->where('id', $request->invoice[$n])->first();
            if(!empty($updateInvoice)){
                $updateInvoice->paid_amount += $request->payment[$n] * $request->exchange_rate[$n];
                if($updateInvoice->paid_amount >= $updateInvoice->total){
                    $updateInvoice->status = 1; //marked as complete
                    $updateInvoice->posted = 1;
                    $updateInvoice->posted_by = Auth::user()->id;
                    $updateInvoice->post_date = now();

                }
                $updateInvoice->save();
            }
        }
        #Payment history
        $history = new PaymentHistory;
        $history->contact_id = $request->contact;
        $history->amount = $totalAmount * $request->exchange_rate[0];
        $history->type = 1;//Receipt
        $history->transaction_date = now();
        $history->narration = $totalAmount * $request->exchange_rate[0]." received with reference no. ".$request->reference_no;
        $history->tenant_id = Auth::user()->tenant_id;
        $history->save();
         #if payment is complete register contact as deal
         $clientExist = Deal::where('client_id', $request->contact)->where('tenant_id', Auth::user()->tenant_id)->first();
         if(empty($clientExist)){
             $lead = new Deal;
             $lead->client_id = $request->contact;
             $lead->tenant_id = Auth::user()->tenant_id;
             $lead->converted_by = Auth::user()->id;
             $lead->save();
         }
        return redirect()->route('receipts');

    }
}
