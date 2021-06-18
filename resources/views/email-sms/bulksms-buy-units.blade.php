@extends('layouts.master-layout')

@section('title')
    Buy Units
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Buy Units
@endsection
@section('page-description')
Buy Units
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Buy Units</a>
</li>
@endsection

@section('page-heading')
Buy Units
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <div class="row mb-3">
            <div class="col-md-12 col-sm-12 ">
                <a  href="{{route('bulksms-balance')}}" class="btn btn-mini btn-primary"><i class="ti-plus mr-2"></i>Balance</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <h5 class="sub-title">Calculate Cost</h5>
                <form class="form-material" id="buyUnitForm">
                    <div class="form-group form-primary form-static-label">
                        <input type="number" placeholder="SMS Quantity" value="50" name="sms_quantity" id="sms_quantity" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">SMS Quantity</label>
                    </div>
                    <div class="form-group">
                        <label for="">Bundle Name:</label>
                        <select name="plan_name" id="plan_name" class="form-control">
                            <option selected disabled>--- Select plan ---</option>
                            @foreach($plans as $plan)
                                <option value="{{$plan->id}}" data-cost="{{$plan->unit_cost ?? 0}}">{{$plan->plan_name ?? ''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for=""><strong style="font-weight: 700;">Total: </strong> <span class="unitCost"></span></label>
                        <input type="text" hidden step="0.01" name="totalAmount" id="totalAmount">
                        <input type="hidden"   id="transaction" name="transaction" value="">
                    </div>
                    <hr>
                    <div class="btn-group d-flex justify-content-center">
                        <button class="btn btn-primary btn-mini" type="button" onclick="payWithPaystack()"><i class="ti-wallet mr-2"></i>Pay & Continue</button>
                    </div>
            </div>
            <div class="col-md-8 col-sm-8">
                <h5 class="sub-title">Price List</h5>
                <div class="dt-responsive table-responsive">
                    <table  class="table table-striped ">
                        <thead>
                            <tr>
                                <th>Bundle</th>
                                <th>Min. Qty.</th>
                                <th>Unit Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                                <tr>
                                    <td>{{$plan->plan_name ?? ''}}</td>
                                    <td>{{number_format($plan->min_quantity ?? 0)}}</td>
                                    <td>{{$plan->unit_cost ?? 0}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-md-12 col-sm-12">
                <h5 class="sub-title">Invoice Summary</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SMS Cost(NGN)</th>
                            <th>VAT(NGN)</th>
                            <th>Total(NGN)</th>
                        </tr>
                        <tr>
                            <td><span class="sub-total"></span></td>
                            <td><span class="">Inclusive</span></td>
                            <td><span class="total"></span></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div> -->
    </form>
    </div>
</div>
@endsection

@section('extra-scripts')
<script src="/assets/js/datatable.min.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();
        var cost = 0;
        var unit_cost = 0;
        var bundle = null;
        var quantity = 1;
        $(document).on('change', '#plan_name', function(e){
            e.preventDefault();
            var price = $(this).find(':selected').data('cost');
            var val = $('#sms_quantity').val() ?? 1;
            var tt = val * price;
            $('#totalAmount').val(val * price);
            $('.unitCost').text(tt.toFixed(2));


        });
        $(document).on('blur', '#sms_quantity', function(e){
                e.preventDefault();
                //$('.characters').text("Characters: "+charCount);
                var amt = +$(this).val() * $('#plan_name').find(':selected').data('cost');
                $('#totalAmount').val(+$(this).val() * $('#plan_name').find(':selected').data('cost'));
                $('.unitCost').text(amt.toFixed(2));

            });
        $(document).on('click', '.balance', function(e){
            e.preventDefault();
            axios.post('/sms/balance')
            .then(response=>{
               // console.log(response.data);
            })
            .catch(error=>{
                //console.log(error.response.errors);
            });
        });
    });

    function payWithPaystack(){
    var handler = PaystackPop.setup({
      key: 'pk_test_ec726436a72f60a31b99b173478a569bddd105bc',
      email: '{{Auth::user()->email}}',
      amount: $('#totalAmount').val() * 100,
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
                 axios.post('/bulksms/transaction',new FormData(buyUnitForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Account credited.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    //window.location.replace(response.data.route);
                    window.location = response.data.route;
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
