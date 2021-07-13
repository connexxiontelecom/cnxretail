<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Imprest;
use App\Models\Bank;

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

}
