<?php

namespace App\Http\Controllers;

use App\Access_Code;
use App\Client;
use Mail;
use Illuminate\Http\Request;

class AccessCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create-access_code');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
        ]);

        $date = date('Y-m-d');
        
        if(Access_Code::whereRaw("client_id='$request->client_id' AND DATE(expires_at) <= '$date'")->get()->count() > 0){
            return response()->json([
                'error' => true,
                'message' => "A valid access token exists for this client"
            ]);
        }

        $access_code = new Access_Code();

        $access_code->client_id = $request->client_id;
        $access_code->access_code = $this->generateCode(24);

        $client = Client::findOrFail($request->client_id);
        $access_code->expires_at = date('Y-m-d', strtotime($client->end_date));

        if($access_code->save()){
            return response()->json([
                'error' => false,
                'data' => $access_code,
                'message' => 'Access code generated successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error generating access code'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Access_Code  $access_Code
     * @return \Illuminate\Http\Response
     */
    public function show(Access_Code $access_Code)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Access_Code  $access_Code
     * @return \Illuminate\Http\Response
     */
    public function edit(Access_Code $access_Code)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Access_Code  $access_Code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Access_Code $access_Code)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Access_Code  $access_Code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Access_Code $access_Code)
    {
        //
    }

    public function generateCode($length)
    {
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $strcode   = strlen($str)-1;
        $id  = '';

        for ($i = 0; $i < $length; $i++) {
                $id .= $str[mt_rand(0, $strcode)]; 
        }

        return $id;
    }

    public function sendToken(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'email' => 'required',
        ]);

        $date = date('Y-m-d');

        $access = Access_Code::with('client')->whereRaw("client_id='$request->client_id' && DATE(expires_at) >= '$date'")->first();
        
        $data = array('access_code' => $access->access_code);

        $to_name = $access->client->name;
        $to_email = $request->email;

        Mail::send('email_templates.token', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('Client Access');
            $message->from('noreply@offinsecuritygh.com','Offin Security');
        });

        if(count(Mail::failures()) > 0){
            return response()->json([
                'error' => true,
                'message' => 'Could not send the mail'
            ]);
        }else {
            return response()->json([
                'error' => false,
                'message' => 'Client URL sent successfully'
            ]);
        }
    }

    public function resetCode(Request $request)
    {
        $request->validate([
            'client_id' => 'required'
        ]);

        $date = date('Y-m-d');

        $access = Access_Code::whereRaw("client_id='$request->client_id' && DATE(expires_at) >= '$date'")->first();

        $access->access_code = $this->generateCode(24);

        if($access->save()){
            return response()->json([
                'error' => false,
                'message' => 'Access Code reset successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not reset access code'
            ]);
        }
    }
}
