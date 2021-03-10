<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
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
        if(empty($settings)){

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
            $settings->phone_no = $request->phone_no;
            $settings->start = $request->opening_hour ?? '';
            $settings->end = $request->closing_hour ?? '';
            $settings->logo = $logo;
            $settings->favicon = $siteicon;
            $settings->website = '';
            $settings->save();
            return response()->json(['message'=>'Success! Changes saved.']);
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
}
