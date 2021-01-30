<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReceiptDetail;
use App\Models\ReceiptMaster;
use App\Models\Deal;
use App\Models\PaymentHistory;


class receiptController extends Controller
{
    public function createReceipt(Request $request){

        $receipt = new ReceiptMaster;
        $receipt->contact_id = $request->contact;
        $receipt->tenant_id = $request->tenant;
        $receipt->issued_by = $request->issuedby;
        $receipt->ref_no = $request->reference;
        $receipt->issue_date = $request->date;
        $receipt->amount = $request->total;
        $receipt->exchange_rate = $request->exchangerate;
        $receipt->currency_id = $request->currency;
        $receipt->payment_type = $request->paymentmethod;
        $receipt->bank_id = $request->bank;
        $receipt->slug = substr(sha1(time()),34,40);
        $receipt->save();
        $receiptId = $receipt->id;


        #ReceiptDetail
        foreach ($request->items as $item) {
            $detail = new ReceiptDetail;
            $detail->invoice_id = $item['invoice']['id'];
            $detail->receipt_id = $receiptId;
            $detail->tenant_id = $request->tenant;
            $detail->payment = $item['payment'];
            $detail->exchange_rate = $item['invoice']['exchange_rate'];
            $detail->currency_id = $item['invoice']['currency_id'];
            $detail->save();
        }

        #Payment history
        $history = new PaymentHistory;
        $history->contact_id = $request->contact;
        $history->amount = $request->total;
        $history->type = 2;//Receipt
        $history->transaction_date = now();
        $history->narration = $request->total. " received with reference no. ".$request->reference_no;
        $history->tenant_id = $request->tenant;
        $history->save();


         #if payment is complete register contact as deal
         $clientExist = Deal::where('client_id', $request->contact)->where('tenant_id', $request->tenant)->first();
         if(empty($clientExist)){
             $lead = new Deal;
             $lead->client_id = $request->contact;
             $lead->tenant_id = $request->tenant;
             $lead->converted_by = $request->issuedby;
             $lead->save();
         }

         return response()->json(['response'=>'success']);

    }
}
