@extends('layouts.master-layout')

@section('title')
    New Bill
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-name')
{{$contact->company_name ?? ''}}
@endsection
@section('page-description')
    New Bill
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">New Bill</a>
</li>
@endsection

@section('page-heading')
    New Bill
@endsection
@section('content')
<div>
    <div class="card">
        <form id="billForm">

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
                            <h5 class="text-center">Vendor Bill</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
            <div class="card-block">
                <div class="row invoive-info">
                    <div class="col-md-4 col-xs-12 invoice-client-info">
                        <h6 class="text-uppercase">Vendor Information :</h6>
                       <div class="form-group">
                        <select name="vendor" id="vendor" value="{{old('contact')}}" class="js-example-basic-single select-product">
                            <option selected disabled>Select vendor</option>
                            @foreach($contacts as $contact)
                                <option value="{{$contact->id}}" >{{$contact->company_name  ?? ''}}</option>
                            @endforeach
                        </select>
                       </div>
                    </div>
                    <div class="col-md-4 col-xs-12 ">

                        <h6 class="text-uppercase">Order Information :</h6>
                        <table class="table table-responsive invoice-table invoice-order table-borderless">
                            <tbody>
                                <div class="form-group col-md-8 col-sm-8">
                                    <label for="">Date</label>
                                    <input type="date" name="bill_date" id="bill_date" class="form-control">
                                </div>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <h6 class="m-b-20 text-uppercase">Bill Number <span class="text-primary">#{{$billNo}}</span></h6>
                        <input type="hidden" name="bill_no" value="{{$billNo}}">
                        <h6 class="text-uppercase text-primary">Total Due :
                            <span class="total"></span>
                        </h6>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table  invoice-detail-table">
                                <thead>
                                    <tr class="thead-default">
                                        <th>Service/Product</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Total</th>
                                        <th class="text-danger">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="products">
                                    <tr class="item">
                                        <td>
                                            <div class="form-group">
                                                <select name="service[]" value="{{old('service[]')}}" class="js-example-basic-single select-product">
                                                    <option selected disabled>Select service/product</option>
                                                    @foreach($services as $service)
                                                        <option value="{{$service->id}}" >{{$service->service ?? ''}}</option>
                                                    @endforeach
                                                </select>
                                                @error('service')
                                                    <i class="text-danger mt-2">{{$message}}</i>
                                                @enderror
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" placeholder="Quantity" name="quantity[]"  class="form-control">
                                            @error('quantity')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" placeholder="Unit Cost" step="0.01"  class="form-control" name="unit_cost[]">
                                            @error('unit_cost')
                                                <i class="text-danger mt-2">{{$message}}</i>
                                            @enderror
                                        </td>
                                        <td><input type="text" class="form-control total_amount" name="total[]" readonly style="width: 120px;"></td>
                                        <td>
                                            <i class="ti-trash text-danger remove-line" style="cursor: pointer;"></i>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <button class="btn btn-mini btn-primary add-line"> <i class="ti-plus mr-2"></i> Add Line</button>
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


                                <input type="number" name="mainTotal" hidden id="mainTotal">
                                <tr>
                                    <th>VAT(%):</th>
                                    <td>
                                        <input type="number" step="0.01" name="vat" id="vat" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th>VAT Amount :</th>
                                    <td><span class="vat_amount"></span></td>
                                </tr>
                                <tr>
                                    <th>Sub Total :</th>
                                    <td><span class="sub_total"></span></td>
                                </tr>
                                <tr>
                                    <th>Currency:</th>
                                    <td>
                                        <select name="currency" id="currency" value="{{old('currency')}}" class="js-example-basic-single">
                                            <option value="{{Auth::user()->tenant->currency->id}}" selected>{{Auth::user()->tenant->currency->name ?? ''}} ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</option>
                                            @foreach($currencies->where('id', '!=', Auth::user()->tenant->currency->id) as $currency)
                                                    <option value="{{$currency->id}}" data-abbr="{{$currency->abbr}}">{{$currency->name ?? ''}} ({{$currency->symbol ?? ''}})</option>
                                            @endforeach
                                    </select>
                                    @error('currency')
                                            <i class="text-danger mt-3 d-flex ">{{$message}}</i>
                                    @enderror
                                    </td>
                                </tr>
                                <tr class="exchange-rate">
                                    <th>Exchange Rate :</th>
                                    <td>
                                        <input type="text" placeholder="Exchange rate" value="1" class="form-control" id="exchange_rate" name="exchange_rate">
                                    </td>
                                </tr>
                                <tr class="text-info">
                                    <td>
                                        <hr>
                                        <h5 class="text-primary">Total :</h5>
                                    </td>
                                    <td>
                                        <hr>
                                        <h5 class="text-primary"><span class="total"></span></h5>
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
                        <a href="{{url()->previous()}}" class="btn btn-danger btn-print-invoice m-b-10 btn-mini waves-effect waves-light m-r-20"><i class="ti-close mr-2"></i>Cancel</a>
                        <button type="submit" class="btn btn-primary btn-mini waves-effect m-b-10 waves-light"><i class="ti-check mr-2"></i> Submit</button>
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
        var defaultCurrency = "{{Auth::user()->tenant->currency->id}}";
        $('.exchange-rate').hide();
        $('.js-example-basic-single').select2({
            placeholder: "Select product/service"
        });
        $('.scrollList').slimscroll({
            height: '430px',
        });
        var grand_total = 0;
        $('.invoice-detail-table').on('mouseup keyup', 'input[type=number]', ()=> calculateTotals());
        $(document).on('click', '.add-line', function(e){
            e.preventDefault();
            var new_selection = $('.item').first().clone();
            $('#products').append(new_selection);

            $(".select-product").select2({
                placeholder: "Select product or service"
            });
            $(".select-product").last().next().next().remove();
            setTotal();
            $(".js-example-basic-single").select2({ width: 'resolve' });
        });
         //calculate totals
         function calculateTotals(){
            const subTotals = $('.item').map((idx, val)=> calculateSubTotal(val)).get();
            const total = subTotals.reduce((a, v)=> a + Number(v), 0);
            grand_total = total;
            $('.sub_total').text(grand_total.toLocaleString());
            $('#subTotal').val(total);
            $('#mainTotal').val(grand_total);
            $('.total').text(total.toLocaleString());
            $('.balance').text(total.toLocaleString());
        }
        function calculateSubTotal(row){
            const $row = $(row);
            const inputs = $row.find('input');
            const subtotal = inputs[0].value * inputs[1].value;
            $row.find('td:nth-last-child(2) input[type=text]').val(subtotal);
            return subtotal;
        }

        $(document).on('change', '#currency', function(e){
					e.preventDefault();
					if(defaultCurrency != $(this).val()){
                            var abbr = $(this).find(':selected').data('abbr');
                            console.log(abbr);
							string = abbr+"_"+"{{Auth::user()->tenant->currency->abbr}}";
							var url = "https://free.currconv.com/api/v7/convert?q="+string+"&compact=ultra&apiKey=c6616c96883701c84660";
							axios.get(url)
							.then(response=>{
								$('#exchange_rate').val(response.data[string]);
							});
							$('.exchange-rate').show();
						}else{
							$('.exchange-rate').hide();
						}
                });

        $(document).on('change', '#vat', function(e){
            e.preventDefault();
            var vat = 0;
            var total = $('#mainTotal').val();
            var vat_amount = total*$(this).val()/100;
            $('.vat_amount').text(vat_amount.toLocaleString());
            var grandTotal = vat_amount + + total;
            $('.total').text(grandTotal.toLocaleString());

        });
    //Remove line
    $(document).on('click', '.remove-line', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        setTotal();
    });

    $(document).on('change', '#contact', function(e){
        e.preventDefault();
        axios.post('/get-contact', {contact:$(this).val()})
        .then(response=>{
            console.log(response.data.contact);
        });
    });
    billForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/contact/bill',new FormData(billForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Invoice raised.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    window.location.replace('bills');
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
