@extends('layouts.master-layout')

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
               <form action="" class="form-inline">
                <div class="form-group">
                    <label for="">From</label>
                    <input type="date" class="form-control ml-2" placeholder="From">
                </div>
                <div class="ml-2 form-group">
                    <label for="">To</label>
                    <input type="date" class="form-control ml-2" placeholder="From">
                </div>
                <div class="form-group">
                    <button class="btn-primary btn ">Filter</button>
                </div>
               </form>
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Log</th>
                    <th>Date</th>
                </tr>
                </thead>
                @php
                    $n = 1;
                @endphp
                @foreach ($logs as $log)
                    <tr>
                        <td>{{$n++}}</td>
                        <td>{{$log->subject ?? ''}}</td>
                        <td>{{$log->log ?? ''}}</td>
                        <td>{{date('d F, Y', strtotime($log->created_at))}}</td>
                    </tr>
                @endforeach
                <tbody>
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
