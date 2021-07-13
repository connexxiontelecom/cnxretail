<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BillDetail;
use App\Models\BillMaster;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Deal;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\Lead;
use App\Models\PayMaster;
use App\Models\PaymentDetail;
use App\Models\ReceiptDetail;
use App\Models\ReceiptMaster;
use App\Models\Reminder;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class dashboardController extends Controller
{

    public function fetchReminders(Request $request)
    {
        $tenant_id = $request->tenant_id;
        $user_id = $request->user_id;
        if (is_null($tenant_id) || is_null($user_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $reminders = Reminder::where('tenant_id', $tenant_id)->where('set_by', $user_id)->orderBy('id', 'DESC')->get();
            foreach ($reminders as $reminder) {
                $reminder["set_by_name"] = User::find($reminder["set_by"])["full_name"];
            }
            return response()->json(['data' => $reminders], 200);
        }

    }

    public function fetchInvoices(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $invoices = InvoiceMaster::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();

            foreach ($invoices as $invoice) {

                //$invoice["Service"] =  $invoice->getInvoiceService[0];

                //fetch contact invoice was issued to
                $invoice['contact'] = Contact::find($invoice["contact_id"]);

                //fetch details of particular invoice
                $invoice['details'] = InvoiceDetail::leftJoin('services', function ($join) {
                    $join->on('invoice_details.service_id', '=', 'services.id');
                })->where('invoice_details.tenant_id', $tenant_id)->where('invoice_id', $invoice["id"])->get();

                //fetch who issued invocie
                $invoice['issued_by_name'] = User::find($invoice["issued_by"])["full_name"];
            }
            return response()->json(['data' => $invoices], 200);
        }

    }

    //fetch Details for all Invoices
    public function fetchInvoiceDetails(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $invoiceDetails = InvoiceDetail::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();

            return response()->json(['data' => $invoiceDetails], 200);
        }

    }

    public function fetchReceipts(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $receipts = ReceiptMaster::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();

            foreach ($receipts as $receipt) {
                //$invoice["Service"] =  $invoice->getInvoiceService[0];
                //fetch receipt details
                $receipt['details'] = ReceiptDetail::leftJoin('invoice_masters', function ($join) {
                    $join->on('receipt_details.invoice_id', '=', 'invoice_masters.id');
                })->where('receipt_details.tenant_id', $tenant_id)->where('receipt_id', $receipt["id"])->get();

                //fetch who issued receipt
                $receipt['issued_by_name'] = User::find($receipt["issued_by"])["full_name"];

                //fetch contact receipt was issued to
                $receipt['contact'] = Contact::find($receipt["contact_id"]);
            }

            return response()->json(['data' => $receipts], 200);
        }

    }

    public function fetchReceiptsDetails(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $receiptsDetails = ReceiptDetail::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();
            return response()->json(['data' => $receiptsDetails], 200);
        }

    }

    public function fetchBills(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $bills = BillMaster::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();

            foreach ($bills as $bill) {
                //$invoice["Service"] =  $invoice->getInvoiceService[0];

                //fetch bill details
                $bill['details'] = BillDetail::leftJoin('services', function ($join) {
                    $join->on('bill_details.service_id', '=', 'services.id');
                })->where('bill_details.tenant_id', $tenant_id)->where('bill_id', $bill["id"])->get();

                //fetch vendor that owns bill
                $bill['vendor'] = Contact::find($bill["vendor_id"]);

                //fetch who issued bill
                $bill['issued_by_name'] = User::find($bill["user_id"])["full_name"];
            }

            return response()->json(['data' => $bills], 200);
        }

    }

    public function fetchBillsDetails(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $billsDetail = BillDetail::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();
            return response()->json(['data' => $billsDetail], 200);
        }

    }

    public function fetchPayments(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $payments = PayMaster::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();


            foreach ($payments as $payment) {
                //$invoice["Service"] =  $invoice->getInvoiceService[0];

                //fetch bill details
                $payment['details'] = PaymentDetail::leftJoin('bill_masters', function ($join) {
                    $join->on('payment_details.bill_id', '=', 'bill_masters.id');
                })->where('payment_details.tenant_id', $tenant_id)->where('pay_id', $payment["id"])->get();


                //fetch vendor payment was issued
                $payment['vendor'] = Contact::find($payment["vendor_id"]);

                //fetch who issued payment
                $payment['issued_by_name'] = User::find($payment["user_id"])["full_name"];
            }

            return response()->json(['data' => $payments], 200);
        }

    }

    public function fetchPaymentsDetails(Request $request)
    {
        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $paymentDetails = PaymentDetail::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();
            return response()->json(['data' => $paymentDetails], 200);
        }

    }



    public function fetchServices(Request $request){

        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $services = Service::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();
            return response()->json(['data' => $services], 200);
        }

    }


    public function fetchContacts(Request $request){

        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $contacts = Contact::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();
            foreach($contacts as $contact){
               $contact->getConversations;
            }
            return response()->json(['data' => $contacts], 200);
        }

    }




    public function fetchLeads(Request $request){

        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $leads = Lead::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();

            foreach($leads as $lead){

               $contact = Contact::find($lead['contact_id']);
               $contact->getConversations;
               $lead['contact'] = $contact;
                $lead['convertedby'] = User::find($lead['converted_by']);
            }

            return response()->json(['data' => $leads], 200);
        }

    }




    public function fetchDeals(Request $request){

        $tenant_id = $request->tenant_id;
        if (is_null($tenant_id)) {
            return response()->json(['data' => "Request Error"], 200);
        } else {

            $deals = Deal::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();

            foreach($deals as $deal){

               $contact = Contact::find($deal['client_id']);
               $contact->getConversations;
               $deal['contact'] = $contact;
                $deal['convertedby'] = User::find($deal['converted_by']);
            }

            return response()->json(['data' => $deals], 200);
        }

    }


    public function fetchCurrencies(Request $request){

        $deals = Currency::orderBy('id', 'DESC')->get();
        return response()->json(['data' => $deals], 200);


    }











}
