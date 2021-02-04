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
        <a href="{{route('add-new-quotation')}}" class="btn btn-primary btn-mini mb-3"><i class="ti-plus mr-2"></i>Add New Quotation</a>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Issued By</th>
                    <th>Quotation No.</th>
                    <th>Total ({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                    @php
					$serial = 1;
                    @endphp
                            @foreach ($quotations->where('trash', '!=',1) as $quotation)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$quotation->contact->company_name ?? ''}}</td>
                                    <td>{{$quotation->converter->full_name ?? ''}} </td>
                                    <td>{{$quotation->quotation_no}}</td>
                                    <td>{{number_format(($quotation->total),2)}}</td>
                                    <td>{{date('d F, Y', strtotime($quotation->created_at))}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{route('view-quotation', $quotation->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Quotation"><i class="ti-printer text-warning mr-2"></i></a>
                                            <a href="{{route('view-quotation', $quotation->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Quotation"><i class="ti-eye text-success mr-2"></i></a>
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
