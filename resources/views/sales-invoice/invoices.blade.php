@extends('layouts.master-layout')

@section('title')
   Invoices
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
    Invoices
@endsection
@section('page-description')
    A list of all invoices
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Invoices</a>
</li>
@endsection

@section('page-heading')
    Invoices
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Issued By</th>
                    <th>Invoice No.</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php
					$serial = 1;
                    @endphp
                            @foreach ($invoices->where('trash', '!=',1) as $invoice)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$invoice->contact->company_name ?? ''}}</td>
                                    <td>{{$invoice->converter->full_name ?? ''}} </td>
                                    <td>{{$invoice->invoice_no}}</td>
                                    <td>{{number_format(($invoice->total/$invoice->exchange_rate),2)}}</td>
                                    <td>{{number_format($invoice->paid_amount/$invoice->exchange_rate,2)}}</td>
                                    <td>{{number_format(($invoice->total/$invoice->exchange_rate)  - ($invoice->paid_amount/$invoice->exchange_rate),2)}}</td>
                                    <td>{{date('d F, Y', strtotime($invoice->due_date))}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{route('view-invoice', $invoice->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Invoice"><i class="ti-printer text-warning mr-2"></i></a>
                                            <a href="{{route('receive-payment', $invoice->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Receive Payment"><i class="ti-wallet text-primary mr-2"></i></a>
                                            <a href="{{route('view-invoice', $invoice->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Invoice"><i class="ti-eye text-success mr-2"></i></a>
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
