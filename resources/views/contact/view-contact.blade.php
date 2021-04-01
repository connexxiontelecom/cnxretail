@extends('layouts.master-layout')

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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 mb-1">
                                <div class="btn-group float-right">
                                    <a href="{{route('convert-to-lead', $contact->slug)}}" class="btn btn-mini btn-success">Raise Invoice</a>
                                    <button type="button" data-toggle="modal" data-target="#prospectingModal" class="btn btn-mini btn-secondary">Prospecting</button>
                                </div>
                            </div>
                        </div>
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
                    <div class="card-header">
                        <h5 class="text-uppercase">Contact Person</h5>
                        <div class="card-header-right">
                        </div>
                    </div>
                    <div class="card-block">
                        <table class="table table-stripped">
                            <tr>
                                <td>
                                    <h6>Full Name:</h6>
                                </td>
                                <td>{{$contact->contact_full_name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Email:</h6>
                                </td>
                                <td>{{$contact->contact_email ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Position:</h6>
                                </td>
                                <td>{{$contact->contact_position ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Mobile No.:</h6>
                                </td>
                                <td>{{$contact->contact_mobile ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Communication Channel:</h6>
                                </td>
                                <td>{{$contact->communication_channel ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Preferred Time:</h6>
                                </td>
                                <td>{{date('h:ia', strtotime($contact->preferred_time)) ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>WhatsApp Contact:</h6>
                                </td>
                                <td><a class="btn btn-mini btn-primary" href="https://api.whatsapp.com/send?phone={{$contact->whatsapp_contact}}">{{ !empty($contact->whatsapp_contact) ? 'Send message' : 'No Contact'}}</a></td>
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
                                <a class="nav-link  " data-toggle="tab" href="#invoice" role="tab" aria-selected="true">Invoice</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#messages3" role="tab">Receipt</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#settings3" role="tab">Payment History</a>
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
                                        <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                                            <ul id="validation-errors">
                                            </ul>
                                        </div>
                                    </div>
                                    <form id="conversationForm" class="form-material">
                                        <div class="form-group form-primary form-static-label">
                                            <input type="text" name="subject" name="subject" class="form-control">
                                            <span class="form-bar"></span>
                                            <label class="float-label">Subject</label>
                                        </div>
                                        <div class="form-group form-primary form-static-label">
                                            <textarea  name="conversation" id="conversation" class="form-control" style="resize: none;"></textarea>
                                            <span class="form-bar"></span>
                                            <label class="float-label">What was your last conversation with {{$contact->company_name ?? ''}}?</label>
                                        </div>
                                        <input type="hidden" value="{{$contact->id}}" name="contact">
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
                                            @foreach ($contact->getConversations as $conversation)
                                                <label class="badge badge-danger">{{$i++}}</label>
                                                <blockquote class="blockquote mt-2" style="font-size:16px;">
                                                    <cite title="{{$conversation->subject ?? ''}}">{{$conversation->subject ?? ''}}</cite>
                                                    <p class="m-b-0">{{$conversation->conversation ?? ''}}</p>
                                                    <footer class="blockquote-footer">{{$conversation->user->full_name ?? ''}}
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
                                        <p><label for="" class="badge badge-danger">{{count($contact->getContactInvoices)}}</label> invoice(s)</p>
                                    </div>
                                <div class="dt-responsive table-responsive">
                                    <table  class="table table-striped table-bordered nowrap simpletable">
                                        <thead>
                                        <tr>
                                            <th>Invoice No.</th>
                                            <th>Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($contact->getContactInvoices as $invoice)
                                            <tr>
                                                <td>{{$invoice->invoice_no}}</td>
                                                <td>{{number_format(($invoice->total/$invoice->exchange_rate),2)}}</td>
                                                <td>{{number_format($invoice->paid_amount/$invoice->exchange_rate,2)}}</td>
                                                <td>{{number_format(($invoice->total/$invoice->exchange_rate)  - ($invoice->paid_amount/$invoice->exchange_rate),2)}}</td>
                                                <td>{{date('d M, Y', strtotime($invoice->due_date))}}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{route('view-invoice', $invoice->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Invoice"><i class="ti-printer text-warning mr-2"></i></a>
                                                        <a href="{{route('receive-payment', $invoice->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Receive Payment"><i class="ti-wallet text-primary mr-2"></i></a>
                                                        <a href="{{route('view-invoice', $invoice->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Invoice"><i class="ti-eye text-success mr-2"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="messages3" role="tabpanel">
                                <div>
                                    <p><label for="" class="badge badge-danger">{{count($contact->getContactReceipts)}}</label> receipt(s)</p>
                                </div>
                            <div class="dt-responsive table-responsive">
                                <table  class="table table-striped table-bordered nowrap simpletable">
                                    <thead>
                                    <tr>
                                        <th>Receipt No.</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($contact->getContactReceipts as $receipt)
                                        <tr>
                                            <td>{{$receipt->ref_no}}</td>
                                            <td>{{number_format(($receipt->amount),2)}}</td>
                                            <td>{{date('d F, Y', strtotime($receipt->issue_date))}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{route('view-receipt', $receipt->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Print Invoice"><i class="ti-printer text-warning mr-2"></i></a>
                                                    <a href="{{route('view-receipt', $receipt->slug)}}" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Invoice"><i class="ti-eye text-success mr-2"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tfoot>
                                </table>
                            </div>
                            </div>
                            <div class="tab-pane" id="settings3" role="tabpanel">
                                <div class="col-md-12 col-sm-12">
                                    <div class="dt-responsive table-responsive">
                                        <table  class="table table-striped table-bordered nowrap simpletable">
                                            <thead>
                                            <tr>
                                                <th>S/No.</th>
                                                <th>Date</th>
                                                <th>Narration</th>
                                                <th>DR</th>
                                                <th>CR</th>
                                                <th>Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $n = 1;
                                                @endphp
                                            @foreach ($contact->getPaymentHistory as $pay)
                                                    <tr>
                                                        <td>{{$n++}}</td>
                                                        <td>{{date('d M, Y', strtotime($pay->transaction_date)) ?? ''}}</td>
                                                        <td>{{$pay->narration ?? ''}}</td>
                                                        <td>{{number_format($pay->type == 2 ? $pay->amount : 0,2)}}</td>
                                                        <td>{{number_format($pay->type == 1 ? $pay->amount : 0,2)}}</td>
                                                        <input type="hidden" value="{{$balance += ($balance + $pay->type == 2 ? $pay->amount : 0) - ($pay->type == 1 ? $pay->amount : 0)}}">
                                                        <td>{{number_format( $balance,2)}}</td>
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
</div>

<div class="modal fade" id="prospectingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Prospecting</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="prospectingForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <input type="datetime-local" name="date_time" id="date_time" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">Date & Time</label>
            </div>
            <div class="form-group form-primary form-static-label">
                <textarea name="remarks" id="remarks" class="form-control"></textarea>
                <span class="form-bar"></span>
                <label class="float-label">Remarks</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="reminder" name="reminder">
                <label class="form-check-label" for="exampleCheck1">Set reminder</label>
              </div>
              <hr>
            <input type="hidden" name="prospectingContact" value="{{$contact->id}}">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="btn-group d-flex justify-content-center">
                        <button class="btn btn-danger btn-mini" data-dismiss="modal"><i class="ti-close mr-2"></i> Close</button>
                        <button class="btn btn-primary btn-mini" type="submit"><i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
          </form>
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

        conversationForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/contact/conversation',new FormData(conversationForm))
                .then(response=>{
                    Toastify({
                        text: "Success! New conversation registered.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    conversationForm.reset();
                    //window.location.replace(response.data.route);
                })
                .catch(error=>{
                        $('#validation-errors').html('');
                        $.each(error.response.data.errors, function(key, value){
                            Toastify({
                            text: value,
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "linear-gradient(to right, #FF0000, #FE0000)",
                            stopOnFocus: true,
                            onClick: function(){}
                        }).showToast();
                        $('#validation-errors').append("<li><i class='ti-hand-point-right text-danger mr-2'></i><small class='text-danger'>"+value+"</small></li>");
                    });
                });
            };
        prospectingForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/contact/prospecting',new FormData(prospectingForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Prospect saved.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    conversationForm.reset();
                    $('#prospectingModal').modal('hide');
                })
                .catch(error=>{
                        $('#validation-errors').html('');
                        $.each(error.response.data.errors, function(key, value){
                            Toastify({
                            text: value,
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "linear-gradient(to right, #FF0000, #FE0000)",
                            stopOnFocus: true,
                            onClick: function(){}
                        }).showToast();
                        $('#validation-errors').append("<li><i class='ti-hand-point-right text-danger mr-2'></i><small class='text-danger'>"+value+"</small></li>");
                    });
                });
            };
    });
</script>
@endsection
