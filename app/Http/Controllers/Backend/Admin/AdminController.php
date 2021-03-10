<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminTenantConversationLog;
use App\Models\User;
use App\Models\AdminUser;
use App\Models\Tenant;
use App\Models\Membership;
use App\Models\ActivityLog;
use Auth;

class AdminController extends Controller
{
    //
    public $admin, $user, $tenant;

    public function __construct(){
        $this->middleware('auth:admin');
        $this->admin = new AdminUser;
        $this->user = new User;
        $this->tenant = new Tenant;

    }


    public function dashboard(){
        $data = [
            'tenants'=>$this->tenant->orderBy('id', 'DESC')->get(),
            'users'=>$this->user->orderBy('id', 'DESC')->get(),
            'admins'=>$this->admin->orderBy('id', 'DESC')->get()
        ];
        return view('admin.pages.dashboard',$data);
    }


    public function getAllTenants(){
        $tenants = Tenant::orderBy('id', 'DESC')->get();
        return view('admin.pages.tenants', ['tenants'=>$tenants]);
    }


    public function getTenant($slug){
        $tenant = Tenant::where('slug', $slug)->first();

        if(!empty($tenant)){
            $subscriptions = Membership::where('tenant_id', $tenant->tenant_id)->orderBy('id', 'DESC')->get();
            return view('admin.pages.view-tenant', ['contact'=>$tenant,'subscriptions'=>$subscriptions]);
        }else{
            return back();
        }
    }


    public function messageTenant(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'message'=>'required'
        ]);
        $message = new AdminTenantConversationLog;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->admin_user = Auth::user()->id;
        $message->tenant_id = $request->tenant;
        $message->save();
        session()->flash("success", "<strong>Success!</strong> Message sent.");
        return back();
    }


    public function addNewAdminUser(Request $request){
        return view('admin.pages.add-new-admin');
    }


    public function storeAdminUser(Request $request){
        $this->validate($request,[
            'full_name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed'
        ]);
        $admin = new AdminUser;
        $admin->full_name = $request->full_name ?? '';
        $admin->email = $request->email ?? '';
        $admin->password = bcrypt($request->password);
        $admin->slug = substr(time(),28,40);
        $admin->save();
        session()->flash("success","<strong>Success!</strong> New admin user registered.");
        return back();
    }


    public function getAllAdminUsers(){
        $users = AdminUser::orderBy('id', 'DESC')->get();
        return view('admin.pages.all-admin-users', ['users'=>$users]);
    }

    public function getActivityLogs(){
        $activities = ActivityLog::orderBy('id', 'DESC')->get();

        return view('admin.pages.activity-log', ['activities'=>$activities]);
    }
}
