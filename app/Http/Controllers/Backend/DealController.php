<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deal;
use Auth;

class DealController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $deals = Deal::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('deal.index', ['deals'=>$deals]);
    }
}
