<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BillMaster;
use App\Models\InvoiceMaster;
use App\Models\PayMaster;
use App\Models\ReceiptMaster;
use Illuminate\Http\Request;

class reportsController extends Controller
{


    public function getSalesReport(Request $request)
    {

        if ($request->to != null && $request->from != null) {

            $receipts = ReceiptMaster::where('tenant_id', $request->tenant_id)
                ->whereBetween('issue_date', [$request->from, $request->to])->get();
            $payments = PayMaster::where('tenant_id', $request->tenant_id)
                ->whereBetween('date_inputed', [$request->from, $request->to])->get();
            $invoices = InvoiceMaster::where('tenant_id', $request->tenant_id)
                ->whereBetween('issue_date', [$request->from, $request->to])->get();
            $bills = BillMaster::where('tenant_id', $request->tenant_id)
                ->whereBetween('bill_date', [$request->from, $request->to])->get();

        } else if ($request->to != null && $request->from != null && $request->contactId != null) {

            $receipts = ReceiptMaster::where('tenant_id', $request->tenant_id)->where('contact_id', $request->contactId)
                ->whereBetween('issue_date', [$request->from, $request->to])->get();
            $payments = PayMaster::where('tenant_id', $request->tenant_id)->where('vendor_id', $request->contactId)
                ->whereBetween('date_inputed', [$request->from, $request->to])->get();
            $invoices = InvoiceMaster::where('tenant_id', $request->tenant_id)->where('contact_id', $request->contactId)
                ->whereBetween('issue_date', [$request->from, $request->to])->get();
            $bills = BillMaster::where('tenant_id', $request->tenant_id)->where('vendor_id', $request->contactId)
                ->whereBetween('bill_date', [$request->from, $request->to])->get();

        } else {
            $receipts = ReceiptMaster::where('tenant_id', $request->tenant_id)->get();
            $payments = PayMaster::where('tenant_id', $request->tenant_id)->get();
            $invoices = InvoiceMaster::where('tenant_id', $request->tenant_id)->get();
            $bills = BillMaster::where('tenant_id', $request->tenant_id)->get();
        }

        return response()->json([
            'receipts' => $receipts,
            'payments' => $payments,
            'bills' => $bills,
            'invoices' => $invoices,
            'from' => $request->from,
            'to' => $request->to,
        ], 200);
    }



}
