@extends('layouts.admin-layout')

@section('title')
    Activity Log
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Activity Log
@endsection
@section('page-description')
    Activity Log
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Activity Log</a>
</li>
@endsection

@section('page-heading')
    Activity Log
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
                <a href="{{route('admin.add.new.admin.user')}}" class="btn btn-primary btn-mini"><i class="ti-plus mr-2"></i>Add New Admin User</a>
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Subject </th>
                    <th>Log</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $contact)
                            <td>{{$contact->getTenant->company_name ?? ''}}</td>
                            <td>{{$contact->subject ?? ''}}</td>
                            <td>{{$contact->log ?? ''}}</td>
                            <td>{{!is_null($contact->created_at) ? date('d F, Y', strtotime($contact->created_at)) : ''}}</td>

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
