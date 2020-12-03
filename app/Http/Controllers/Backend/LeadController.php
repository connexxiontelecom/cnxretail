<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Contact;
use App\Models\User;
use Auth;
class LeadController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $leads = Lead::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('lead.index', ['leads'=>$leads]);
    }


    public function viewLead($slug){
        $contact = Contact::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
        $lead = Lead::where('tenant_id', Auth::user()->tenant_id)->first();
        if(!empty($lead) ){
            return view('lead.view-lead', ['lead'=>$lead, 'users'=>$users]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }

    public function scoreLead(Request $request){
        $this->validate($request,[
            'prospectingContact'=>'required',
            'score'=>'required'
        ]);
        $score = Lead::where('tenant_id', Auth::user()->tenant_id)->where('contact_id', $request->prospectingContact)->first();
        if(!empty($score)){
            $score->score = $request->score;
            $score->save();
        }
    }

    public function assignLead(Request $request){
        $this->validate($request,[
            'contactId'=>'required',
            'assign_user'=>'required'
        ]);
        $user = Lead::where('tenant_id', Auth::user()->tenant_id)->where('contact_id', $request->contactId)->first();
        if(!empty($user)){
            $user->assigned_to = $request->assign_user;
            $user->save();
        }
    }
}
