<?php

namespace App\Http\Controllers\Backend;

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
use App\Models\PaymentHistory;
use Auth;
class SalesInvoiceController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function viewInvoice($slug){
        $invoice = InvoiceMaster::where('slug',$slug)->where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($invoice)){
            $invoices = InvoiceDetail::where('invoice_id',$invoice->id)->where('tenant_id', Auth::user()->tenant_id)->get();
            return view('sales-invoice.view-invoice',['invoice'=>$invoice,'invoices'=>$invoices]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }


    public function declineInvoice($slug){
        $invoice = InvoiceMaster::where('slug',$slug)->where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($invoice)){
            $invoice->trash = 1;
            $invoice->trashed_by = Auth::user()->id;
            $invoice->trash_date = now();
            $invoice->save();
            return redirect()->route('invoices');
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }


    public function receivePayment($slug){
        $invoice = InvoiceMaster::where('slug',$slug)->where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($invoice)){
            $invoices = InvoiceMaster::where('contact_id',$invoice->contact_id)
                                    ->where('tenant_id', Auth::user()->tenant_id)
                                    ->where('status', 0)
                                    ->where('currency_id', $invoice->currency_id)
                                    ->get();
            $total = 0;
            return view('sales-invoice.receive-payment',['invoice'=>$invoice,'invoices'=>$invoices, 'total'=>$total]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }


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

    public function receipts(){
        $receipts = ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        if(count($receipts) > 0){
            return view('sales-invoice.receipts', ['receipts'=>$receipts]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }


    public function viewReceipt($slug){
        $receipt = ReceiptMaster::where('slug',$slug)->where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($receipt)){
            $receipts = ReceiptDetail::where('receipt_id',$receipt->id)->where('tenant_id', Auth::user()->tenant_id)->get();
            return view('sales-invoice.view-receipt',['receipt'=>$receipt,'receipts'=>$receipts]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function newInvoice(){
        $services = Service::where('tenant_id', Auth::user()->tenant_id)->get();
        $contacts = Contact::where('tenant_id', Auth::user()->tenant_id)->get();
        $currencies = Currency::all();
        $invoiceNo = null;
        $invoice = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->first();
        if(!empty($invoice)){
            $invoiceNo = $invoice->invoice_no + 1;
        }else{
            $invoiceNo = 10000;
        }
        return view('sales-invoice.new-invoice', ['services'=>$services,'invoiceNo'=>$invoiceNo, 'contacts'=>$contacts, 'currencies'=>$currencies]);

    }




    /* public function filterSalesReport(Request $request){
        $this->validate($request,[
            'from'=>'required',
            'to'=>'required'
        ]);
        $receipts = ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('issue_date', [$request->from, $request->to])->get();
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('date_inputed', [$request->from, $request->to])->get();
        $invoices = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('issue_date', [$request->from, $request->to])->get();
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)
                                    ->whereBetween('bill_date', [$request->from, $request->to])->get();
        return view('sales-invoice.sales-report', [
            'receipts'=>$receipts,
            'payments'=>$payments,
            'bills'=>$bills,
            'invoices'=>$invoices,
            'from'=>$request->from,
            'to'=>$request->to
            ]);

    }
 */


    public function invoicePaymentHistory($slug){
        $invoice = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        if(!empty($invoice)){
            $invoices = ReceiptDetail::where('tenant_id', Auth::user()->tenant_id)->where('invoice_id', $invoice->id)->get();

            return view('sales-invoice.invoice-payment-history', ['invoices'=>$invoices,'invoice'=>$invoice]);
        }
    }


    public function sendInvoiceAsEmail(Request $request){
        $invoice = InvoiceMaster::where('id', $request->invoiceId)->where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($invoice)){
            $invoices = InvoiceDetail::where('invoice_id',$invoice->id)->where('tenant_id', Auth::user()->tenant_id)->get();
            \Mail::to(new SendInvoiceMail($invoice, $invoices, Contact::find($invoice->contact_id)));
            session()->flash("success", "<strong>Success!</strong> Invoice sent via email");
            return redirect()->route('invoices');
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

}
