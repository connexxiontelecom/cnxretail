@extends('layouts.master-layout')

@section('title')
    Email Templates
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Email Templates
@endsection
@section('page-description')
Email Templates
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Email Templates</a>
</li>
@endsection

@section('page-heading')
Email Templates
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <button class="btn-primary btn btn-mini mb-4" type="button" data-toggle="modal" data-target="#emailTemplateModal"><i class="ti-plus mr-2"></i>Add New Template</button>
            @php
                $s = 1;
            @endphp
        <div class="row">
            @foreach ($templates as $template)
                <div class="col-md-3 col-sm-3">
                    <label for="" class="badge badge-danger">{{$s++}}</label>
                    <img src="/assets/uploads/cnxdrive/{{$template->directory}}" width="295" alt="{{$template->template_name ?? ''}}">
                    <p style="border-left-color: red solid;">{{$template->template_name ?? ''}}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>


<div class="modal fade" id="emailTemplateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Add New Template</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="emailTemplateForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <input type="text" name="template_name" placeholder="Template Name" id="template_name" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">Template Name</label>
            </div>
            <div class="form-group form-primary form-static-label">
                <input type="file" name="template" id="template" class="form-control-file">
            </div>
              <hr>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="btn-group d-flex justify-content-center">
                        <button class="btn btn-danger btn-mini" data-dismiss="modal"><i class="ti-close mr-2"></i> Close</button>
                        <button class="btn btn-primary btn-mini" type="submit" id="confirmSelection"><i class="ti-check mr-2"></i> Submit</button>
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
<script src="/assets/js/datatable.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();

        emailTemplateForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/add-new-email-template',new FormData(emailTemplateForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Template saved.",
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
    });
</script>
@endsection
