<?php

namespace App\Http\Controllers\Auth;
use Spatie\Newsletter\NewsletterFacade as Newsletter;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Membership;
use App\Models\InvoiceMaster;
use Carbon\Carbon;
use Paystack;
use Auth;

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

    public function showRegistrationForm($ref_link = null){
        return view('auth.register', ['link'=>$ref_link]);
    }

    public function register(Request $request){
        $this->validate($request,[
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_no'=>'required',
            'address'=>'required',
            'full_name'=>'required',
            'nature_of_business'=>'required',
            'plan'=>'required'
        ]);

        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            session()->flash("error", "<strong>Ooops!</strong> The token has expired. Please refresh the page and try again." );
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
            $amount = 0;
            $latestTenant = Tenant::orderBy('id', 'DESC')->first();
            if(!empty($latestTenant)){
                $tenant_id = $latestTenant->tenant_id + 1;
            }else{
                $tenant_id = rand(10,999);
            }
            $paymentDetails = Paystack::getPaymentData();
            $metadata = json_decode($paymentDetails['data'] ['metadata'][0], true);
            $purpose = $metadata['purpose'];
            if($purpose != 'training'){
                #Register new tenant
                $tenant = new Tenant;
                $tenant->tenant_id = $tenant_id;
                $tenant->company_name = $metadata['company_name'];
                $tenant->email = $metadata['email'];
                $tenant->phone = $metadata['phone_no'];
                $tenant->address = $metadata['address'];
                $tenant->nature_of_business = $metadata['nature_of_business'];
                $tenant->plan_id = $metadata['plan'];
                $tenant->start = now();
                //$tenant->end = $current->addDays(30);
                if($metadata['plan'] == 1){
                    $tenant->end = $current->addDays(30);
                    $amount = 7500;
                }else if($metadata['plan'] == 2){
                    $tenant->end = $current->addDays(30*6);
                    $amount = 6500*6;
                }else if($metadata['plan'] == 3){
                    $tenant->end = $current->addDays(365);
                    $amount = 5500*12;
                }
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
                $member->plan_id = $metadata['plan'];
                $member->sub_key = $key;
                $member->status = 1; //active;
                $member->start_date = now();
                if($metadata['plan'] == 1){
                    $member->end_date = $current->addDays(30);
                }else if($metadata['plan'] == 2){
                    $member->end_date = $current->addDays(30*6);
                }else if($metadata['plan'] == 3){
                    $member->end_date = $current->addDays(365);
                }
                $member->amount = $amount;
                $member->save();
                #Send mail
                if ( ! Newsletter::isSubscribed($metadata['email']) ) {
                    Newsletter::subscribe($metadata['email'], ['FNAME'=>$metadata['full_name']]);
                }
                #API call to AMP
                if(!empty($metadata['link'])){
                    $data = [
                        'product_id'=>16,
                        'referral_code' => $metadata['link'], //referral ID
                        'amount'=> $amount,
                        'company_name'=> $metadata['company_name'],
                        'contact_email'=> $metadata['email'],
                        'month'=> date('m'),
                        'year'=> date('Y')
                    ];
                    $url = "https://amp-api.connexxiontelecom.com/public/new_product_sale";
                    $response = Http::post($url, $data);

                }
                session()->flash("success", "<strong>Success!</strong> Registration done. Proceed to login.");
            }else{
                #Register new tenant
                $tenant = new Tenant;
                $tenant->tenant_id = $tenant_id;
                $tenant->company_name = $metadata['business_name'];
                $tenant->email = $metadata['email'];
                //$tenant->phone = $metadata['phone_no'];
                $tenant->plan_id = 1;
                $tenant->start = now();
                $tenant->end = $current->addDays(60);
                $amount = 15000;
                $tenant->slug = substr(time(),30,40);
                $tenant->save();

                #user
                $user = new User;
                $user->full_name = $metadata['full_name'];
                $user->password = bcrypt($metadata['password']);
                $user->email = $metadata['email'];
                $user->tenant_id = $tenant_id;
                $user->slug = substr(time(),29,40);
                //$user->address = $metadata['address'] ?? '';
                $user->account_status = 1;
                //$user->mobile_no = $metadata['phone_no'];
                $user->gender = 1;
                $user->save();
                #Register subscription
                $key = "key_".substr(sha1(time()),21,40 );
                $member = new Membership;
                $member->tenant_id = $tenant_id;
                $member->plan_id = 1; //$metadata['plan'];
                $member->sub_key = $key;
                $member->status = 1; //active;
                $member->start_date = now();
                $member->end_date = $current->addDays(60);
                $member->amount = $amount;
                $member->save();
            }

            return Redirect::to('https://cnxretail.com/confirmation');
        }else{
            //
        }
    }

    public function thankYou(){
        return view('auth.thank-you');
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
        $tenant->start = now();
        $tenant->end = $current->addDays(14);
        $tenant->slug = substr(time(),30,40);
        $tenant->plan_id = 1; //free
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
        $member->end_date = $current->addDays(14);
        $member->amount = 0;
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

    public function showTrainingForm(){
        return view('auth.training');
    }


    public function validateTrainingRegistration(Request $request){

        $this->validate($request,[
            'business_name'=>'required',
            'full_name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|confirmed'
        ],[
            'business_name.required'=>'Whoops! Enter your business name',
            'full_name.required'=>'Enter your full name ',
            'email.required'=>'Enter a valid email address',
            'password.required'=>'Choose a password',
            'password.confirmed'=>'Chosen password mis-match with re-type password'
        ]);
        $metadata = [
            'purpose'=>'training',
            'business_name'=>$request->business_name,
            'full_name'=>$request->full_name,
            'email'=>$request->email,
            'password'=>$request->password
        ];

        $arr[0] = json_encode($metadata);
        $request->request->add(['metadata'=>$arr, 'amount'=>1500000]);
        try{
            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            return dd($e);
            session()->flash("error", "<strong>Whoops!</strong> Token expired. Refresh the page and try again.");
            return back();
        }
    }
}
