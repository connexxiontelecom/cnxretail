<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InvoiceMaster;
use App\Models\ReceiptMaster;
use App\Models\BillMaster;
use App\Models\PayMaster;
use App\Models\Reminder;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function dashboard(){
        $invoices = InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $receipts = ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $bills = BillMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $payments = PayMaster::where('tenant_id', Auth::user()->tenant_id)->get();
        $reminders = Reminder::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('dashboard.index', [
            'invoices'=>$invoices,
            'receipts'=>$receipts,
            'payments'=>$payments,
            'bills'=>$bills,
            'reminders'=>$reminders,'users'=>$users]);
    }


}
