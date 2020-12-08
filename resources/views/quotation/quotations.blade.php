@extends('layouts.master-layout')

@section('title')
   Quotations
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Quotations
@endsection
@section('page-description')
    A list of all Quotations
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Quotations</a>
</li>
@endsection

@section('page-heading')
Quotations
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <a href="" class="btn btn-primary btn-mini mb-3"><i class="ti-plus mr-2"></i>Add New Quotation</a>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Issued By</th>
                    <th>Quotation No.</th>
                    <th>Total ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Paid ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Balance ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php
					$serial = 1;
                    @endphp

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
