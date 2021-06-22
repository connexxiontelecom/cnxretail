<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\ActivityLog;
use Auth;

class AppointmentController extends Controller
{
   public function __construct(){
       $this->middleware('auth');
       $this->activitylog = new ActivityLog();
   }

   public function appointments(){
       $appointments = Appointment::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
       $this->activitylog->setNewActivityLog('Appointments', 'Viewed appointments');
       return view('appointments.index', ['appointments'=>$appointments]);
   }

   public function addNewAppointment(){
    $log = new ActivityLog;
    $log->subject = "Add new appointment";
    $log->tenant_id = Auth::user()->tenant_id;
    $log->log = Auth::user()->full_name." open add new appointment view";
    $log->save();
       return view('appointments.create');
   }
}
