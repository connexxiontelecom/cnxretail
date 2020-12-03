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


            $settings = new Tenant;
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
}
