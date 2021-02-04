@extends('layouts.master-layout')

@section('title')
   All My Imprest
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
All My Imprest
@endsection
@section('page-description')
    A list of  all my imprest
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}"> All My Imprest</a>
</li>
@endsection

@section('page-heading')
All My Imprest
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
                    <div class="card-block">
                        <h5 class="sub-title">Add New Imprest</h5>
                        <form action="{{route('post-imprest')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" name="date" placeholder="Date" class="form-control">
                                @error('date')
                                    <i class="text-danger">{{$message}}</i>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Amount</label>
                                <input type="number" step="0.01" name="amount" placeholder="Amount" class="form-control">
                                @error('amount')
                                    <i class="text-danger">{{$message}}</i>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Bank</label>
                                <select name="bank" id="bank" class="form-control">
                                    <option disabled selected>Select bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->bank ?? ''}} - {{$bank->account_no ?? ''}}</option>
                                    @endforeach
                                </select>
                                @error('bank')
                                    <i class="text-danger">{{$message}}</i>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Responsible Officer</label>
                                <select name="responsible_officer" class="form-control">
                                    <option disabled selected>Select responsible person</option>
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->full_name ?? ''}}</option>
                                    @endforeach
                                </select>
                                @error('responsible_officer')
                                    <i class="text-danger">{{$message}}</i>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Description/Purpose</label>
                                <textarea name="description" id="description" style="resize: none;" class="form-control" placeholder="Description/Purpose"></textarea>
                                @error('description')
                                    <i class="text-danger">{{$message}}</i>
                                @enderror
                            </div>
                            <div class="form-group ">
                                <div class="btn-group d-flex justify-content-center">
                                    <a href="{{url()->previous()}}" class="btn-danger btn btn-mini"><i class="ti-close mr-2"></i> Cancel</a>
                                    <button class="btn-primary btn-mini btn" type="submit"><i class="ti-check mr-2"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-8">
                <div class="card">
                    <div class="card-block">
                        <h5 class="sub-title">All My Imprest</h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success background-success" role="alert">
                                {!! session()->get('success') !!}
                            </div>
                        @endif
                        <div class="dt-responsive table-responsive">
                            <table  class="table table-striped table-bordered nowrap simpletable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount({{Auth::user()->tenant->currency->symbol ?? 'N'}})</th>
                                    <th>Status</th>
                                    <th>Bank</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $serial = 1;
                                    @endphp
                                    @foreach ($imprests as $imprest)
                                        <tr>
                                            <td>{{$serial++}}</td>
                                            <td>{{date('d-M-Y', strtotime($imprest->transaction_date))}}</td>
                                            <td>{{number_format($imprest->amount,2)}}</td>
                                            <td>
                                                @if ($imprest->status == 0)
                                                    <label for="" class="label label-warning text-uppercase">Pending</label>
                                                @elseif($imprest->status == 1)
                                                    <label for="" class="label label-success text-uppercase">Approved</label>
                                                @endif
                                            </td>
                                            <td>{{$imprest->getBank->bank ?? ''}} - {{$imprest->getBank->account_no ?? ''}}</td>
                                            <td>
                                                <label for="" class="" style="display: block; cursor:pointer;" data-target="#imprestModal_{{$imprest->id}}" data-toggle="modal"><i class="ti-eye text-primary"></i></label>
                                                <div class="modal fade" id="imprestModal_{{$imprest->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h5 class="modal-title" id="exampleModalLabel">Imprest Details</h5>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="imprest_{{$imprest->id}}">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label for="">Date</label>
                                                                    <input type="text" readonly placeholder="Date" class="form-control" value="{{date('d-m-Y', strtotime($imprest->transaction_date))}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Amount</label>
                                                                    <input type="text" readonly placeholder="Amount" class="form-control" value="{{number_format($imprest->amount,2)}}">

                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Bank</label>
                                                                    <input type="text" readonly value="{{$imprest->getBank->bank ?? ''}} - {{$imprest->getBank->account_no ?? ''}}" class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Responsible Officer</label>
                                                                    <input type="text" value="{{$imprest->getResponsibleOfficer->full_name ?? ''}}" readonly class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Status</label>
                                                                    <br>
                                                                    @if ($imprest->status == 0)
                                                                        <label for="" class="label label-warning text-uppercase">Pending</label>
                                                                    @elseif($imprest->status == 1)
                                                                        <label for="" class="label label-success text-uppercase">Approved</label>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="">Description/Purpose</label>
                                                                    <textarea readonly class="form-control" placeholder="Description/Purpose">{{$imprest->description ?? ''}}</textarea>

                                                                </div>
                                                                <div class="form-group ">
                                                                    <div class="btn-group d-flex justify-content-center">
                                                                        <button data-dismiss="modal" class="btn-danger btn btn-mini"><i class="ti-close mr-2"></i> Close </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                      </div>
                                                    </div>
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
@endsection

@section('extra-scripts')
<script src="/assets/js/datatable.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();

        $(document).on('click', '.postImprest',function(e){
            e.preventDefault();
            axios.post('/approve-imprest',{imprest:$(this).val()})
            .then(response=>{
                Toastify({
                        text: "Success! Imprest posted.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                location.reload();
            })
            .catch(error=>{

            });
        });
    });
</script>
@endsection
