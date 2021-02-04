<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reminder;

class reminderController extends Controller
{
    public function setReminder(Request $request){
        $this->validate($request,[
            "title"=>'required',
            "date"=>'required',
            "note"=>'required',
            "time"=>'required',
            "tenant"=>'required',
            "user"=>'required',
        ]);

        $reminder = new Reminder;
        $reminder->reminder_name = $request->title;
        $reminder->set_by = $request->user;
        $reminder->tenant_id = $request->tenant;
        $reminder->note = $request->note;
        $reminder->remind_at =  date("y-m-d H:i:s", strtotime($request->date));
        $reminder->priority = 1;//$request->priority;
        $reminder->active_color = $request->priority == 1 ? '#FFC400' : '#FF0000';

        $reminder->save();

        return response()->json(['response'=>'success']);
    }
}
