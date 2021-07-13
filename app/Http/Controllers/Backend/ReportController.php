<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoiceMaster;
use App\Models\InvoiceDetail;
use App\Models\ReceiptMaster;
use App\Models\ReceiptDetail;
use App\Models\PayMaster;
use App\Models\PayDetail;
use App\Models\BillMaster;
use App\Models\BillDetail;
use App\Models\Contact;
use App\Models\PaymentHistory;
use Auth;

class ReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function salesReport(){
        $receipts = ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $invoices = InvoiceMaster::where('trash',0)->where('tenant_id', Auth::user()->tenant_id)->get();
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('reports.sales-report', [
            'receipts'=>$receipts,
            'payments'=>$payments,
            'bills'=>$bills,
            'invoices'=>$invoices,
            'from'=>now(),
            'to'=>now()
            ]);
    }


    public function filterSalesReport(Request $request){
        $this->validate($request,[
            'from'=>'required',
            'to'=>'required'
        ]);
        $receipts = ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('issue_date', [$request->from, $request->to])->get();
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('date_inputed', [$request->from, $request->to])->get();
        $invoices = InvoiceMaster::where('trash',0)->where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('issue_date', [$request->from, $request->to])->get();
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('bill_date', [$request->from, $request->to])->get();
        return view('reports.sales-report', [
            'receipts'=>$receipts,
            'payments'=>$payments,
            'bills'=>$bills,
            'invoices'=>$invoices,
            'from'=>$request->from,
            'to'=>$request->to
            ]);

    }


    public function paymentReport(){
        $receipts = ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $invoices = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('reports.payment-report', [
            'receipts'=>$receipts,
            'payments'=>$payments,
            'bills'=>$bills,
            'invoices'=>$invoices,
            'from'=>now(),
            'to'=>now()
            ]);
    }


    public function filterPaymentReport(Request $request){
        $this->validate($request,[
            'from'=>'required',
            'to'=>'required'
        ]);

        $receipts = ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('issue_date', [$request->from, $request->to])->get();
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('date_inputed', [$request->from, $request->to])->get();
        $invoices = InvoiceMaster::where('trash',0)->where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('issue_date', [$request->from, $request->to])->get();
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('bill_date', [$request->from, $request->to])->get();
        return view('reports.payment-report', [
            'receipts'=>$receipts,
            'payments'=>$payments,
            'bills'=>$bills,
            'invoices'=>$invoices,
            'from'=>$request->from,
            'to'=>$request->to
            ]);

    }

    public function imprestReport(){
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)->where('type', 2)->get();
        return view('reports.imprest-report', [
            'payments'=>$payments,
            'from'=>now(),
            'to'=>now()
            ]);
    }

    public function filterImprestReport(Request $request){
        $this->validate($request,[
            'from'=>'required',
            'to'=>'required'
        ]);
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)->where('type', 2)
                                    ->whereBetween('date_inputed', [$request->from, $request->to])->get();
        return view('reports.imprest-report', [
            'payments'=>$payments,
            'from'=>$request->from,
            'to'=>$request->to
            ]);

    }


    public function customerSalesReportStatement(){
        $contactIds = PaymentHistory::where('tenant_id', Auth::user()->tenant_id)->get();
        $ids = [];
        foreach($contactIds as $id){
            array_push($ids, $id->contact_id);
        }
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->whereIn('id', $ids)->orderBy('company_name', 'ASC')->get();
        return view('reports.customer-sales-report-statement',[
        'contacts'=>$contacts]);
    }
    public function filterCustomerSalesReportStatement(Request $request){
        $payments = PaymentHistory::where('tenant_id', Auth::user()->tenant_id)->where('contact_id', $request->contact)->get();
        return view('reports.statement._statement',[
        'payments'=>$payments, 'balance'=>0]);
    }

}
