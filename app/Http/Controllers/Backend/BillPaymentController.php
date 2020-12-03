<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillMaster;
use App\Models\BillDetail;
use App\Models\PayMaster;
use App\Models\PayDetail;
use App\Models\Service;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Bank;
use App\Models\ActivityLog;
use Auth;
class BillPaymentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function bills(){
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        $log = new ActivityLog;
        $log->subject = "Bills view";
        $log->tenant_id = Auth::user()->tenant_id;
        $log->log = Auth::user()->full_name." open bills view";
        $log->save();
        return view('bills-payment.bills', ['bills'=>$bills]);
    }


    public function newBill(){
        $services = Service::where('tenant_id', Auth::user()->tenant_id)->get();
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $currencies = Currency::orderBy('name','ASC')->get();
        $billNo = null;
        $bill = BillMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->first();

        //$log->save();
        if(!empty($bill)){
            $billNo = $bill->bill_no + 1;
            #log
            $log = new ActivityLog;
            $log->subject = "New bill view";
            $log->tenant_id = Auth::user()->tenant_id;
            $log->log = Auth::user()->full_name." opened new bill interface.";
            return view('bills-payment.new-bill', ['services'=>$services,'billNo'=>$billNo, 'contacts'=>$contacts,'currencies'=>$currencies]);
        }else{
            $billNo = 1000;
            #log
            $log = new ActivityLog;
            $log->subject = "New bill view";
            $log->tenant_id = Auth::user()->tenant_id;
            $log->log = Auth::user()->full_name." opened new bill interface.";
            return view('bills-payment.new-bill', ['services'=>$services,'billNo'=>$billNo, 'contacts'=>$contacts, 'currencies'=>$currencies]);
        }

    }

    public function storeBill(Request $request){
        $this->validate($request,[
            'bill_date'=>'required|date|',
            'currency'=>'required'
        ]);
        $totalAmount = 0;
        for($i = 0; $i<count($request->total); $i++){
            $totalAmount += $request->total[$i];
        }

        $ref_no = strtoupper(substr(sha1(time()), 30,40));
        $bill = new BillMaster;
        $bill->vendor_id = $request->vendor;
        $bill->billed_to = $request->vendor;
        $bill->bill_date = $request->bill_date;
        $bill->currency_id = $request->currency ?? 1;
        $bill->bill_no = $request->bill_no;
        $bill->tenant_id = Auth::user()->tenant_id;
        $bill->user_id = Auth::user()->id;
        $bill->ref_no = $ref_no;
        $bill->bill_amount = $totalAmount + ($totalAmount*$request->vat)/100;
        $bill->vat_charge = $request->vat;
        $bill->vat_amount = ($request->vat*$totalAmount)/100;
        $bill->exchange_rate = 1;
        $bill->slug = substr(sha1(time()),32,40);
        $bill->save();
        $billId = $bill->id;
        #bill detail
        for($n = 0; $n<count($request->total); $n++){
            $detail = new BillDetail;
            $detail->bill_id = $billId;
            $detail->tenant_id = Auth::user()->tenant_id;
            $detail->service_id = $request->service[$n];
            $detail->quantity = $request->quantity[$n];
            $detail->rate = $request->unit_cost[$n];
            $detail->amount = $request->unit_cost[$n] * $request->quantity[$n];
            $detail->save();
        }

        return response()->json(['message'=>'Success! Bill submitted.']);

    }


    public function makePayment(){
        //$bill = BillMaster::where('slug',$slug)->where('tenant_id', Auth::user()->tenant_id)->first();
        /* $bills = BillMaster::where('vendor_id',$bill->vendor_id)
                                ->where('tenant_id', Auth::user()->tenant_id)
                                ->where('status', 0)
                                ->where('currency_id', $bill->currency_id)
                                ->get(); */
        $paymentNo = null;
        $pay = PayMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->first();
        if(!empty($pay)){
            $paymentNo = $pay->ref_no + 1;
        }else{
            $paymentNo = 1000;
        }
        $vendors = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $banks = Bank::where('tenant_id', Auth::user()->tenant_id)->get();
        $total = 0;
        return view('bills-payment.make-payment',['total'=>$total, 'vendors'=>$vendors,'paymentNo'=>$paymentNo,'banks'=>$banks]);
    }


    public function getVendor(Request $request){
        $this->validate($request,[
            'vendor'=>'required'
        ]);
        $total = 0;
        $vendor = Contact::where('tenant_id', Auth::user()->tenant_id)->where('id', $request->vendor)->first();
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)->where('vendor_id', $vendor->id)->get();
        return view('bills-payment.common._bills',['bills'=>$bills,'total'=>$total]);
    }
    public function addNewBank(Request $request){
        $this->validate($request,[
            'bank_name'=>'required',
            'account_name'=>'required',
            'account_number'=>'required'
        ]);
        $bank = new Bank;
        $bank->bank = $request->bank_name;
        $bank->tenant_id = Auth::user()->tenant_id;
        $bank->account_name = $request->account_name;
        $bank->account_no = $request->account_number;
        $bank->save();
        return response()->json(['message'=>'Success! Bank registered.']);
    }
}
