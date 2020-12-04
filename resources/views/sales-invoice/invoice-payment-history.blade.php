@extends('layouts.master-layout')

@section('title')
   Invoice Payment History
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Invoice Payment History
@endsection
@section('page-description')
Invoice Payment History
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">   Invoice Payment History</a>
</li>
@endsection

@section('page-heading')
Invoice Payment History
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
                    <th>Total ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Paid ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Balance ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Due Date</th>
                </tr>
                </thead>
                <tbody>
                    @php
					$serial = 1;
                    @endphp
                            @foreach ($invoices->where('trash', '!=',1) as $invo)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$invoice->contact->company_name ?? ''}}</td>
                                    <td>{{$invoice->converter->full_name ?? ''}} </td>
                                    <td>{{$invoice->invoice_no}}</td>
                                    <td>{{number_format(($invoice->total),2)}}</td>
                                    <td>{{number_format($invo->payment * $invoice->exchange_rate ?? 1,2)}}</td>
                                    <td>{{number_format(($invoice->total)  - ($invo->payment * $invoice->exchange_rate ?? 1),2)}}</td>
                                    <td>{{date('d F, Y', strtotime($invoice->due_date))}}</td>
                                </tr>
                                @endforeach
                            </tfoot>
                        </table>
                    </div>
                    <a href="{{route('view-invoice', $invoice->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Invoice"><i class="ti-printer text-warning mr-2"></i></a>
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
