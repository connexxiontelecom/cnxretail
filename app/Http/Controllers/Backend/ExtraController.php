<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reminder;
use Auth;

class ExtraController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


    public function reminders(){
        return view('extra.reminders');
    }

    public function storeReminder(Request $request){
        $this->validate($request,[
            'reminder_name'=>'required',
            'date_time'=>'required',
            'priority'=>'required'
        ]);

        $reminder = new Reminder;
        $reminder->reminder_name = $request->reminder_name;
        $reminder->set_by = Auth::user()->id;
        $reminder->tenant_id = Auth::user()->tenant_id;
        $reminder->note = $request->note;
        $reminder->remind_at = $request->date_time;
        $reminder->priority = $request->priority;
        $reminder->active_color = $request->priority == 1 ? '#FFC400' : '#FF0000';
        $reminder->save();
        return response()->json(['message'=>'Success! Reminder set.'], 201);
    }

    public function reminderListview(){
        $reminders = Reminder::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('extra.reminder-listview', ['reminders'=>$reminders]);
    }
}
