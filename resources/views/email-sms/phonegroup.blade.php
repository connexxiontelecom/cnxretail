@extends('layouts.master-layout')

@section('title')
    Phone Group
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/select2.min.css">
@endsection
@section('page-name')
Phone Group
@endsection
@section('page-description')
Phone Group
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Phone Group</a>
</li>
@endsection

@section('page-heading')
Phone Group
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <div class="row mb-3">
            <div class="col-md-12 col-sm-12 ">
                <a  href="{{route('compose-sms')}}" class="btn btn-mini btn-primary"><i class="ti-plus mr-2"></i>Compose SMS</a>
                <button  type="button" class="btn btn-mini btn-primary balance"><i class="ti-plus mr-2"></i>Balance</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <form class="form-material" id="phoneGroupForm">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                            <ul id="validation-errors">
                            </ul>
                        </div>
                    </div>
                    <div class="card-header mb-4">
                        <h5>Create Phone Group</h5>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="group_name" id="group_name" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Group Name</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <label class="label label-primary" data-target="#groupModal" data-toggle="modal"><i style="cursor: pointer;" class="ti-plus mr-2"></i>Add Contact(s)</label>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <select id="selectedContacts" multiple="multiple" name="selectedContacts[]" class="js-example-basic-multiple form-control form-control-inverse fill">
                            <option selected disabled>Selected contacts</option>
                        </select>
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
            <div class="col-sm-8 col-md-8">
                <div class="dt-responsive table-responsive">
                    <table  class="table table-striped table-bordered nowrap simpletable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Group Name</th>
                            <th>Contacts</th>
                        </tr>
                        </thead>
                        @php
                            $serial = 1;
                        @endphp
                        <tbody>
                            @foreach ($phonegroups as $group)
                                <tr><td>{{$serial++}}</td>
                                    <td><a href="{{route('update-phonegroup', $group->slug)}}">{{$group->group_name ?? ''}}</a></td>
                                    <td>{{$group->phone_numbers ?? ''}}</td>
                                </tr>
                            @endforeach
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
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
<script src="/assets/js/datatable.min.js"></script>
<script src="/assets/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();
        $('.js-example-basic-multiple').select2();
        $(document).on('click', '.balance', function(e){
            e.preventDefault();
            axios.post('/sms/balance')
            .then(response=>{
                console.log(response.data);
            })
            .catch(error=>{
                console.log(error.response.errors);
            });
        });

        phoneGroupForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/phonegroup',new FormData(phoneGroupForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Phone Group created",
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
    });
</script>
@endsection
