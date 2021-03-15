@extends('layouts.admin-layout')

@section('title')
    All Tenants
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
All Tenants
@endsection
@section('page-description')
    A list of all your Tenants
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">All Tenants</a>
</li>
@endsection

@section('page-heading')
    All Tenants
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
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($tenants as $contact)
                        <tr>
                            <td>{{$contact->company_name ?? ''}}</td>
                            <td>{{$contact->email ?? ''}},{{$contact->email ?? ''}}</td>
                            <td> {{$contact->phone ?? ''}}</td>
                            <td>{{!is_null($contact->start) ? date('d F, Y', strtotime($contact->start)) : ''}}</td>
                            <td>{{!is_null($contact->end) ? date('d F, Y', strtotime($contact->end)) : ''}}</td>
                            <td>
                                <a href="{{route('admin.view.tenant', $contact->slug)}}" class="btn btn-warning btn-mini">View</a>
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
