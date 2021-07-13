@extends('layouts.master-layout')

@section('title')
   Receive payment from {{$invoice->contact->company_name ?? ''}}
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-name')
Receive payment from {{$invoice->contact->company_name ?? ''}}
@endsection
@section('page-description')
Receive payment from {{$invoice->contact->company_name ?? ''}}
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">{{$invoice->contact->company_name ?? ''}}</a>
</li>
@endsection

@section('page-heading')
Receive payment
@endsection
@section('content')
<div>
    <div class="card">
        <form id="receiptForm" class="form-material">

            <div class="row invoice-contact">
                <div class="col-md-8">
                    <div class="invoice-box row">
                        <div class="col-sm-12">
                            <table class="table table-responsive invoice-table table-borderless">
                                <tbody>
                                    <tr>
                                        <td>
                                            <img src="/assets/uploads/cnxdrive/{{Auth::user()->tenant->logo ?? 'logo.png'}}" height="33" width="90" class="m-b-10" alt="">
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
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
            <div class="card-block">
                <div class="row invoive-info">
                    <div class="col-md-4 col-xs-12 invoice-client-info">
                        <h6 class="text-uppercase">Client Information :</h6>
                        <h6 class="m-0">{{$invoice->contact->company_name ?? ''}}</h6>
                        <p class="m-0 m-t-10">{{$invoice->contact->address ?? ''}}</p>
                        <p class="m-0">{{$invoice->contact->company_phone ?? ''}}</p>
                        <p class="m-0">{{$invoice->contact->email ?? ''}}</p>
                        <p>{{$invoice->contact->website ?? ''}}</p>
                        <input type="hidden" value="{{$invoice->contact_id}}" name="contact">
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
                        <h6 class="m-b-20 text-uppercase">Receipt Number <span class="text-primary">#{{$invoice->invoice_no ?? ''}}</span></h6>
                        <input type="hidden" name="invoice_no" value="{{$invoice->invoice_no ?? ''}}">
                        <div class="form-group">
                            <label for="">Bank</label>
                            <select name="bank" id="bank" class="form-control">
                                <option disabled selected>Select bank</option>
                                @foreach ($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->bank ?? ''}} - {{$bank->account_no ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table  invoice-detail-table">
                                <thead>
                                    <tr class="thead-default">
                                        <th>Product/Service</th>
                                        <th>Due Date</th>
                                        <th>Total</th>
                                        <th>Balance</th>
                                        <th>Payment</th>
                                    </tr>
                                </thead>
                                <tbody id="products">
                                    @foreach ($invoices as $item)
                                            <tr class="item">
                                                <td>
                                                    <p><a href="{{route('view-invoice', $item->slug)}}" target="_blank">Receiving payment on invoice #{{$item->invoice_no ?? ''}}</a></p>
                                                </td>
                                                <td>
                                                    <p>{{date('d F, Y h:ia', strtotime($item->due_date)) ?? ''}}</p>
                                                </td>
                                                <td>
                                                    <p>{{$item->getCurrency->symbol ?? 'N'}}{{number_format($item->total/$item->exchange_rate,2) ?? ''}}</p>
                                                </td>
                                                <td>
                                                <p>{{number_format($item->total/$item->exchange_rate - $item->paid_amount/$item->exchange_rate,2)}}</p>
                                                <input type="hidden" name="totalAmount" id="totalAmount" value="{{$total += $item->total/$item->exchange_rate - $item->paid_amount/$item->exchange_rate}}">
                                                <input type="hidden" name="exchange_rate[]" value="{{$item->exchange_rate}}">
                                                <input type="hidden" name="currency[]" value="{{$item->currency_id}}">
                                                <input type="hidden" name="invoice[]" value="{{$item->id}}">
                                                </td>
                                                <td>
                                                    <div class="form-group form-primary form-static-label">
                                                        <input type="number" step="0.01" name="payment[]" data-balance="{{$item->total/$item->exchange_rate - $item->paid_amount/$item->exchange_rate}}" class="form-control total_amount">
                                                        <span class="form-bar"></span>
                                                        <label class="float-label">Payment</label>
                                                    </div>
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                                        <strong class="text-primary">Total Due: </strong>
                                    </td>
                                    <td>
                                        <hr>
                                        <strong class="text-primary">{{$item->getCurrency->symbol ?? 'N'}}<span class="">{{$invoices->sum('total')/$invoice->exchange_rate - $invoices->sum('paid_amount')/$invoice->exchange_rate}}</span></strong>
                                    </td>
                                </tr>
                                <tr class="text-info">
                                    <td>
                                        <hr>
                                        <strong class="text-primary">Total Payment:</strong>
                                    </td>
                                    <td>
                                        <hr>
                                        <strong class="text-primary">{{$item->getCurrency->symbol ?? 'N'}}<span class="totalPayment">0.00</span></strong>
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
                        <button type="submit" id="submitReceiptBtn" class="btn btn-primary btn-mini waves-effect m-b-10 waves-light"><i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('extra-scripts')
<script type="text/javascript" src="\assets\js\jquery.slimscroll.min.js"></script>
<script src="/assets/js/datatable.min.js"></script>
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('.totalDue').text(Number($('#totalAmount').val()).toLocaleString());
        $('.js-example-basic-single').select2({
            placeholder: "Select product/service"
        });
        $('.scrollList').slimscroll({
            height: '430px',
        });
        var grand_total = 0;
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

   });
   $(document).on("change", ".total_amount", function() {
       if($(this).val() > $(this).data('balance') ){
           Toastify({
               text: 'Payment amount cannot be more than balance.',
               duration: 3000,
               newWindow: true,
               close: true,
               gravity: "top",
               position: 'right',
               backgroundColor: "linear-gradient(to right, #FF0000, #FE0000)",
               stopOnFocus: true,
               onClick: function(){}
           }).showToast();
           $('#submitReceiptBtn').attr('disabled', true);
           $(this).val(0);
           return false;
       }else{
           $('#submitReceiptBtn').attr('disabled', false);
           setTotal();
       }
    });
   function setTotal(){
        var sum = 0;
        $(".total_amount").each(function(){
						sum += +$(this).val().replace(/,/g, '');
            $(".totalPayment").text(sum.toLocaleString());
        });
		}
</script>
@endsection
