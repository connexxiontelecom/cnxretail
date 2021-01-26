<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class serviceController extends Controller
{
    //


    public function addService(Request $request){
        $this->validate($request,[
            'service_product_name'=>'required'
        ]);
        $service = new Service;
        $service->service = $request->service_product_name;
        $service->added_by = $request->addedby;
        $service->tenant_id = $request->tenant_id;
        $service->save();

        return response()->json(['response'=>'success'], 201);

    }


}
