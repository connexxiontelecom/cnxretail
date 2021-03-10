<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use Auth;

class LoginController extends Controller
{
    //

    public function showLoginForm(){

        return view('admin.auth.login');
    }

    public function login(Request $request){
        $this->validate($request, [
            'email'=>'required',
            'password'=>'required'
        ]);
        $user = AdminUser::where('email', $request->email)->first();
        if(!empty($user)){
            if(Auth::guard('admin')->attempt(['email'=>$request->email, 'password'=>$request->password, 'account_status'=>1], $request->remember)){
                return redirect()->route('admin.dashboard');

            }else{
                session()->flash("error", "<strong>Error! </strong> Wrong or invalid login credentials. Try again.");
                return back();
            }
        }else{
            session()->flash("error", "<strong>Ooops!</strong> There's no existing account with this details.");
            return back();
        }

    }
}
