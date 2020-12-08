@extends('layouts.master-layout')

@section('title')
    SMS Preview
@endsection
@section('page-name')
SMS Preview
@endsection
@section('page-description')
SMS Preview
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">SMS Preview</a>
</li>
@endsection

@section('page-heading')
SMS Preview
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">

        <form class="form-material" id="composeSmsForm" autocomplete="off" action="{{route('compose-sms')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    <ul id="validation-errors">
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-12 col-lg-12">
                    <div class="alert alert-warning">
                        <strong style="font-weight: 500;">Your message has not been sent yet.</strong>  Kindly go through the SMS summary below and then click the "Proceed to Send" button at the bottom of the page to send your message.
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 text-center">
                                <h3>N2.19</h3>
                                <p>SMS COST (1.10 units)</p>
                            </div>
                            <div class="col-md-4 col-sm-4 text-center">
                                <h3>1</h3>
                                <p>RECIPIENTS</p>
                            </div>
                            <div class="col-md-4 col-sm-4 text-center">
                                <h3>1</h3>
                                <p>PAGE</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <form action="">
                                    <div class="form-group">
                                        <label for="">Sender ID</label>
                                        <input type="text" value="{{$senderId}}" readonly class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone Numbers</label>
                                        <input type="text" value="{{$senderId}}" readonly class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Text Message</label>
                                        <textarea name="" class="form-control" id="" cols="30" rows="10">{{$message}}</textarea>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-md-12">
                    @if ($account->sum('debit') - $account->sum('credit') < $cost)
                        <p><strong style="font-weight: 700;" class="text-danger">Ooops!</strong> Insufficient balance.</p>
                    @endif
                    <div class="btn-group d-flex justify-content-center">
                        <a href="" class="btn btn-danger btn-mini"> <i class="ti-close mr-2"></i> Cancel</a>
                        <button {{ $account->sum('debit') - $account->sum('credit') < $cost ? 'disabled' : ''}} type="submit"  class="btn btn-primary btn-mini save-contact"> <i class="ti-check mr-2"></i> Proceed to Send</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('extra-scripts')

@endsection
