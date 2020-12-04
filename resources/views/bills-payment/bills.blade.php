@extends('layouts.master-layout')

@section('title')
   Bills
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Bills
@endsection
@section('page-description')
    A list of all bills
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Bills</a>
</li>
@endsection

@section('page-heading')
Bills
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <a href="{{route('new-bill')}}" class="btn btn-primary btn-mini mb-3"><i class="ti-plus mr-2"></i>Add New Bill</a>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Vendor</th>
                    <th>Issued By</th>
                    <th>Bill No.</th>
                    <th>Total({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Paid({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Balance({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php
					$serial = 1;
                    @endphp
                    @foreach ($bills as $bill)
                        <tr>
                            <td>{{$serial++}}</td>
                            <td>{{$bill->getVendor->company_name ?? ''}}</td>
                            <td>{{$bill->issuedBy->full_name ?? ''}}</td>
                            <td>{{$bill->bill_no ?? ''}}</td>
                            <td>{{number_format($bill->bill_amount,2) ?? ''}}</td>
                            <td>{{number_format($bill->paid_amount,2) ?? ''}}</td>
                            <td>{{number_format($bill->bill_amount - $bill->paid_amount,2) ?? ''}}</td>
                            <td>{{date('d F, Y', strtotime($bill->bill_date))}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{route('view-bill', $bill->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Bill"><i class="ti-printer text-warning mr-2"></i></a>
                                    <a href="{{route('view-bill', $bill->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Bill"><i class="ti-eye text-success mr-2"></i></a>
                                </div>
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
