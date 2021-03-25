<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request){
        $this->validate($request, [
            'email'=>'required',
            'password'=>'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if(!empty($user)){
            if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password], $request->remember)){
                if(\Carbon\Carbon::now() > Auth::user()->tenant->end){
                    $users = User::where('tenant_id', Auth::user()->tenant_id)->get();
                    foreach($users as $use){
                        $use->account_status = 0; //inactive
                        $use->save();
                    }
                    return redirect()->route('renew-subscription');
                }
                //check if profile is not updated
                $log = new ActivityLog;
                $log->subject = "Login success";
                $log->tenant_id = Auth::user()->tenant_id;
                $log->log = Auth::user()->full_name." logged in successfully.";
                $log->save();
                return redirect()->route('dashboard');
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
