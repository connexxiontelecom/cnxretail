@extends('layouts.master-layout')

@section('title')
    Compose Email
@endsection
@section('page-name')
Compose Email
@endsection
@section('page-description')
Compose Email
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Compose Email</a>
</li>
@endsection

@section('page-heading')
Compose Email
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">

        <form class="form-material" id="composeEmailForm" autocomplete="off">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    <ul id="validation-errors">
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="card-header mb-4">
                        <h5>Compose Email</h5>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="subject" id="subject" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Subject</label>
                    </div iv>
                    <div class="form-group">
                        <span ><label for="" class="label label-primary" style="cursor: pointer;" data-target="#recipientModal" data-toggle="modal">Add Recipient(s)</label></span>
                    </div>
                    <div class="form-group form-primary target-recipients form-static-label">

                    </div>
                    <div class="form-group form-primary form-static-label">
                        <select id="selectedContacts" multiple="multiple" name="selectedContacts[]" class="js-example-basic-multiple form-control form-control-inverse fill">
                            <option selected disabled>Selected contacts</option>
                        </select>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <select id="template" name="template" class="form-control form-control-inverse fill">
                            <option selected disabled>Select template</option>
                            @foreach ($templates as $template)
                                <option value="{{$template->directory}}">{{$template->template_name ?? ''}}</option>
                            @endforeach
                        </select>
                        <span class="form-bar"></span>
                        <label class="float-label">Select template</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <textarea name="compose_email" id="compose_email" class="form-control content"></textarea>
                        <span class="form-bar"></span>
                        <label class="float-label">Type message here...</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="draft" name="draft">
                        <label class="form-check-label" for="exampleCheck1">Save as draft</label>
                      </div>
                </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 mt-3 templates">
                        <img src="#" width="295" class="template-preview">
                    </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-md-12">
                    <div class="btn-group d-flex justify-content-center">
                        <a href="" class="btn btn-danger btn-mini"> <i class="ti-close mr-2"></i> Cancel</a>
                        <button type="submit"  class="btn btn-primary btn-mini save-contact"> <i class="ti-check mr-2"></i> Send</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="recipientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Add Recipient(s)</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="prospectingForm" class="form-material">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="selectAll">
                <label class="form-check-label" for="exampleCheck1">Select all</label>
            </div>
              <hr>
              <div class="row mb-3 contact-list">
                @foreach ($contacts as $cont)
                    <div class="col-md-6 col-sm-6">
                        <div class="form-check">
                            <input type="checkbox" data-contact="{{$cont->company_name ?? ''}}" class="form-check-input contact" value="{{$cont->id}}" id="{{$cont->id}}" name="{{$cont->id}}">
                            <label class="form-check-label" for="exampleCheck1">{{$cont->company_name ?? ''}}</label>
                        </div>
                    </div>
                @endforeach
              </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="btn-group d-flex justify-content-center">
                        <button class="btn btn-danger btn-mini" data-dismiss="modal"><i class="ti-close mr-2"></i> Close</button>
                        <button class="btn btn-primary btn-mini" type="button" id="confirmSelection"><i class="ti-check mr-2"></i> Submit</button>
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
<script src="/assets/js/tinymce/tinymce.min.js"></script>
<script src="/assets/js/tinymce.js"></script>
<script src="/assets/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.error-wrapper').hide();
            $('.templates').hide();
            $('.js-example-basic-multiple').select2();
            composeEmailForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/compose/email',new FormData(composeEmailForm))
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
            $("#selectAll").click(function () {
                $('.contact-list input[type="checkbox"]').prop('checked', this.checked);
            });
            $("#confirmSelection").click(function(event){
                event.preventDefault();
                var searchIDs = $(".contact-list input:checkbox:checked").map(function(){
                    return $(this).val();
                }).get();
                var name = $(".contact-list input:checkbox:checked").map(function(){
                    return $(this).data('contact');
                }).get();
                var n = 0;
                for(n = 0; n<searchIDs.length; n++){
                    $('#selectedContacts').append("<option selected value='"+searchIDs[n]+"'>"+name[n]+"</option>");
                }
            });

            $(document).on('change', '#template', function(e){
                e.preventDefault();
                var template = $(this).val();
                $('.template-preview').attr('src', '/assets/uploads/cnxdrive/'+template);
                console.log(template);
                $('.templates').show();
            });
        });
    </script>
@endsection
