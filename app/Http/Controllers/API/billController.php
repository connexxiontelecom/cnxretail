<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillMaster;
use App\Models\BillDetail;

class billController extends Controller
{
    public function createBill(Request $request){

        $ref_no = strtoupper(substr(sha1(time()), 30,40));
        $bill = new BillMaster;
        $bill->bill_no = 120031; //$request->bill_no;
        $bill->vendor_id = $request->contact;
        $bill->billed_to = $request->contact;
        $bill->bill_date = $request->issuedate;
        $bill->currency_id = $request->currency ?? 1;
        $bill->tenant_id = $request->tenant;
        $bill->user_id = $request->issuedby;
        $bill->ref_no = $ref_no;
        $bill->bill_amount = $request->grandtotal; //already calculated with exchange rate from front-end
        //$bill->sub_total = $request->subtotal;
        $bill->vat_charge = $request->vat;
        $bill->vat_amount = $request->vatamount;
        $bill->exchange_rate = $request->exchangerate ?? 1;
        $bill->slug = substr(sha1(time()),32,40);
        $bill->save();
        $billId = $bill->id;
        #bill detail
        foreach ($request->items as $item) {
            $detail = new BillDetail;
            $detail->bill_id = $billId;
           // $detail->contact_id = $request->contact;
            $detail->tenant_id = $request->tenant;
            $detail->service_id = $item['id'];
            $detail->quantity = $item['qty'];
            $detail->rate = $item['unitPrice'];
            $detail->amount =  (double)$item['qty'] * (double)$item['unitPrice'] * $request->exchangerate ?? 1;
            $detail->save();

        }

        return response()->json(['response'=>'success']);

    }
}
