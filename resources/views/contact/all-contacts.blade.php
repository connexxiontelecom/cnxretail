@extends('layouts.master-layout')

@section('title')
    All Contacts
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
All Contacts
@endsection
@section('page-description')
    A list of all your contacts
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">All Contacts</a>
</li>
@endsection

@section('page-heading')
    All Contacts
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
            <div class="col-md-12 col-sm-12">
                <a href="{{route('add-new-contact')}}" class="btn btn-primary btn-mini"><i class="ti-plus mr-2"></i>Add New Contact</a>
            </div>
        </div>
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
                    @foreach ($contacts as $contact)
                        <tr>
                            <td>{{$contact->company_name ?? ''}}</td>
                            <td>{{$contact->contact_full_name ?? ''}}</td>
                            <td>{{$contact->email ?? ''}},{{$contact->contact_email ?? ''}}</td>
                            <td>{{$contact->company_phone ?? ''}}, {{$contact->contact_mobile ?? ''}}</td>
                            <td>{{!is_null($contact->created_at) ? date('d F, Y', strtotime($contact->created_at)) : ''}}</td>
                            <td>
                                <a href="{{route('view-contact', $contact->slug)}}" class="btn btn-warning btn-mini">View</a>
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
