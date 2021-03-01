<?php

namespace App\Http\Controllers;

use App\Report;
use App\Client;
use App\Site;

use PDF;

use Mail;
use Storage;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $reports = Report::with('client')->paginate(18);
        return view('view-reports')->with('reports', $reports);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create-report');
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
            'template' => 'required'
        ]);

        $report = new Report();

        $report->client_id = $request->client_id;
        $report->template  = $request->template;
        $report->fileName  = md5($request->template.microtime());

        if($report->save()){
            return response()->json([
                'error' => false,
                'data' => $report,
                'message' => 'Report Created Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error creating report'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        $report = new Report();

        return view('edit-report')->with('report', $report);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        $request->validate([
            'client_id' => 'required',
            'template' => 'required'
        ]);

        $report->client_id = $request->client_id;
        $report->template = $request->template;

        if($report->update()){
            return response()->json([
                'error' => false,
                'data' => $report,
                'message' => 'Report Updated Successfully'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error updating report'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }

    /**
     * -------------------------------------------
     * Present a page for send reports to clients
     * -------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return view
     */
    public function send(Request $request){
        if($request->client != null){
            $current_client = Client::where('id', $request->client)->first();
        }else{
            $current_client = null;
        }
        $clients = Client::all();

        return view('reports')->with('clients', $clients)->with('current_client', $current_client);
    }

    /**
     * ----------------------------------------
     * Generate report for a particular client
     * -----------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request){
        $request->validate([
            'client_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'email' => 'required'
        ]);

        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));
        
        $client = Client::where('id', $request->client_id)->first();
        
        $sites = Site::where('client_id', $request->client_id)->with(['attendances'=>function($q) use ($start_date, $end_date){
            $q->whereRaw("DATE(date_time) BETWEEN DATE('$start_date') AND DATE('$end_date') AND type = 1");
        }])->with(['incidents' => function($q) use ($start_date, $end_date){
            $q->whereRaw("DATE(created_at) BETWEEN DATE('$start_date') AND DATE('$end_date')");
        }])->with(['occurrences' => function($q) use ($start_date, $end_date){
            $q->whereRaw("DATE(created_at) BETWEEN DATE('$start_date') AND DATE('$end_date')");
        }])->get();
        

        foreach($sites as $site){
            $sorted = array();
            foreach($site->attendances as $attendance){
                $sorted[date('Y-m-d', strtotime($attendance->date_time))][] = $attendance;
            }
            $site->sorted = $sorted;
        }
        

        $client->sites = $sites;

        
        
        $incidents = $request->incidents;
        
        $pdf = PDF::loadView('report_template', compact('client', 'start_date', 'end_date', 'incidents'));
        $filename = microtime().'-'.$client->name.'['.$start_date.'].pdf';
        $link = storage_path().'/docs'.'/'.$filename;
        $pdf->save($link);
        $pdf->stream($filename);

        $report = new Report();
        $report->client_id = $client->id;
        $report->template = $filename;
        $report->fileName = md5($filename.microtime());

        if($report->save()){
            return response()->json([
                'error' => false,
                'message' => 'Report saved',
                'data' => $report
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not save the report'
            ]);
        }
    }

    /**
     * --------------------------
     * Download files for report
     * --------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function download(Request $request)
    {
        $report = Report::where('fileName', $request->file)->first();
        if($report != null){
            return response()->download(storage_path("docs/".$report->template));
        }else{
            return abort(403);
        }
    }

    /**
     * -------------------------------------------
     * Send generated reports to client via email
     * -------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMail(Request $request){
        $request->validate([
            'client_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'email' => 'required',
            'report' => 'required'
        ]);

        $report = Report::where('id', $request->report)->first();
        $client = Client::where('id', $request->client_id)->first();

        $data = array('start_date' => $request->start_date, 'end_date' => $request->end_date, 'link' => $report->fileName);
        

        $to_name = $client->name;
        $to_email = $request->email;

        Mail::send('email_templates.basic', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('Scheduled Report');
            $message->from('noreply@offinsecuritygh.com','Offin Security');
        });


        if(count(Mail::failures()) > 0){
            return response()->json([
                'error' => true,
                'message' => 'Could not send the mail'
            ]);
        }else{
            return response()->json([
                'error' => false,
                'message' => 'Report sent successfully'
            ]);
        }
    }
    
    /**
     * ------------------------------------------
     * Get the download code for specific files
     * ------------------------------------------
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDownloadLink(Request $request){
        $request->validate([
            "file" => "required"
        ]);

        $file = urldecode($request->file);
        $report = Report::where('template', $file)->first();
        if($report == null){
            return response()->json([
                'error' => true, 
                'message' => "Invalid download link"
            ]);
        }
        
    }
}
