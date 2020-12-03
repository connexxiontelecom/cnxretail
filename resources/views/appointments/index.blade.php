@extends('layouts.master-layout')

@section('title')
    Appointments
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Appointments
@endsection
@section('page-description')
    All appointments
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">All Appointments</a>
</li>
@endsection

@section('page-heading')
    All Appointments
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
                <a href="{{route('new-appointment')}}" class="btn btn-primary btn-mini"><i class="ti-plus mr-2"></i>Add New Appointment</a>
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($appointments as $item)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$item->full_name ?? ''}}</td>
                            <td>{{$item->email ?? ''}}</td>
                            <td>{{$item->mobile_no ?? ''}}</td>
                            <td>{{date('d F, Y', $item->schedule_date) ?? ''}}</td>
                            <td>Action</td>
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
