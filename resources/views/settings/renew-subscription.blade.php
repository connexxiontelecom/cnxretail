@extends('layouts.master-layout')

@section('title')
    Renew Subscription
@endsection
@section('page-name')
Renew Subscription
@endsection
@section('page-description')
Renew Subscription
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}"> Renew Subscription</a>
</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        @if (Auth::user()->account_status != 1)
            <div class="alert alert-warning background-warning">
                <p>Ooops! You do not have an active subscription. Select one of our plans below.</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h5>Our Pricing</h5>
                <span>Here are our various prices. Choose the one that suites you.</span>
            </div>
            <div class="card-block list-tag">
                <div class="row">
                    <div class="col-sm-12 col-xl-3">
                        <h4 class="sub-title">Trial @if(Auth::user()->tenant->plan_id == 1 )<label for="" class="label label-danger">Current</label> @endif</h4>
                        <ul>
                            <li>
                                <i class="icofont icofont-bubble-right"></i> No Cost
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-12 col-xl-3">
                        <h4 class="sub-title">Monthly  @if(Auth::user()->tenant->plan_id == 2 )<label for="" class="label label-danger">Current</label> @endif</h4>
                        <ul>
                            <li>
                                <i class="icofont icofont-double-right text-success"></i> ₦7,500/month
                            </li>
                        </ul>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-sm text-center" onclick="payWithPaystack(7500, 2, {{Auth::user()->tenant_id}})">Pay ₦7,500 to subscribe</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-3">
                        <h4 class="sub-title">Bi-annual @if(Auth::user()->tenant->plan_id == 3 )<label for="" class="label label-danger">Current</label> @endif</h4>
                        <ul>
                            <li>
                                <i class="icofont icofont-hand-right text-info"></i> ₦6,500/month
                            </li>
                        </ul>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-sm text-center" onclick="payWithPaystack(39000, 3, {{Auth::user()->tenant_id}})">Pay ₦39,000 to subscribe</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-xl-3">
                        <h4 class="sub-title">Annual @if(Auth::user()->tenant->plan_id == 4 )<label for="" class="label label-danger">Current</label> @endif</h4>
                        <ul>
                            <li>
                                <i class="icofont icofont-stylish-right text-danger"></i> ₦5,500/month
                            </li>
                        </ul>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <button type="button" class="btn btn-primary btn-sm text-center" onclick="payWithPaystack(66000, 4, {{Auth::user()->tenant_id}})">Pay ₦66,000 to subscribe</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
<script src="/assets/js/toastify.min.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    var amount = 0;
    $(document).ready(function(){

    });

function payWithPaystack(amt,plan,tenant){

    var handler = PaystackPop.setup({
      key: 'pk_test_ec726436a72f60a31b99b173478a569bddd105bc',
      email: '{{Auth::user()->email}}',
      amount: parseFloat(amt) * 100,
      currency: "NGN",
      ref: ''+Math.floor((Math.random() * 1000000000) + 1),
      metadata: {
         custom_fields: [
            {
                display_name: "{{Auth::user()->full_name}}",
                variable_name: "23480384848",
                value: "+2348012345678"
            }
         ]
      },
      callback: function(response){
          $('#transaction').val(response.trans);
               axios.post('/renew-subscription',{tenant:tenant,plan:plan,amount:amt})
                .then(response=>{
                    Toastify({
                        text: "Success! Subscription renewed.",
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
            //};
         // }
          //alert('success. transaction ref is ' + response.reference);
      },
      onClose: function(){
          alert('Are you sure you want to terminate this transaction?');
      }
    });
    handler.openIframe();
  }
</script>
@endsection
