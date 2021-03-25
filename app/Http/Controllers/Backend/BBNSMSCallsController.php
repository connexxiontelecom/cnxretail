<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\BBNSMSGatewayTraits;
use App\Models\ActivityLog;
use Redirect;
use Auth;
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
        $termiiApi = "TLpMHRqPFlfmSJPxYONy6qCSs94qkaP3oocjtREGoUq7bAneOm6UEo01mzNdJm";
        $data = array("to" => "2347880234567","from" => "retail",
        "sms"=>"Hi there, testing Termii","type" => "plain","channel" => "generic","api_key" => $termiiApi);

      /*   $name = "Joseph";

        $message = "CNX Retail Bulk SMS test ";
        $createdURL = "";
        $ozSURL = "http://www.bbnsms.com/bulksms/bulksms.php";
        $ozUser = "talktojoegee@gmail.com";
        $ozPassw = "Lordofmylife123";
        $ozMessageType = "CNXGRP";
        $ozRecipient = $mobile;
        $ozMessageData = $message;

        $createdURL = $ozSURL."?username=".trim($ozUser)."&password=".trim($ozPassw)."&sender=".trim($ozMessageType)."&mobile=".trim($ozRecipient)."&message=".$ozMessageData;
 */
        /* $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $createdURL); */
        /* $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $data); */
        $curl = curl_init();
        $data = array("to" => "2348032404359","from" => "Retail",
        "sms"=>"Hi there, testing Termii","type" => "plain","channel" => "generic","api_key" => $termiiApi);
        $post_data = json_encode($data);
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://termii.com/api/sms/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;


        #Register log
        $log = new ActivityLog;
        $log->subject = "Send Bulk SMS";
        $log->tenant_id = Auth::user()->tenant_id;
        $log->log = Auth::user()->full_name." sent bulk SMS";
        $log->save();
        return response()->json(['message'=>$response]);
    }

}
