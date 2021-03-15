<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Membership;
use App\Models\InvoiceMaster;
use Carbon\Carbon;
use Paystack;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'company_name' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_no'=>'required',
            'address'=>'required',
            'first_name'=>'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
/*     protected function create(array $data)
    {
        return User::create([
            'company_name' => $data['company_name'],
            'email_address' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    } */

    public function register(Request $request){
        $this->validate($request,[
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_no'=>'required',
            'address'=>'required',
            'full_name'=>'required',
            'nature_of_business'=>'required'
        ]);
        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            session()->flash("error", "<strong>Ooops!</strong> The paystack token has expired. Please refresh the page and try again." );
            return back();
        }
    }
    public function showStartTrialForm(){
        return view('auth.start-trial');
    }


    public function processPayment(Request $request){

        if(!Auth::check() ){
            $tenant_id = null;
            $current = Carbon::now();
            $latestTenant = Tenant::orderBy('id', 'DESC')->first();
            if(!empty($latestTenant)){
                $tenant_id = $latestTenant->tenant_id + 1;
            }else{
                $tenant_id = rand(10,999);
            }
            $paymentDetails = Paystack::getPaymentData();
            $metadata = json_decode($paymentDetails['data'] ['metadata'][0], true);
            #Register new tenant
            $tenant = new Tenant;
            $tenant->tenant_id = $tenant_id;
            $tenant->company_name = $metadata['company_name'];
            $tenant->email = $metadata['email'];
            $tenant->phone = $metadata['phone_no'];
            $tenant->address = $metadata['address'];
            $tenant->nature_of_business = $metadata['nature_of_business'];
            $tenant->start = $current;
            $tenant->end = $current->addDays(30);
            $tenant->slug = substr(time(),30,40);
            $tenant->save();

            #user
            $user = new User;
            $user->full_name = $metadata['full_name'];
            $user->password = bcrypt($metadata['password']);
            $user->email = $metadata['email'];
            $user->tenant_id = $tenant_id;
            $user->slug = substr(time(),29,40);
            $user->address = $metadata['address'] ?? '';
            $user->account_status = 1;
            $user->mobile_no = $metadata['phone_no'];
            $user->gender = 1;
            $user->save();
            #Register subscription
            $key = "key_".substr(sha1(time()),21,40 );
             $member = new Membership;
            $member->tenant_id = $tenant_id;
            $member->plan_id = 1;//$metadata['plan'];
            $member->sub_key = $key;
            $member->status = 1; //active;
            $member->start_date = $current;
            $member->end_date = $current->addDays(30);
            $member->amount = 5500;
            $member->save();
            session()->flash("success", "<strong>Success!</strong> Registration done. Proceed to login.");
            return redirect()->route('login');
        }else{
            //
        }
    }


    public function startTrial(Request $request){
        $this->validate($request,[
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_no'=>'required',
            'address'=>'required',
            'full_name'=>'required',
            'nature_of_business'=>'required'
        ]);
        $tenant_id = null;
        $current = Carbon::now();
        $latestTenant = Tenant::orderBy('id', 'DESC')->first();
        if(!empty($latestTenant)){
            $tenant_id = $latestTenant->tenant_id + 1;
        }else{
            $tenant_id = rand(10,999);
        }
        #Register new tenant
        $tenant = new Tenant;
        $tenant->tenant_id = $tenant_id;
        $tenant->company_name = $request->company_name;
        $tenant->email = $request->email;
        $tenant->phone = $request->phone_no;
        $tenant->address = $request->address;
        $tenant->nature_of_business = $request->nature_of_business;
        $tenant->start = $current;
        $tenant->end = $current->addDays(30);
        $tenant->slug = substr(time(),30,40);
        $tenant->save();

        #user
        $user = new User;
        $user->full_name = $request->full_name;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->tenant_id = $tenant_id;
        $user->slug = substr(time(),29,40);
        $user->address = $request->address ?? '';
        $user->account_status = 1;
        $user->mobile_no = $request->phone_no;
        $user->gender = 1;
        $user->save();
        #Register subscription
        $key = "key_".substr(sha1(time()),21,40 );
         $member = new Membership;
        $member->tenant_id = $tenant_id;
        $member->plan_id = 1;//$metadata['plan'];
        $member->sub_key = $key;
        $member->status = 1; //active;
        $member->start_date = $current;
        $member->end_date = $current->addDays(30);
        $member->amount = 5500;
        $member->save();
        session()->flash("success", "<strong>Success!</strong> Registration done. Proceed to login.");
        return redirect()->route('login');
    }


    public function payInvoiceOnline($slug){
        $total = 0;
        $invoice = InvoiceMaster::where('slug', $slug)->first();
        if(!empty($invoice)){
            $tenant = Tenant::where('tenant_id', $invoice->tenant_id)->first();
            return view('sales-invoice.pay-invoice-online',['invoice'=>$invoice, 'total'=>$total, 'tenant'=>$tenant]);
        }
    }
}
