<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\BBNSMSGatewayTraits;
use App\Models\ActivityLog;
use Redirect;
class BBNSMSCallsController extends Controller
{
    use BBNSMSGatewayTraits;

    public function __construct(){
        $this->middleware('auth');
    }


    public function getBalance(){
        if($this->tryLogin(getenv('BBNSMS_EMAIL'), getenv('BBNSMS_PASSWORD'))){
            $balance = $this->checkBalance(getenv('BBNSMS_EMAIL'), getenv('BBNSMS_PASSWORD'));
            return response()->json(['balance'=>$balance],200);
        }else{
            return response()->json(['error'=>'Authentication failed. Try again.']);
        }
    }
    public function sendMessage(){
        $mobile = "+2348032404359";
        $name = "Joseph";

        $message = "CNX Retail Bulk SMS test ";
        $createdURL = "";
        $ozSURL = "http://www.bbnsms.com/bulksms/bulksms.php";
        $ozUser = "talktojoegee@gmail.com";
        $ozPassw = "Lordofmylife123";
        $ozMessageType = "CNXGRP";
        $ozRecipient = $mobile;
        $ozMessageData = $message;

        $createdURL = $ozSURL."?username=".trim($ozUser)."&password=".trim($ozPassw)."&sender=".trim($ozMessageType)."&mobile=".trim($ozRecipient)."&message=".$ozMessageData;

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $createdURL);
        #Register log
        $log = new ActivityLog;
        $log->subject = "Send Bulk SMS";
        $log->tenant_id = Auth::user()->tenant_id;
        $log->log = Auth::user()->full_name." sent bulk SMS";
        $log->save();
        return response()->json(['message'=>$response]);
    }

}
