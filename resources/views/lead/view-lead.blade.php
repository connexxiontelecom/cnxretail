@extends('layouts.master-layout')

@section('title')
    {{$lead->getContact->company_name ?? ''}}
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
{{$lead->getContact->company_name ?? ''}}
@endsection
@section('page-description')
    Learn more about {{$lead->getContact->company_name ?? ''}}
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">{{$lead->getContact->company_name ?? ''}}</a>
</li>
@endsection

@section('page-heading')
    Lead > {{$lead->getContact->company_name ?? ''}}
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
                                    <button type="button" data-toggle="modal" data-target="#assignModal" class="btn btn-mini btn-success">Assign to user</button>
                                    <button type="button" data-toggle="modal" data-target="#prospectingModal" class="btn btn-mini btn-secondary">Score Contact</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-stripped">
                            <tr>
                                <td>
                                    <h6>Company Name:</h6>
                                </td>
                                <td>{{$lead->getContact->company_name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Company Email:</h6>
                                </td>
                                <td>{{$lead->getContact->email ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Office Address:</h6>
                                </td>
                                <td>{{$lead->getContact->address ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Phone:</h6>
                                </td>
                                <td>{{$lead->getContact->company_phone ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Website:</h6>
                                </td>
                                <td>{{$lead->getContact->website ?? ''}}</td>
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
                                <td>{{$lead->getContact->contact_full_name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Email:</h6>
                                </td>
                                <td>{{$lead->getContact->contact_email ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Position:</h6>
                                </td>
                                <td>{{$lead->getContact->contact_position ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Mobile No.:</h6>
                                </td>
                                <td>{{$lead->getContact->contact_mobile ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Communication Channel:</h6>
                                </td>
                                <td>{{$lead->getContact->communication_channel ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Preferred Time:</h6>
                                </td>
                                <td>{{date('h:ia', strtotime($lead->getContact->preferred_time)) ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Score:</h6>
                                </td>
                                <td>{{$lead->score ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <h6>Assigned to:</h6>
                                </td>
                                <td>{{$lead->getAssignedTo->full_name ?? '' }}</td>
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
                        </ul>
                        <div class="tab-content card-block">
                            <div class="tab-pane active show" id="conversation" role="tabpanel">
                                <div class="card-header mb-3">
                                    <h5 class="text-uppercase">Interest</h5>
                                    <div class="card-header-right">
                                    </div>
                                </div>
                                <div class="card-block">
                                    <p><strong style="font-weight: 700;">Date & time: </strong> {{date('d F, Y', strtotime($lead->getProspect->date_time))}}</p>
                                    <p><strong style="font-weight: 700;">Remarks: </strong> {{$lead->getProspect->remarks ?? ''}}</p>

                                </div>
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
                                            <label class="float-label">What was your last conversation with {{$lead->getContact->company_name ?? ''}}?</label>
                                        </div>
                                        <input type="hidden" value="{{$lead->getContact->id}}" name="contact">
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
                                            @foreach ($lead->getContact->getConversations as $conversation)
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
                                        <p><label for="" class="badge badge-danger">{{count($lead->getContact->getContactInvoices)}}</label> invoice(s)</p>
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
                                        @foreach ($lead->getContact->getContactInvoices as $invoice)
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
                                    <p><label for="" class="badge badge-danger">{{count($lead->getContact->getContactReceipts)}}</label> receipt(s)</p>
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
                                    @foreach ($lead->getContact->getContactReceipts as $receipt)
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
          <h6 class="modal-title" id="exampleModalLabel">Score Lead</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="prospectingForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <input type="number" name="score" id="score" placeholder="Score" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">Score</label>
            </div>
              <hr>
            <input type="hidden" name="prospectingContact" value="{{$lead->getContact->id}}">
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
<div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Assign Lead</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="assignForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <select name="assign_user" id="assign_user" class="form-control">
                    <option disabled selected>Assign {{$lead->getContact->company_name ?? ''}} to</option>
                    @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->full_name ?? ''}}</option>
                    @endforeach
                </select>
                <span class="form-bar"></span>
                <label class="float-label">Assign user</label>
            </div>
              <hr>
            <input type="hidden" name="contactId" value="{{$lead->getContact->id}}">
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
                axios.post('/lead/score',new FormData(prospectingForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Score saved.",
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
                    location.reload();
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
        assignForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/lead/assign',new FormData(assignForm))
                .then(response=>{
                    Toastify({
                        text: "Success! User assigned",
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
                    $('#assignModal').modal('hide');
                    location.reload();
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
