@extends('layouts.master-layout')

@section('title')
    All Deals
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
All Deals
@endsection
@section('page-description')
    A list of all your deals
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">All Deals</a>
</li>
@endsection

@section('page-heading')
All Deals
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
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($deals as $contact)
                        <tr>
                            <td>{{$contact->getContact->company_name ?? ''}}</td>
                            <td>{{$contact->getContact->contact_full_name ?? ''}}</td>
                            <td>{{$contact->getContact->email ?? ''}},{{$contact->getContact->contact_email ?? ''}}</td>
                            <td>{{$contact->getContact->company_phone ?? ''}}, {{$contact->getContact->contact_mobile ?? ''}}</td>
                            <td>{{!is_null($contact->getContact->created_at) ? date('d F, Y', strtotime($contact->getContact->created_at)) : ''}}</td>
                            <td>
                                <a href="{{route('view-contact', $contact->getContact->slug)}}" class="btn btn-warning btn-mini">View</a>
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
