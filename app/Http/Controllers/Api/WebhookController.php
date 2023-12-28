<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    
    public function store(Request $request){
        $value = $request->header('secret');

        if(empty($value)){
            return response()->json([
                'status'=> 'error',
                'message'=> 'secret not found'
            ]);
        }

        if($value !== env('SECRET')){
            return response()->json([
                'status'=> 'error',
                'message'=> 'secret not valid'
            ], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'phone_number'=> 'required',
            'message'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if(empty(env('TOKEN_WHATSAUTH'))){
            return response()->json([
                'status'=> 'error',
                'message'=> 'token is empty'
            ], 400);
        }

        $msg = array(
            'to' => $request->phone_number,
            'isgroup' => false,
            'messages' => $request->message,
        );
        // JSON encode the data.
        $jsonData = json_encode($msg);
        // The URL to send the POST request to.
        $url= 'https://api.wa.my.id/api/send/message/text';
      
        $ch = curl_init($url);
        // Set cURL options.
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'Content-Type: application/json',
            'token: ' . env('TOKEN_WHATSAUTH')
        ));
        
        // Execute the POST request.
        $response = curl_exec($ch);
        
        // Check for cURL errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        
        // Close cURL session.
        curl_close($ch);
        // If there was an error, handle it here.
        if (isset($error_msg)) {
            // Log or echo the error message.
            echo "cURL Error: " . $error_msg;
            return response()->json([
                "status"=> "error",
                "message"=> "cURL Error: " .$error_msg
            ],500);
        }
        
        // Do something with the response.
        return response()->json([
            "status"=> "success",
            "message"=> "success to send message",
            "data" => $response,
        ], 200);        
    }
}
