@extends('layouts.master-layout')

@section('title')
    {{$invoice->contact->company_name ?? ''}}
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-name')
{{$receipt->contact->company_name ?? ''}}
@endsection
@section('page-description')
    Details of  {{$receipt->contact->company_name ?? ''}} receipt
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">{{$invoice->contact->company_name ?? ''}}</a>
</li>
@endsection

@section('page-heading')
    Receipt Ref. No. {{$receipt->ref_no ?? ''}}
@endsection
@section('content')
<div>
    <div class="card">
        <form id="invoiceForm">

            <div class="row invoice-contact">
                <div class="col-md-8">
                    <div class="invoice-box row">
                        <div class="col-sm-12">
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
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
            <div class="card-block">
                <div class="row invoive-info">
                    <div class="col-md-4 col-xs-12 invoice-client-info">
                        <h6 class="text-uppercase">Client Information :</h6>
                        <h6 class="m-0">{{$receipt->contact->company_name ?? ''}}</h6>
                        <p class="m-0 m-t-10">{{$receipt->contact->address ?? ''}}</p>
                        <p class="m-0">{{$receipt->contact->company_phone ?? ''}}</p>
                        <p class="m-0">{{$receipt->contact->email ?? ''}}</p>
                        <p>{{$receipt->contact->website ?? ''}}</p>
                        <input type="hidden" value="{{$receipt->contact_id}}" name="contact">
                    </div>
                    <div class="col-md-4 col-xs-12 ">

                        <h6 class="text-uppercase">Order Information :</h6>
                        <table class="table table-responsive invoice-table invoice-order table-borderless">
                            <tbody>
                                <div class="form-group col-md-8 col-sm-8">
                                    <p><strong>Issue Date: </strong> {{date('d F, Y', strtotime($receipt->issue_date))}}</p>
                                </div>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <h6 class="m-b-20 text-uppercase">Ref. Number <span class="text-primary">#{{$receipt->ref_no ?? ''}}</span></h6>
                        <h6 class="text-uppercase text-primary">Total Due :
                            <span class="total">{{number_format($receipt->amount,2)}}</span>
                        </h6>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table  invoice-detail-table">
                                <thead>
                                    <tr class="thead-default">
                                        <th>Invoice</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody id="products">
                                    @foreach ($receipts as $item)
                                            <tr class="item">
                                                <td>
                                                    <p>Receipt issued for invoice # {{$item->getInvoice->invoice_no}}</p>
                                                </td>
                                                <td>
                                                    <p>{{number_format($item->getInvoice->total/$item->getInvoice->exchange_rate,2)}}</p>
                                                </td>
                                                <td>
                                                    <p>{{number_format($item->getInvoice->paid_amount/$item->getInvoice->exchange_rate,2) ?? ''}}</p>
                                                </td>
                                                <td>
                                                <p>{{number_format(($item->getInvoice->total/$item->getInvoice->exchange_rate) - ($item->getInvoice->paid_amount/$item->getInvoice->exchange_rate),2)}}</p>
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
                            <tbody class="float-right">
                                <tr class="text-info">
                                    <td>
                                        <hr>
                                        <h6 class="text-primary">Total :</h6>
                                    </td>
                                    <td>
                                        <hr>
                                        <h6 class="text-primary"><span class="total">{{number_format($receipts->sum('payment'),2)}}</span></h6>
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
                        <button type="submit" class="btn btn-primary btn-mini waves-effect m-b-10 waves-light"><i class="ti-printer mr-2"></i> Print</button>
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
        $(document).on('change', '#vat', function(e){
            e.preventDefault();
            var vat = 0;
            var total = $('#mainTotal').val();
            var vat_amount = total*$(this).val()/100;
            $('.vat_amount').val(vat_amount.toLocaleString());
            var grandTotal = vat_amount + + total;
            $('.total').text(grandTotal.toLocaleString());

        });
    //Remove line
    $(document).on('click', '.remove-line', function(e){
        e.preventDefault();
        $(this).closest('tr').remove();
        setTotal();
    });
    invoiceForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/contact/invoice',new FormData(invoiceForm))
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
