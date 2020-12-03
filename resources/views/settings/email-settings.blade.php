@extends('layouts.master-layout')

@section('title')
    Email Settings
@endsection
@section('page-name')
Email Settings
@endsection
@section('page-description')
Email Settings
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}"> Email Settings</a>
</li>
@endsection

@section('page-heading')
Email Settings
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">

        <form class="form-material" id="emailSettingsForm">
            @csrf
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    <ul id="validation-errors">
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card-header mb-4">
                        <h5>Email Settings</h5>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="text" name="signature" id="signature" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Text signature</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="form-group form-primary form-static-label">
                                <input type="file" name="logo" id="logo" class="form-control">
                                <span class="form-bar"></span>
                                <label class="float-label">Signature Image</label>
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
            emailSettingsForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/tenant/general-settings',new FormData(emailSettingsForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Changes saved.",
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
