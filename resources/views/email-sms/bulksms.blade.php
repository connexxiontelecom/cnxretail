@extends('layouts.master-layout')

@section('title')
    BulkSMS
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
BulkSMS
@endsection
@section('page-description')
BulkSMS
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">BulkSMS</a>
</li>
@endsection

@section('page-heading')
BulkSMS
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success background-success">
                {!! session()->get('success') !!}
            </div>
        @endif
        <div class="row mb-3">
            <div class="col-md-12 col-sm-12 ">
                <a  href="{{route('compose-sms')}}" class="btn btn-mini btn-primary"><i class="ti-plus mr-2"></i>Compose SMS</a>
                <a  href="{{route('bulksms-balance')}}" class="btn btn-mini btn-primary"><i class="ti-plus mr-2"></i>Balance</a>
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>Sender ID</th>
                    <th>Content</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($bulksms as $sms)
                        <tr>
                            <td>{{$sms->sender_id}}</td>
                            <td><a href="{{route('read-sms', $sms->slug)}}"><strong>{{strip_tags(substr($sms->message, 0, 100))}}...</a></td>
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

        $(document).on('click', '.balance', function(e){
            e.preventDefault();
            axios.post('/sms/balance')
            .then(response=>{
                console.log(response.data);
            })
            .catch(error=>{
                console.log(error.response.errors);
            });
        });
    });
</script>
@endsection
