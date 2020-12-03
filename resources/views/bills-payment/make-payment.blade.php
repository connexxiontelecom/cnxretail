@extends('layouts.master-layout')

@section('title')
   Make Payment
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-name')
Make Payment
@endsection
@section('page-description')
Make Payment
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Make Payment</a>
</li>
@endsection

@section('page-heading')
Make Payment
@endsection
@section('content')
<div>
    <div class="card">
        <form id="receiptForm" class="form-material">

            <div class="row invoice-contact">
                <div class="col-md-8">
                    <div class="invoice-box row">
                        <div class="col-sm-6">
                            <table class="table table-responsive invoice-table table-borderless">
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/logo.png" height="33" width="90" class="m-b-10" alt="">
                                            <p><strong style="font-weight: 700;">Company Name: </strong>{{ Auth::user()->tenant->company_name ?? ''}}</p>
                                            <p><strong style="font-weight: 700;">Address: </strong>{{ Auth::user()->tenant->address ?? ''}}</p>
                                            <p><strong style="font-weight: 700;">Email: </strong>{{ Auth::user()->tenant->email ?? ''}}</p>
                                            <p><strong style="font-weight: 700;">Phone: </strong>{{ Auth::user()->tenant->phone ?? ''}}</p>
                                            <p><strong style="font-weight: 700;">Website: </strong>{{ Auth::user()->tenant->website ?? ''}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-6 pt-4">
                            <h5 class="text-center">Bill Payment</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
            <div class="card-block">
                <div class="row invoive-info">
                    <div class="col-md-4 col-xs-12 invoice-client-info">

                        <div class="form-group">
                            <h6 class="text-uppercase">Vendor</h6>
                            <select name="vendor" id="vendor" value="{{old('vendor')}}" class="form-control js-example-basic-single select-product">
                                <option selected disabled>Select vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{$vendor->id}}" >{{$vendor->company_name ?? ''}}</option>
                                @endforeach
                            </select>
                            @error('vendor')
                                <i class="text-danger mt-2">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <h6 class="text-uppercase">Bank</h6>
                            <select name="bank" id="bank" value="{{old('bank')}}" class="form-control js-example-basic-single select-product">
                                <option selected disabled>Select bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{$bank->id}}" >{{$bank->bank ?? ''}}</option>
                                @endforeach
                            </select>
                            @error('bank')
                                <i class="text-danger mt-2">{{$message}}</i>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12 ">
                        <table class="table table-responsive invoice-table invoice-order table-borderless">
                            <tbody>
                                <div class="form-group col-md-8 col-sm-8">
                                    <div class="form-group form-primary form-static-label">
                                        <input type="date" name="payment_date" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Payment Date</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-8 col-sm-8">
                                    <div class="form-group form-primary form-static-label">
                                        <input type="text"  name="reference_no" class="form-control">
                                        <span class="form-bar"></span>
                                        <label class="float-label">Reference No.</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-8 col-sm-8">
                                    <div class="form-group form-primary form-static-label">
                                        <select id="payment_method" name="payment_method" class="form-control form-control-inverse fill">
                                            <option selected disabled>Select payment method</option>
                                            <option value="1">Cash</option>
                                            <option value="2">Bank Transfer</option>
                                            <option value="3">Cheque</option>
                                        </select>
                                    </div>
                                </div>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <h6 class="m-b-20 text-uppercase">Payment Number <span class="text-primary">#{{$pay_no ?? ''}}</span></h6>
                        <input type="hidden" name="pay_no" value="{{$pay_no ?? ''}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table  invoice-detail-table">
                                <thead>
                                    <tr class="thead-default">
                                        <th>Product/Service</th>
                                        <th>Bill Date</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody id="products">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label for="" class="label label-primary" data-toggle="modal" data-target="#bankModal" style="cursor: pointer;"><i class="ti-plus mr-2"></i> Add New Bank</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-responsive invoice-table invoice-total">
                            <tbody class="float-left">

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                                        <ul id="validation-errors">
                                        </ul>
                                    </div>
                                </div>
                            </tbody>
                            <tbody class="float-right">
                                <tr class="text-info">
                                    <td>
                                        <hr>
                                        <strong class="text-primary">Total Due:</strong>
                                    </td>
                                    <td>
                                        <hr>
                                        <strong class="text-primary"><span class="totalDue"></span></strong>
                                    </td>
                                </tr>
                                <tr class="text-info">
                                    <td>
                                        <hr>
                                        <strong class="text-primary">Total Payment:</strong>
                                    </td>
                                    <td>
                                        <hr>
                                        <strong class="text-primary"><span class="totalPayment"></span></strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-sm-12 invoice-btn-group text-center">
                    <div class="btn-group d-flex justify-content-center">
                        <a href="{{url()->previous()}}" class="btn btn-secondary btn-print-invoice m-b-10 btn-mini waves-effect waves-light m-r-20"><i class="ti-close mr-2"></i>Cancel</a>
                        <button type="submit" class="btn btn-primary btn-mini waves-effect m-b-10 waves-light"><i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Add New Bank</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="newBankForm" class="form-material">
              <hr>
                <div class="form-group form-primary form-static-label">
                    <input type="text" name="account_name" id="account_name" class="form-control">
                    <span class="form-bar"></span>
                    <label class="float-label">Account Name</label>
                </div>
                <div class="form-group form-primary form-static-label">
                    <input type="text" name="account_number" id="account_number" class="form-control">
                    <span class="form-bar"></span>
                    <label class="float-label">Account Number</label>
                </div>
                <div class="form-group form-primary form-static-label">
                    <input type="text" name="bank_name" id="bank_name" class="form-control">
                    <span class="form-bar"></span>
                    <label class="float-label">Bank Name</label>
                </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="btn-group d-flex justify-content-center">
                        <button class="btn btn-danger btn-mini" data-dismiss="modal"><i class="ti-close mr-2"></i> Close</button>
                        <button class="btn btn-primary btn-mini" type="submit" id="confirmSelection"><i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('extra-scripts')
<script type="text/javascript" src="\assets\js\jquery.slimscroll.min.js"></script>
<script src="/assets/js/datatable.min.js"></script>
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        //$('.totalDue').text($('#totalAmount').val().toLocaleString());
        $('.js-example-basic-single').select2({
            placeholder: "Select product/service"
        });
        $('.scrollList').slimscroll({
            height: '430px',
        });
        var grand_total = 0;

        $(document).on('change', '#vendor', function(e){
            e.preventDefault();
             axios.post('/get-vendor',{vendor:$(this).val()})
            .then(response=>{
                $('#products').html(response.data);
            });
        });
    receiptForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/invoice/receive-payment',new FormData(receiptForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Receipt issued.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    window.location.replace(response.data.route);
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
            };
            newBankForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/tenant/bank',new FormData(newBankForm))
                .then(response=>{
                    Toastify({
                        text: "Success! New bank registered.",
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
            };

   });
   function setTotal(){
        var sum = 0;
        $(".total_amount").each(function(){
						sum += +$(this).val().replace(/,/g, '');
            $(".total").text(sum.toLocaleString());
        });
		}
</script>
@endsection
