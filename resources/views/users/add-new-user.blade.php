@extends('layouts.master-layout')

@section('title')
    Add New User
@endsection
@section('page-name')
Add New User
@endsection
@section('page-description')
    Add a new user to your list.
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Add New User</a>
</li>
@endsection

@section('page-heading')
    Add New User
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">

        <form class="form-material" id="addNewUserForm">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    <ul id="validation-errors">
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card-header mb-4">
                        <h5>User Info</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="text" name="full_name" placeholder="Full Name" id="full_name" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Full Name</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="text" name="email" placeholder="Email" id="email" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Email</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="text" name="mobile_no" placeholder="Mobile No." id="mobile_no" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Mobile No.</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <select id="gender" name="gender" class="form-control form-control-inverse fill">
                                    <option selected disabled>Select gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-primary form-static-label">
                                <select id="marital_status" name="marital_status" class="form-control form-control-inverse fill">
                                    <option selected disabled>Select marital status</option>
                                    <option value="1">Single</option>
                                    <option value="2">Married</option>
                                    <option value="3">Divorced</option>
                                    <option value="4">Separated</option>
                                    <option value="5">Complicated</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="text" name="address" id="address" placeholder="Address" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Address</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-md-12">
                    <div class="btn-group d-flex justify-content-center">
                        <a href="" class="btn btn-danger btn-mini"> <i class="ti-close mr-2"></i> Cancel</a>
                        <button type="submit"  class="btn btn-primary btn-mini save-contact"> <i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra-scripts')
    <script>
        $(document).ready(function(){
            $('.error-wrapper').hide();
            addNewUserForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/add-new-user',new FormData(addNewUserForm))
                .then(response=>{
                    Toastify({
                        text: "Success! New contact registered.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    window.location.replace(response.data.route);
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
            $(document).on('click', '.close-errors', function(e){
                e.preventDefault();
                $('.error-wrapper').hide();
            });
        });
    </script>
@endsection
