<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Imprest;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class imprestController extends Controller
{
    //
    public function fetchBanks(Request $request)
    {
        $this->validate($request,[
            'tenant_id'=>'required',
        ]);
        $tenant = $request->tenant_id;
        $banks = Bank::where('tenant_id', $tenant)->get();
        return response()->json(['data' => $banks], 200);
    }


    public function myImprest(){
       /* $banks = Bank::where('tenant_id', Auth::user()->tenant_id)->get();
        $users = User::where('tenant_id', Auth::user()->tenant_id)->orderBy('full_name', 'ASC')->get();*/
        $imprests = Imprest::where('user_id', Auth::user()->id)
            ->where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $imprests], 200);
    }
    public function allImprest(){
        $imprests = Imprest::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $imprests], 200);
    }

    public function postImprest(Request $request){
        $this->validate($request,[
            'date'=>'required',
            'amount'=>'required',
            'description'=>'required',
            'responsible_officer'=>'required'
        ]);
        $imprest = new Imprest;
        $imprest->amount = $request->amount;
        $imprest->transaction_date = $request->date;
        $imprest->description = $request->description;
        $imprest->user_id = Auth::user()->id;
        $imprest->tenant_id = Auth::user()->tenant_id;
        $imprest->responsible_officer = $request->responsible_officer;
        $imprest->bank_id = !(is_null($request->bank)) ? $request->bank : null;
        $imprest->save();
        return response()->json(['data' => $imprest], 200);
    }

    public function approveImprest(Request $request){
        $this->validate($request,[
            'imprest'=>'required'
        ]);
        $imprest = Imprest::where('tenant_id', Auth::user()->tenant_id)->where('id', $request->imprest)->first();
        if(!empty($imprest)){
            $pay = new PayMaster;
            $pay->tenant_id = $imprest->tenant_id;
            $pay->bank_id = $imprest->bank_id;
            $pay->vendor_id = 0; //imprest;
            $pay->exchange_rate = 1;
            $pay->currency_id = Auth::user()->tenant->currency->id;
            $pay->date_inputed = $imprest->transaction_date;
            $pay->amount = $imprest->amount;
            $pay->ref_no = strtoupper(substr(sha1(time()),34,40));
            $pay->payment_type = 1; //cash
            $pay->user_id = $imprest->user_id;
            $pay->posted = 1; //yes
            $pay->posted_date = now();
            $pay->slug = substr(sha1(time()),34,40);
            $pay->type = 2; //imprest,1=bill
            $pay->save();
            #update imprest
            $imprest->status = 1;
            $imprest->save();
            return response()->json(['message' => 'success'], 200);
        }

    }








}
