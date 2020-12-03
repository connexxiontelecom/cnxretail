@extends('layouts.master-layout')

@section('title')
    Mailbox
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Mailbox
@endsection
@section('page-description')
    Mailbox
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Mailbox</a>
</li>
@endsection

@section('page-heading')
Mailbox
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
            <div class="col-md-12 col-sm-12 ">
                <a href="{{route('compose-email')}}" class="btn btn-mini btn-primary"><i class="ti-plus mr-2"></i>Compose Email</a>
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>To</th>
                    <th>Content</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($mails as $item)
                        <tr>
                            <td>
                                @foreach ($item->getEmailRecipients->take(2) as $receiver)
                                <label class="label label-inverse-success">{{$receiver->contact->company_name }}</label>,
                                @endforeach{{count($item->getEmailRecipients) > 2 ? '...' : ''}}
                            </td>
                            <td><a href="{{route('read-mail', $item->slug)}}"><strong>{{$item->subject ?? ''}}</strong> - {{strip_tags(substr($item->content, 0, 100))}}...</a></td>
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
