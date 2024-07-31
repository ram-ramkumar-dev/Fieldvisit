<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientGroup;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = "Clients";
        //$clients = Client::with('clientgroup')->get();  
        $companyId = Session::get('company_id'); 
        $clients = Client::where('company_id', $companyId)->with('clientgroup')->get(); 
        return view('clients.index', compact('clients', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = "Clients"; 
        $states = State::all();
        $companyId = Session::get('company_id'); 
        $clientgroups = ClientGroup::where('company_id', $companyId)->get(); 
        return view('clients.create', compact('clientgroups', 'states', 'page'));
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
            'client_name' => 'required',
            'registration_no' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postcode' => 'required',
            'phone1' => 'required',
            'client_group_id' => 'nullable|exists:client_groups,id', 
        ]);

        // Get the company_id from the session
        $companyId = Session::get('company_id');
        // Merge the company_id into the request data
        $data = $request->all();
        $data['company_id'] = $companyId;

        Client::create($data);
        return redirect()->route('clients.index')->with('success', 'Client created successfully.');  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $page = "Clients";
        return view('clients.show', compact('client', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $page = "Clients"; 
        $states = State::all();
        $companyId = Session::get('company_id'); 
        $clientgroups = ClientGroup::where('company_id', $companyId)->get(); 
        return view('clients.edit', compact('client', 'clientgroups', 'states', 'page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([ 
            'client_name' => 'required',
            'registration_no' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postcode' => 'required',
            'phone1' => 'required',
            'client_group_id' => 'nullable|exists:client_groups,id',
            'status' => 'required|boolean',
        ]);

        $client->update($request->all());
        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
