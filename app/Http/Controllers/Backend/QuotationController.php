<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\QuotationMaster;
use App\Models\QuotationDetail;
use Auth;
class QuotationController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function quotations(){
        $quotations = QuotationMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('quotation.quotations', ['quotations'=>$quotations]);
    }


    public function newQuotation(){
        $services = Service::where('tenant_id', Auth::user()->tenant_id)->get();
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $currencies = Currency::all();
        $quotationNo = null;
        $quotation = QuotationMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->first();
        if(!empty($quotation)){
            $quotationNo = $quotation->quotation_no + 1;
        }else{
            $quotationNo = 10000;
        }
        return view('quotation.add-new-quotation', ['services'=>$services,'quotationNo'=>$quotationNo, 'contacts'=>$contacts, 'currencies'=>$currencies]);

    }


    public function storeQuotation(Request $request){
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
        $quote = new QuotationMaster;
        $quote->quotation_no = $request->invoice_no;
        $quote->contact_id = $request->contact;
        $quote->issue_date = $request->issue_date;

        $quote->currency_id = $request->currency ?? 1;
        $quote->tenant_id = Auth::user()->tenant_id;
        $quote->issued_by = Auth::user()->id;
        $quote->ref_no = $ref_no;
        $quote->issue_date = $request->issue_date;
        $quote->total = $request->currency != Auth::user()->tenant->currency->id ? ($totalAmount * $request->exchange_rate + ($totalAmount*$request->vat)/100 * $request->exchange_rate ) : ($totalAmount + ($totalAmount*$request->vat)/100 ) ;
        $quote->sub_total = $request->currency != Auth::user()->tenant->currency->id ? ($totalAmount * $request->exchange_rate) -  (($totalAmount*$request->vat)/100 * $request->exchange_rate ): $totalAmount - ($totalAmount*$request->vat)/100 ;
        $quote->vat_rate = $request->vat;
        $quote->vat_amount = $request->currency != Auth::user()->tenant->currency->id ?  ($request->vat*$totalAmount)/100 * $request->exchange_rate : ($request->vat*$totalAmount)/100;
        $quote->exchange_rate = $request->exchange_rate ?? 1;
        $quote->slug = substr(sha1(time()),32,40);
        $quote->save();
        $quoteId = $quote->id;
        #Invoice detail
        for($n = 0; $n<count($request->total); $n++){
            $detail = new QuotationDetail;
            $detail->quotation_id = $quoteId;
            $detail->contact_id = $request->contact;
            $detail->tenant_id = Auth::user()->tenant_id;
            $detail->service_id = $request->service[$n];
            $detail->quantity = $request->quantity[$n];
            $detail->unit_cost = $request->unit_cost[$n];
            $detail->total = $request->unit_cost[$n] * $request->quantity[$n];
            $detail->save();
        }

        session()->flash("success", "<strong>Success!</strong> Quotation registered.");
        return redirect()->route('quotations');
    }

    public function viewQuotation($slug){
        $quote = QuotationMaster::where('slug',$slug)->where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($quote)){
            $quotations = QuotationDetail::where('quotation_id',$quote->id)->where('tenant_id', Auth::user()->tenant_id)->get();
            return view('quotation.view-quote',['quote'=>$quote,'quotations'=>$quotations]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

}
