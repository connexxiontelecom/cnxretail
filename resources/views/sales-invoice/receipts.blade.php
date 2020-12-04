@extends('layouts.master-layout')

@section('title')
   Receipts
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Receipts
@endsection
@section('page-description')
    A list of all receipts
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Receipts</a>
</li>
@endsection

@section('page-heading')
Receipts
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
                    <th>Ref. No.</th>
                    <th>Total({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php
					$serial = 1;
                    @endphp
                            @foreach ($receipts->where('trash', '!=',1) as $receipt)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$receipt->contact->company_name ?? ''}}</td>
                                    <td>{{$receipt->converter->full_name ?? ''}} </td>
                                    <td>{{$receipt->ref_no}}</td>
                                    <td>{{number_format(($receipt->amount),2)}}</td>
                                    <td>{{date('d F, Y', strtotime($receipt->issue_date))}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{route('view-receipt', $receipt->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Invoice"><i class="ti-printer text-warning mr-2"></i></a>
                                            <a href="{{route('view-receipt', $receipt->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Invoice"><i class="ti-eye text-success mr-2"></i></a>
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
