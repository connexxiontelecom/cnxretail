@extends('layouts.master-layout')

@section('title')
    Reminder List View
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">

@endsection
@section('page-name')
Reminder List View
@endsection
@section('page-description')
Reminder List View
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Reminder List View</a>
</li>
@endsection

@section('page-heading')
Reminder List View
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="dt-responsive table-responsive">
                    <table  class="table table-striped table-bordered nowrap simpletable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Date & Time</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Note</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $n = 1;
                            @endphp
                            @foreach ($reminders as $reminder)
                                <tr>
                                    <td>{{$n++}}</td>
                                    <td>{{$reminder->reminder_name ?? ''}}</td>
                                    <td>{{ date('d F, Y @ h:ia', strtotime($reminder->remind_at)) }}</td>
                                    <td>{{$reminder->priority == 1 ?  'Low' : 'High'}}</td>
                                    <td>{{date('d F, Y', strtotime($reminder->created_at))}}</td>
                                    <td>{{$reminder->note ?? ''}}</td>
                                </tr>
                            @endforeach
                        </tfoot>
                    </table>
                </div>
            </div>
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
