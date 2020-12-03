@extends('layouts.master-layout')

@section('title')
    Add New Contact
@endsection
@section('page-name')
Add New Contact
@endsection
@section('page-description')
    Add a new contact to your list.
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Add New Contact</a>
</li>
@endsection

@section('page-heading')
    Add New Contact
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">

        <form class="form-material" id="addNewContactForm">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    <ul id="validation-errors">
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="card-header mb-4">
                        <h5>Company Info</h5>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="company_name" id="company_name" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Company Name</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="company_email" id="company_email" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Company Email</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="company_phone_no" id="company_phone_no" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Company Phone No.</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="website" id="website" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Website</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="company_address" id="company_address" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Company Address</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="card-header mb-4">
                        <h5>Contact Person</h5>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="full_name" id="full_name" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Full Name</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="email_address" id="email_address" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Email Address</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="mobile_no" id="mobile_no" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Mobile No.</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="position" id="position" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Position</label>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="time" name="preferred_time" id="preferred_time" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">What time of the day will be most convenient to contact you?</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group form-primary form-static-label">
                                <select id="communication_channel" name="communication_channel" class="form-control form-control-inverse fill">
                                    <option selected disabled>Preferred communication channel</option>
                                    <option value="opt2">Email</option>
                                    <option value="opt3">SMS</option>
                                    <option value="opt4">Call</option>
                                    <option value="opt5">Others</option>
                                </select>
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
            addNewContactForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/add-new-contact',new FormData(addNewContactForm))
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
