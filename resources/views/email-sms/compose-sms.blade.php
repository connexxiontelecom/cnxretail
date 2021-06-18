@extends('layouts.master-layout')

@section('title')
    Compose SMS
@endsection
@section('page-name')
Compose SMS
@endsection
@section('page-description')
Compose SMS
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Compose SMS</a>
</li>
@endsection

@section('page-heading')
Compose SMS
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        @if(session()->has('error'))
            <div class="alert alert-warning" role="alert">
                {!! session()->get('error') !!}
            </div>
        @endif
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
                    <div class="card-header mb-4">
                        <h5>Compose SMS</h5>
                    </div>
                    <div class="form-group form-primary form-static-label col-md-6 col-sm-6">
                        <input type="text" name="sender_id" class="form-control" value="{{old('sender_id')}}">
                        <span class="form-bar"></span>
                        <label class="float-label">Sender ID</label>
                        @error('sender_id')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <label class="label label-primary" data-target="#groupModal" data-toggle="modal"><i style="cursor: pointer;" class="ti-plus mr-2"></i>Add Contact(s)</label>
                    </div>
                    <div class="form-group form-primary form-static-label col-md-6 col-sm-6">
                        <select id="selectedContacts" multiple="multiple" name="selectedContacts[]" value="{{old('selectedContacts[]')}}" class="js-example-basic-multiple form-control form-control-inverse fill">
                            <option selected disabled>Selected contacts</option>
                        </select>
                    </div>
                    <div class="form-group form-primary form-static-label col-md-6 col-sm-6">
                        <select id="phone_groups" multiple="multiple" value="{{old('phone_groups')}}" name="phone_groups[]" class="js-example-basic-multiple form-control form-control-inverse fill">
                            <option selected disabled>Select Phone Group</option>
                            @foreach ($phonegroups as $group)
                                <option value="{{$group->id}}">{{$group->group_name ?? ''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group form-primary form-static-label col-md-6 col-sm-6">
                        <textarea maxlength="480" name="compose_sms" id="compose_sms" style="height: 100px;" class="form-control content">{{old('compose_sms')}}</textarea>
                        <span class="form-bar"></span>
                        <label class="float-label">Type message here...</label>
                        @error('compose_sms')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 float-right d-flex justify-content-end">
                            <label for=""><small><span class="pages">Page: 1</span> <span class="characters">Characters: 0</span></small></label>
                        </div>
                    </div>
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


<div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Select contact</h6>
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
                            <input type="checkbox" data-contact="{{$cont->company_name ?? ''}}" class="form-check-input contact" value="{{$cont->company_phone}}" id="{{$cont->company_phone}}" name="{{$cont->id}}">
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
<script src="/assets/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            var charCount = 0;
            var pageCount = 0;
            $('.error-wrapper').hide();
            $('.js-example-basic-multiple').select2();
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

            $(document).on('keyup', '#compose_sms', function(e){
                e.preventDefault();
                $('.characters').text("Characters: "+charCount);
                if(charCount <= 160 ){
                   pageCount = 1;
                }else if(charCount >= 161 && charCount <= 313){
                    pageCount = 2;
                }else if(charCount >= 314 && charCount <= 466){
                   pageCount = 3;
                }
                $('.pages').text("Page: "+pageCount);
                 charCount++;

            });
           /*  composeSmsForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/compose-sms',new FormData(composeSmsForm))
                .then(response=>{
                    Toastify({
                        text: "Success! SMS sent.",
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
            }; */
            $(document).on('click', '.close-errors', function(e){
                e.preventDefault();
                $('.error-wrapper').hide();
            });
        });
    </script>
@endsection
