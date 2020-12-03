@extends('layouts.master-layout')

@section('title')
    All Users
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
All Users
@endsection
@section('page-description')
    A list of all your users
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">All Users</a>
</li>
@endsection

@section('page-heading')
    All Users
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
                <a href="{{route('show-user-form')}}" class="btn btn-primary btn-mini"><i class="ti-plus mr-2"></i>Add New User</a>
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Mobile No.</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{$user->full_name ?? ''}}</td>
                            <td>{{$user->email ?? ''}}</td>
                            <td>{{$user->gender == 1 ? 'Male' : 'Female'}}</td>
                            <td>{{$user->mobile_no ?? ''}}</td>
                            <td>{{!is_null($user->created_at) ? date('d F, Y', strtotime($user->created_at)) : ''}}</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-mini">View</a>
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
