<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Membership;
use Carbon\Carbon;
use Paystack;
use Auth;

class GeneralSettingsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function generalSettings(){
        return view('settings.general-settings');
    }

    public function storeGeneralSettings(Request $request){
        $this->validate($request,[
            'business_name'=>'required',
            'phone_no'=>'required',
            'office_address'=>'required'
            ]);


        $settings = Tenant::Where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($settings)){

            if(!empty($request->file('logo'))){
                $extension = $request->file('logo');
                $extension = $request->file('logo')->getClientOriginalExtension();
                $size = $request->file('logo')->getSize();
                $dir = 'assets/uploads/cnxdrive/';
                $logo = uniqid().'_'.time().'_'.date('Ymd').'.'.$extension;
                $request->file('logo')->move(public_path($dir), $logo);
            }else{
                $logo = '';
            }
            if(!empty($request->file('siteicon'))){
                $extension = $request->file('siteicon');
                $extension = $request->file('siteicon')->getClientOriginalExtension();
                $size = $request->file('siteicon')->getSize();
                $dir = 'assets/uploads/cnxdrive/';
                $siteicon = uniqid().'_'.time().'_'.date('Ymd').'.'.$extension;
                $request->file('siteicon')->move(public_path($dir), $siteicon);
            }else{
                $siteicon = '';
            }
            $settings = Tenant::where('tenant_id', Auth::user()->tenant_id)->first();
            $settings->company_name = $request->business_name;
            $settings->tenant_id = Auth::user()->tenant_id;
            $settings->email = $request->email_address;
            $settings->phone = $request->phone_no;
            $settings->start = $request->opening_hour ?? '';
            $settings->end = $request->closing_hour ?? '';
            $settings->logo = $logo;
            $settings->favicon = $siteicon;
            $settings->website = '';
            $settings->save();
            session()->flash("success", "Great! Changes saved.");
            return back();
        }
    }

    public function emailSettings(){
        return view('settings.email-settings');
    }

    public function storeAPISettings(Request $request){
        $this->validate($request,[
            'live_public_key'=>'required',
            'live_secret_key'=>'required'
        ]);
        $api = Tenant::where('tenant_id', Auth::user()->tenant_id)->first();
        $api->secret_key = $request->live_secret_key ?? '';
        $api->public_key = $request->live_public_key ?? '';
        $api->save();
        session()->flash("success", "<strong>Success! </strong> API settings saved.");
        return back();
    }


    public function renewSubscription(){
        return view('settings.renew-subscription');
    }

    public function updateSubscription(Request $request){
        $this->validate($request,[
            'tenant'=>'required',
            'plan'=>'required'
        ]);
        $current = Carbon::now();
        $key = "key_".substr(sha1(time()),21,40 );
        #Register new tenant
        $tenant = Tenant::where('tenant_id', $request->tenant)->first();
        $tenant->active_sub_key = $key;
        $tenant->plan_id = $request->plan;
        $tenant->start = now();
        if($request->plan == 2){
            $tenant->end = $current->addDays(30);
        }else if($request->plan == 3){
            $tenant->end = $current->addDays(30*6);
        }else if($request->plan == 4){
            $tenant->end = $current->addDays(365);
        }
        $tenant->save();
        #Register subscription
        $subscriptions = Membership::where('tenant_id', $request->tenant)->get();
        foreach($subscriptions as $sub){
            $sub->status = 0; //inactive
            $sub->save();
        }
        $member = new Membership;
        $member->tenant_id = $request->tenant;
        $member->plan_id = $request->plan;
        $member->sub_key = $key;
        $member->status = 1; //active;
        $member->start_date = now();
        if($request->plan == 2){
            $member->end_date = $current->addDays(30);
        }else if($request->plan == 3){
            $member->end_date = $current->addDays(30*6);
        }else if($request->plan == 4){
            $member->end_date = $current->addDays(365);
        }
        $member->amount = $request->amount ?? 0;
        $member->save();
        #Update users table
        $users = User::where('tenant_id', $request->tenant)->get();
        foreach($users as $use){
            $use->account_status = 1; //active;
            $use->save();
        }
        return response()->json(['message'=>'Success! Subscription renewed!']);
    }
}
