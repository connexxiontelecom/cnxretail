@extends('layouts.master-layout')

@section('title')
    General Settings
@endsection
@section('page-name')
General Settings
@endsection
@section('page-description')
General Settings
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}"> General Settings</a>
</li>
@endsection

@section('page-heading')
General Settings
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">

        <form class="form-material" id="generalSettingsForm" action="{{route('tenant-general-settings')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    <ul id="validation-errors">
                    </ul>
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {!! session()->get('success') !!}
                        </div>

                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="card-header mb-4">
                        <h5>Business Detail</h5>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="business_name" id="business_name" value="{{old('business_name', Auth::user()->tenant->company_name)}}" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Business Name</label>
                         @error('business_name')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="email_address" id="email_address" value="{{Auth::user()->tenant->email}}" readonly class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label"> Email Address</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="phone_no" id="phone_no" value="{{old('phone_no', Auth::user()->tenant->phone)}}" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Phone No.</label>
                         @error('phone_no')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group form-primary form-static-label">
                    <input type="text" name="website" id="website" value="{{old('website', Auth::user()->tenant->website)}}" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Website</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="office_address" id="office_address" value="{{old('office_address', Auth::user()->tenant->address)}}" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Office Address</label>
                        @error('office_address')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="card-header mb-4">
                        <h5>Business Detail</h5>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="tagline" id="tagline" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Tagline</label>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="time" name="opening_hour" id="opening_hour" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Opening Hour</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="time" name="closing_hour" id="Closing Hour" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Closing Hour</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="file" name="logo" id="logo" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Logo</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="file" name="siteicon" id="siteicon" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Siteicon</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-md-12">
                    <div class="btn-group d-flex justify-content-center">
                        <a href="" class="btn btn-danger btn-mini"> <i class="ti-close mr-2"></i> Cancel</a>
                        <button type="submit"  class="btn btn-primary btn-mini save-contact"> <i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card-header mb-4">
                    <h5>Paystack Payment Integration</h5>
                </div>
                <p>To start receiving online payment, you'll have to first of all setup your <a href="https://www.paystack.com" target="_blank">Paystack</a> keys. Visit <a href="https://www.paystack.com" target="_blank">Paystack</a> to signup for FREE.</p>
                <p>Follow these steps to get your <code>live keys</code></p>
                <ul>
                    <li>Please don't forget to change account mode to <code>Live mode</code> to receive actual payment. You may have to complete some section to have your account verified.</li>
                    <li>After you've logged in, navigate to settings.</li>
                    <li>Then look out for <code>API Keys & Webhooks</code></li>
                    <li>Copy <code>Live Public Key</code> and <code>Live Secret Key</code> and paste it in the fields provided below accordingly.</li>
                    <li>Use <a href="javascript:void(0);">http://app.cnxretail.com/process/payment</a> as Live Webhook URL</li>

                </ul>
                @if (session()->has('success'))
                    <div class="alert alert-success mt-2">{!! session()->get('success') !!}</div>
                @endif
                <form action="{{route('api-settings')}}" method="post">
                    @csrf
                    <div class="form-group col-md-6">
                        <label for="">Live Public Key</label>
                        <input name="live_public_key" value="{{Auth::user()->tenant->public_key ?? ''}}" type="text" placeholder="Live Public Key" class="form-control">
                        @error('live_public_key')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Live Secret Key</label>
                        <input name="live_secret_key" value="{{Auth::user()->tenant->secret_key ?? ''}}" type="text" placeholder="Live Secret Key" class="form-control">
                        @error('live_secret_key')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Live Webhook URL (<small>Use: <i class="text-danger">http://app.cnxretail.com/process/payment</i></small>)</label>
                        <input name="live_webhook_url" value="http://app.cnxretail.com/process/payment" disabled type="text" placeholder="Live Webhook URL" class="form-control">
                        @error('live_webhook_url')
                            <i class="text-danger">{{$message}}</i>
                        @enderror

                    </div>
                    <div class="col-md-12 d-flex justify-content-center">
                        <div class="form-group">
                            <button class="btn btn-sm btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
    <script>
        $(document).ready(function(){
            $('.error-wrapper').hide();
            /* generalSettingsForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/tenant/general-settings',new FormData(generalSettingsForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Changes saved.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    location.reload();
                })
                .catch(error=>{
                        $('#validation-errors').html('');
                        $.each(error.response.data.errors, function(key, value){
                            Toastify({
                            text: value,
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "linear-gradient(to right, #FF0000, #FE0000)",
                            stopOnFocus: true,
                            onClick: function(){}
                        }).showToast();
                        $('#validation-errors').append("<li><i class='ti-hand-point-right text-danger mr-2'></i><small class='text-danger'>"+value+"</small></li>");
                    });
                });
                //let result = await response.json();
                //alert(result.message);
            }; */
            $(document).on('click', '.close-errors', function(e){
                e.preventDefault();
                $('.error-wrapper').hide();
            });
        });
    </script>
@endsection
