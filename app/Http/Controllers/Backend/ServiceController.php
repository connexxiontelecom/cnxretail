<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Auth;
use DB;

class ServiceController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function services(){
        $services = Service::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('service.services',['services'=>$services]);
    }

    public function addNewService(Request $request){
        $this->validate($request,[
            'service_product_name'=>'required'
        ]);
        $service = new Service;
        $service->service = $request->service_product_name;
        $service->added_by = Auth::user()->id;
        $service->tenant_id = Auth::user()->tenant_id;
        $service->save();
        return response()->json(['message'=>'Success! New service registered.'], 201);
    }
}
