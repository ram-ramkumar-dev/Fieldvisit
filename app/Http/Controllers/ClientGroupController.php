<?php

namespace App\Http\Controllers;

use App\Models\ClientGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = "Clientgroups";
        $companyId = Session::get('company_id'); 
        $clientgroups = ClientGroup::where('company_id', $companyId)->get(); 
        return view('clientgroups.index', compact('clientgroups', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = "Clientgroups";
        return view('clientgroups.create', compact('page'));
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
            'name' => 'required', 
        ]);
        // Get the company_id from the session
        $companyId = Session::get('company_id');
        // Merge the company_id into the request data
        $data = $request->all();
        $data['company_id'] = $companyId;

        ClientGroup::create($data); 
        return redirect()->route('clientgroups.index')->with('success', 'Client group created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ClientGroup $clientgroup)
    {
        $page = "Clientgroups";
        return view('clientgroups.show', compact('clientgroup', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientGroup $clientgroup)
    {
        $page = "Clientgroups";
        return view('clientgroups.edit', compact('clientgroup', 'page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientGroup $clientgroup)
    {
        $request->validate([
            'name' => 'required', 
        ]);

        $clientgroup->update($request->all());
        return redirect()->route('clientgroups.index')->with('success', 'Client group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientGroup $clientgroup)
    {
        $clientgroup->delete();
        return redirect()->route('clientgroups.index')->with('success', 'Client group deleted successfully.');
    }
}
