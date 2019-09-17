<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Site;
use Illuminate\Http\Request;

class ContactController extends Controller
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
        return view('contacts');
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
            'contact_name' => 'required',
            'contact_number' => 'required',
            'site_id' => 'required'
        ]);

        $contact = new Contact();

        $contact->contact_name   = $request->contact_name;
        $contact->contact_number = $request->contact_number;
        $contact->site_id        = $request->site_id;

        if($contact->save()){
            return response()->json([
                'error' => false,
                'contact' => $contact,
                'message' => 'Contact saved successfully!'
            ]);
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Could not save contact. Try Again!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }

    public function getSiteContacts(Site $site){
        return response()->json($site->contacts()->get());
    }
}
