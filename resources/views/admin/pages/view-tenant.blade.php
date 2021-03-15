@extends('layouts.admin-layout')

@section('title')
    {{$contact->company_name ?? ''}}
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
{{$contact->company_name ?? ''}}
@endsection
@section('page-description')
    Learn more about {{$contact->company_name ?? ''}}
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">{{$contact->company_name ?? ''}}</a>
</li>
@endsection

@section('page-heading')
    Contact > {{$contact->company_name ?? ''}}
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
            <div class="col-md-4 col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-uppercase">Company Information</h5>
                        <div class="card-header-right">
                        </div>
                    </div>
                    <div class="card-block">
                        <table class="table table-stripped">
                            <tr>
                                <td>
                                    <h6>Company Name:</h6>
                                </td>
                                <td>{{$contact->company_name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Company Email:</h6>
                                </td>
                                <td>{{$contact->email ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Office Address:</h6>
                                </td>
                                <td>{{$contact->address ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Phone:</h6>
                                </td>
                                <td>{{$contact->company_phone ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Website:</h6>
                                </td>
                                <td>{{$contact->website ?? ''}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-8">
                <div class="card">
                    <div class="card-block">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs md-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#conversation" role="tab" aria-selected="false">Conversation</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  " data-toggle="tab" href="#invoice" role="tab" aria-selected="true">Subscription</a>
                                <div class="slide"></div>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content card-block">
                            <div class="tab-pane active show" id="conversation" role="tabpanel">
                                <div class="card-header mb-3">
                                    <h5 class="text-uppercase">New Conversation</h5>
                                    <div class="card-header-right">

                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12 ">
                                            @if (session()->has('success'))
                                                <div class="alert alert-success background-success">{!! session()->get('success') !!}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <form id="conversationForm" class="form-material" action="{{route('admin.message.tenant')}}" method="post">
                                        @csrf
                                        <div class="form-group form-primary form-static-label">
                                            <input type="text" name="subject" name="subject" class="form-control">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Subject</label>
                                            @error('subject')
                                                <i class="text-danger">{{$message}}</i>
                                            @enderror
                                        </div>
                                        <div class="form-group form-primary form-static-label">
                                            <textarea  name="message" id="message" class="form-control" style="resize: none;"></textarea>
                                            <span class="form-bar"></span>
                                            @error('message')
                                                <i class="text-danger">{{$message}}</i>
                                            @enderror
                                        </div>
                                        <input type="hidden" value="{{$contact->tenant_id}}" name="tenant">
                                        <div class="btn-group d-flex justify-content-center">
                                            <button class="btn btn-mini btn-primary" type="submit"><i class="mr-2 ti-check"></i>Submit</button>
                                        </div>
                                    </form>
                                    <div class="card-header mt-3">
                                        <h5 class="text-uppercase">Previous Conversations</h5>
                                        <div class="card-header-right">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-12 col-md-12 mb-3 scrollList" style="overflow-y: scroll;height: 430px; width:100%;">
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($contact->getTenantConversations as $conversation)
                                                <label class="badge badge-danger">{{$i++}}</label>
                                                <blockquote class="blockquote mt-2" style="font-size:16px;">
                                                    <cite title="{{$conversation->subject ?? ''}}">{{$conversation->subject ?? ''}}</cite>
                                                    <p class="m-b-0">{{$conversation->message ?? ''}}</p>
                                                    <footer class="blockquote-footer">{{$conversation->getSender->full_name ?? ''}}
                                                        | <small>{{date('d F, Y h:ia', strtotime($conversation->created_at))}}</small>
                                                    </footer>
                                                </blockquote>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane " id="invoice" role="tabpanel">
                                    <div>
                                        <p><label for="" class="badge badge-danger">{{count($subscriptions)}}</label> subscription(s)</p>
                                    </div>
                                <div class="dt-responsive table-responsive">
                                    <table  class="table table-striped table-bordered nowrap simpletable">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sub. Key</th>
                                            <th>Start Date</th>
                                            <th>Status</th>
                                            <th>End Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $s = 1;
                                            @endphp
                                            @foreach ($subscriptions as $item)
                                                <tr>
                                                    <td>{{$s++}}</td>
                                                    <td>{{$item->sub_key ?? ''}}</td>
                                                    <td>{{date('d M, Y', strtotime($item->start_date))}}</td>
                                                    <td>
                                                        @if ($item->status == 1)
                                                            <label class="label label-success">Active</label>
                                                        @else
                                                            <label class="label label-danger">Inactive</label>

                                                        @endif
                                                    </td>
                                                    <td>{{date('d M, Y', strtotime($item->end_date))}}</td>
                                                </tr>
                                            @endforeach
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
<script type="text/javascript" src="\assets\js\jquery.slimscroll.min.js"></script>
<script src="/assets/js/datatable.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();
        $('.scrollList').slimscroll({
            height: '430px',
        });

    });
</script>
@endsection
