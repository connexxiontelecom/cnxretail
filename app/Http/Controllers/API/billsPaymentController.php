<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BillMaster;
use App\Models\PayMaster;
use App\Models\PaymentDetail;
use App\Models\User;
use Illuminate\Http\Request;

class billsPaymentController extends Controller
{
    //

    public function createPayment(Request $request)
    {

        $bill = new PayMaster;
        $bill->vendor_id = $request->contact;
        $bill->tenant_id = $request->tenant;
        $bill->user_id = $request->issuedby;
        $bill->ref_no = $request->reference;
        $bill->date_inputed = $request->date;
        $bill->amount = $request->total;
        $bill->exchange_rate = $request->exchangerate;
        $bill->currency_id = $request->currency;
        $bill->payment_type = $request->paymentmethod;
        $bill->bank_id = $request->bank;
        $bill->slug = substr(sha1(time()), 34, 40);
        $bill->save();
        $billId = $bill->id;

        #PaymentDetail
        foreach ($request->items as $item) {
            $detail = new PaymentDetail;
            $detail->bill_id = $item['bill']['id'];
            $detail->pay_id = $billId;
            $detail->tenant_id = $request->tenant;
            $detail->pay_amount = $item['payment'];
            //$detail->exchange_rate = $item['bill']['exchange_rate'];
            //$detail->currency_id = $item['bill']['currency_id'];
            $detail->save();

            #update invoice
            $updateBill = BillMaster::where('tenant_id', $request->tenant)->where('id', $item['bill']['id'])->first();
            if (!empty($updateBill)) {
                $updateBill->paid_amount += (double)$item['payment'];
                if ($updateBill->paid_amount >= $updateBill->bill_amount) {
                    $updateBill->status = 'paid'; //marked as complete
                    $updateBill->posted = 1;
                    $updateBill->posted_by = $request->issuedby;
                    $updateBill->post_date = now();
                }
                $updateBill->save();
            }

        }
        return response()->json(['response' => 'success']);
    }

}

