@extends('layouts.master-layout')

@section('title')
    {{$mail->subject ?? ''}}
@endsection
@section('extra-styles')

@endsection
@section('page-name')
Read SMS
@endsection
@section('page-description')
    Read SMS
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Read SMS</a>
</li>
@endsection

@section('page-heading')
    {{$sms->sender_id}}

@endsection
@section('content')
<div>
    <div class="card">
        <form id="invoiceForm">
            <div class="card-block">
                <div class="row mb-2">
                    <div class="col-sm-12 col-md-12">
                        <div class="btn-group">
                            <a href="{{route('bulksms')}}" class="btn btn-mini btn-secondary float-right"><i class="ti-layers-alt mr-2"></i>Bulk SMS</a>
                            <a href="{{route('compose-sms')}}" class="btn btn-mini btn-primary float-right"><i class="ti-plus mr-2"></i>Compose SMS</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 col-sm-8 col-lg-8">
                        {!! $sms->message !!}
                        <hr>
                        <p style="border-left: 2px solid #FF0000; padding-left:5px;">
                            <small><strong style="font-weight:700;">Date: </strong>{{date('d F, Y @ h:ia', strtotime($sms->created_at))}} | <strong style="font-weight:700;">Sender: </strong>{{$sms->sender->full_name ?? ''}}</small>
                        </p>
                    </div>
                    <div class="col-md-4 col-sm-4 col-lg-4">
                        <div class="card-header">
                            <h5 class="text-uppercase">Recipient(s)
                            </h5>
                            <div class="card-header-right">
                            </div>
                            <ol>
                                @foreach ($sms->getSmsRecipients as $receiver)
                                    <li>{{$receiver->contact_id }} </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-sm-12 invoice-btn-group text-center">
                    <div class="btn-group d-flex justify-content-center">
                        <a href="{{url()->previous()}}" class="btn btn-secondary btn-print-invoice m-b-10 btn-mini waves-effect waves-light m-r-20"><i class="ti-close mr-2"></i>Cancel</a>
                        <button type="submit" class="btn btn-primary btn-mini waves-effect m-b-10 waves-light"><i class="ti-check mr-2"></i> Print</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('extra-scripts')
<script>

</script>
@endsection
