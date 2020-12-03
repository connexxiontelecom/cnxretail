@extends('layouts.master-layout')

@section('title')
    Reminders
@endsection
@section('extra-styles')
<link rel="stylesheet" type="text/css" href="{{asset('/assets/fullcalendar/fullcalendar.min.css')}}">
<style>
    .fc .fc-view-container .fc-view table .fc-body .fc-widget-content .fc-day-grid-container .fc-day-grid .fc-row .fc-content-skeleton table .fc-event-container .fc-day-grid-event.fc-event{
        padding: 9px 16px;
        border-radius: 20px 20px 20px 0px;
}
.fc-title{
color:white !important;
}
.fc-time{
color: white !important;
}

.nav-pills .nav-link.active, .nav-pills .show > .nav-link{
    background: #9DCB5C !important;
}
.nav-pills .nav-link{
    border-radius: 0px !important;
}
.dropdown-menu{
    border:none !important;
}
</style>
@endsection
@section('page-name')
Reminders
@endsection
@section('page-description')
Reminders
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Reminders</a>
</li>
@endsection

@section('page-heading')
Reminders
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <button class="btn btn-mini btn-primary mb-4" data-toggle="modal" data-target="#reminderModal"><i class="ti-plus mr-2"></i> Set Reminder</button>
        <a class="btn btn-mini btn-warning text-white mb-4" href="{{route('reminder-listview')}}" ><i class="ti-menu mr-2"></i> Reminder List</a>
        <div class="row">
            <div class="col-xl-8 col-md-8 offset-md-2 offset-xl-2">
                <div id='fullcalendar'></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reminderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Set Reminder</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="reminderForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <input type="text" name="reminder_name" placeholder="Reminder Name" id="reminder_name" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">Reminder Name</label>
            </div>
            <div class="form-group form-primary form-static-label">
                <input type="datetime-local" name="date_time" id="date_time" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">Date & Time</label>
            </div>
            <div class="form-group form-primary form-static-label">
                <textarea name="note" id="note" class="form-control" placeholder="Note"></textarea>
                <span class="form-bar"></span>
                <label class="float-label">Note</label>
            </div>
            <div class="form-group form-primary form-static-label">
                <select name="priority" id="priority" class="form-control col-md-4">
                    <option disabled selected>Select priority</option>
                    <option value="1">Low</option>
                    <option value="2">High</option>
                </select>
                <span class="form-bar"></span>
                <label class="float-label">Priority</label>
            </div>
              <hr>
              <div class="row mb-3 contact-list">
              </div>
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
<script type="text/javascript" src="{{asset('/assets/moment/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/assets/fullcalendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('/assets/js/taskCalendar.js')}}"></script>
<script>

    $(document).ready(function(){
        reminderForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/new-reminder',new FormData(reminderForm))
                .then(response=>{
                    Toastify({
                        text: "Success! Reminder set.",
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
            };
    });
</script>
@endsection
