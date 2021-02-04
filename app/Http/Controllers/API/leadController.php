<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;

class leadController extends Controller
{

    public function storeConversation(Request $request){
        $this->validate($request,[
            'subject'=>'required',
            'conversation'=>'required'
        ]);


        $conversation = new Conversation;
        $conversation->user_id = $request->user;
        $conversation->tenant_id = $request->tenant;
        $conversation->contact_id = $request->contact;
        $conversation->subject = $request->subject;
        $conversation->conversation = $request->conversation;
        $conversation->save();
        return response()->json(['response'=>'success'], 201);
    }

}
