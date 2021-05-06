@extends('layouts.master-layout')

@section('title')
    All Banks
@endsection
@section('page-name')
    All Banks
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-description')
    List of all your banks.
@endsection

@section('page-link')
    <li class="breadcrumb-item"><a href="{{url()->current()}}"> All Banks</a>
    </li>
@endsection

@section('page-heading')
    All Banks
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12">

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    <ul id="validation-errors">
                    </ul>
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {!! session()->get('success') !!}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="card-header mb-4">
                        <h5>Banks</h5>
                    </div>
                    <div class="dt-responsive table-responsive">
                        <table  class="table table-striped table-bordered nowrap simpletable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Bank Name</th>
                                <th>Account Name</th>
                                <th>Account No.</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i=1;
                            @endphp
                            @foreach (Auth::user()->tenant->getBanks as $bank)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$bank->bank ?? ''}}</td>
                                    <td>{{$bank->account_name ?? ''}}</td>
                                    <td>{{$bank->account_no ?? ''}}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="btn btn-mini btn-primary" data-target="#modal_{{$bank->id}}" data-toggle="modal">View</a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="modal_{{$bank->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Bank</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('edit-tenant-bank')}}" method="post">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="">Bank Name</label>
                                                                        <input type="text" name="bank_name" value="{{$bank->bank ?? ''}}" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="">Account Name</label>
                                                                        <input type="text"  name="account_name" value="{{$bank->account_name ?? ''}}" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 col-lg-12">
                                                                    <div class="form-group">
                                                                        <label for="">Account No.</label>
                                                                        <input type="number"  name="account_no" value="{{$bank->account_no ?? ''}}" class="form-control">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="key" value="{{$bank->id}}">
                                                                <button type="submit" class="btn btn-primary btn-mini">Save changes</button>
                                                                <button type="button" class="btn btn-secondary btn-mini" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <form class="form-material" id="addNewServiceForm" method="post" action="{{route('tenant-banks')}}">
                        @csrf
                        <div class="card-header mb-4">
                            <h5>Add New Bank</h5>
                        </div>
                        <div class="form-group form-primary form-static-label">
                            <input type="text" name="bank_name" id="bank_name" class="form-control">
                            <span class="form-bar"></span>
                            <label class="float-label">Bank</label>
                            @error('bank')
                            <i class="text-danger mt-2">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group form-primary form-static-label">
                            <input type="text" name="account_name"  class="form-control">
                            <span class="form-bar"></span>
                            <label class="float-label">Account Name</label>
                            @error('account_name')
                            <i class="text-danger mt-2">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group form-primary form-static-label">
                            <input type="text" name="account_no"  id="account_no" class="form-control">
                            <span class="form-bar"></span>
                            <label class="float-label">Account No.</label>
                            @error('account_no')
                            <i class="text-danger mt-2">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="btn-group d-flex justify-content-center">
                            <a href="{{url()->previous()}}" class="btn btn-danger btn-mini"> <i class="ti-close mr-2"></i> Cancel</a>
                            <button type="submit"  class="btn btn-primary btn-mini save-contact"> <i class="ti-check mr-2"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/js/datatable.min.js"></script>

@endsection
