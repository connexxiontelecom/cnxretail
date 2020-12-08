<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function quotations(){
        return view('quotation.quotations');
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
        return view('quotation.new-quotation', ['services'=>$services,'quotationNo'=>$quotationNo, 'contacts'=>$contacts, 'currencies'=>$currencies]);

    }



}
