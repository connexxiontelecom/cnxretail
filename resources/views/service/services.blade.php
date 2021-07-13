@extends('layouts.master-layout')

@section('title')
    All Services
@endsection
@section('page-name')
    All Services
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-description')
   List of all your services.
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}"> All Services</a>
</li>
@endsection

@section('page-heading')
    All Services
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
                        <h5>Products/Services</h5>
                    </div>
                    <div class="dt-responsive table-responsive">
                        <table  class="table table-striped table-bordered nowrap simpletable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product/Service</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$service->service ?? ''}}</td>
                                            <td>{{number_format($service->amount ?? 0, 2) }}</td>
                                            <td>{{date('d F, Y', strtotime($service->created_at))}}</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-mini btn-primary" data-target="#modal_{{$service->id}}" data-toggle="modal">View</a>
                                                <!-- Modal -->
                                                <div class="modal fade" id="modal_{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Product/Service</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{route('edit-service')}}" method="post">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label for="">Product/Service Name</label>
                                                                                <input type="text" name="product_name" value="{{$service->service ?? ''}}" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group">
                                                                                <label for="">Amount</label>
                                                                                <input type="number" step="0.01" name="amount" value="{{$service->amount ?? ''}}" class="form-control">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="key" value="{{$service->id}}">
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
                    <form class="form-material" id="addNewServiceForm">
                        <div class="card-header mb-4">
                            <h5>Add New Service</h5>
                        </div>
                        <div class="form-group form-primary form-static-label">
                            <input type="text" name="service_product_name" id="service_product_name" class="form-control">
                            <span class="form-bar"></span>
                            <label class="float-label">Product/Service Name</label>
                        </div>
                        <div class="form-group form-primary form-static-label">
                            <input type="text" name="amount" value="0" id="amount" class="form-control">
                            <span class="form-bar"></span>
                            <label class="float-label">Amount</label>
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
    <script>
        $(document).ready(function(){
            $('.error-wrapper').hide();
            addNewServiceForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/add-new-service',new FormData(addNewServiceForm))
                .then(response=>{
                    Toastify({
                        text: "Success! New service registered.",
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
                //let result = await response.json();
                //alert(result.message);
            };
            $(document).on('click', '.close-errors', function(e){
                e.preventDefault();
                $('.error-wrapper').hide();
            });
        });
    </script>
@endsection
