@extends('layouts.master-layout')

@section('title')
   Payments
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Payments
@endsection
@section('page-description')
    A list of all Payments
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Payments</a>
</li>
@endsection

@section('page-heading')
Payments
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <a href="{{route('make-payment')}}" class="btn btn-primary btn-mini mb-3"><i class="ti-plus mr-2"></i>Add New Payment</a>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Vendor</th>
                    <th>Bank</th>
                    <th>Ref. No.</th>
                    <th>Amount({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php
					$serial = 1;
                    @endphp
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{$serial++}}</td>
                            <td>{{$payment->vendor->company_name ?? ''}}</td>
                            <td>{{$payment->getBank->bank ?? ''}}: {{$payment->getBank->account_name ?? ''}}  - ({{$payment->getBank->account_no ?? ''}})</td>
                            <td>{{$payment->ref_no ?? ''}}</td>
                            <td>{{number_format($payment->amount,2) ?? ''}}</td>
                            <td>{{date('d F, Y', strtotime($payment->date_inputed))}}</td>
                            <td>
                                <a href="{{route('view-payment', $payment->slug)}}" class="btn btn-mini btn-warning"> View</a>
                            </td>
                        </tr>
                    @endforeach

                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
<script src="/assets/js/datatable.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();
    });
</script>
@endsection
