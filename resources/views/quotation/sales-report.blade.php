@extends('layouts.master-layout')

@section('title')
    Sales Report
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Sales Report
@endsection
@section('page-description')
Sales Report
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Sales Report</a>
</li>
@endsection

@section('page-heading')
Sales Report
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
            <div class="col-xl-12">
                <div class="card proj-progress-card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <h6>Invoice</h6>
                                <h6 class="m-b-30 f-w-700">{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{number_format($invoices->sum('total') - $invoices->sum('paid_amount'),2)}}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-c-yellow" style="width:100%"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h6>Receipt</h6>
                                <h6 class="m-b-30 f-w-700">{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{number_format($receipts->sum('amount',2))}}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-c-green" style="width:100%"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h6>Bills</h6>
                                <h6 class="m-b-30 f-w-700">{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{number_format($bills->sum('bill_amount') - $bills->sum('paid_amount'),2)}}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-c-yellow" style="width:100%"></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <h6>Payment</h6>
                                <h6 class="m-b-30 f-w-700">{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{number_format($payments->sum('amount'),2)}}</h6>
                                <div class="progress">
                                    <div class="progress-bar bg-c-red" style="width:100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Section filter-->
            <div class="col-md-12 col-sm-12">
                <div class="card-header mb-3">
                    <h5 class="text-uppercase">Filter
                    </h5>
                    <div class="card-header-right">
                    </div>
                </div>
               <form action="{{route('filter-sales-report')}}" class="form-inline" method="post">
                   @csrf
                <div class="form-group">
                    <label for="">From</label>
                    <input type="date" name="from" class="form-control ml-2" placeholder="From">
                    @error('from')
                        <i class="text-danger">{{$message}}</i>
                    @enderror
                </div>
                <div class="ml-2 form-group">
                    <label for="">To</label>
                    <input type="date" class="form-control ml-2" placeholder="To" name="to">
                    @error('to')
                        <i class="text-danger">{{$message}}</i>
                    @enderror
                </div>
                <div class="form-group">
                    <button class="btn-primary btn " type="submit">Filter</button>
                </div>
               </form>
            </div>
        </div>
        <div class="card-header mb-3">
            <h5 class="text-uppercase">Inflow <label for="" class="label label-primary">From: {{date('d F, Y', strtotime($from))}}</label> <label for="" class="label label-primary">To: {{date('d F, Y', strtotime($to))}}</label>
            </h5>
            <div class="card-header-right">
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Contact</th>
                    <th>Ref. No.</th>
                    <th>Amount({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                @php
                    $n = 1;
                @endphp
                <tbody>
                    @foreach ($receipts as $receipt)
                        <tr>
                            <td>{{$n++}}</td>
                            <td>{{$receipt->contact->company_name ?? ''}}</td>
                            <td>{{$receipt->ref_no ?? ''}}</td>
                            <td>{{ number_format($receipt->amount,2) }}</td>
                            <td>
                                @if ($receipt->payment_type == 1)
                                    <label class="label label-secondary">Cash</label>
                                @elseif($receipt->payment_type == 2)
                                    <label class="label label-secondary">Bank Transfer</label>
                                @else
                                    <label class="label label-secondary">Cheque</label>
                                @endif
                            </td>
                            <td>{{date('d-m-Y', strtotime($receipt->issue_date))}}</td>
                            <td>
                                <a href="" class="btn btn-mini btn-warning">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tfoot>
            </table>
        </div>
        <div class="card-header mb-3">
            <h5 class="text-uppercase">Outflow <label for="" class="label label-primary">From: {{date('d F, Y', strtotime($from))}}</label> <label for="" class="label label-primary">To: {{date('d F, Y', strtotime($to))}}</label>
            </h5>
            <div class="card-header-right">
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Vendor</th>
                    <th>Ref. No.</th>
                    <th>Amount({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Bank</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                @php
                    $i = 1;
                @endphp
                <tbody>
                    @foreach ($payments as $pay)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$pay->contact->company_name ?? ''}}</td>
                            <td>{{$pay->ref_no ?? ''}}</td>
                            <td>{{ number_format($pay->amount,2) }}</td>
                            <td>{{$pay->getBank->account_name ?? ''}} - {{$pay->getBank->account_no ?? ''}}</td>
                            <td>{{date('d-m-Y', strtotime($receipt->issue_date))}}</td>
                            <td>
                                <a href="" class="btn btn-mini btn-warning">View</a>
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
