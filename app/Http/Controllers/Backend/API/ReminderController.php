<?php

namespace App\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reminder;
use Auth;
class ReminderController extends Controller
{


    public function allReminders(){
        $reminder = Reminder::select('reminder_name as title', 'remind_at as start', 'active_color as color')
                    //->where('tenant_id', Auth::user()->tenant_id)
                    ->get();
        return response($reminder);
    }
}
