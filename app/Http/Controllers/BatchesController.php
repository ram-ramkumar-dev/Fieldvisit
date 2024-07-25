<?php

namespace App\Http\Controllers;

use App\Models\Batches;
use App\Models\Client;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = "Batches"; 
        $companyId = Session::get('company_id');  
        $batches = Batches::where('company_id', $companyId)
                        ->orWhereNull('company_id')
                        ->with('client')->get(); 
        return view('batches.index', compact('batches', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $page = "Batches"; 
        $companyId = Session::get('company_id'); 
        $clients = Client::where('company_id', $companyId)->get();   
        $status = Status::where('company_id', $companyId)
                            ->orWhereNull('company_id')
                            ->get(); 
        return view('batches.create',  compact('page', 'clients', 'status'));
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
            'batch_no' => 'required',
            'status_code' => 'required', 
        ]); 
        // Get the company_id from the session
        $companyId = Session::get('company_id');
        // Merge the company_id into the request data
        $data = $request->all();
        $data['company_id'] = $companyId;
         
        Batches::create($data);  
        return redirect()->route('batches.index')->with('success', 'Batch created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Batches $batch)
    {
        $page = "Batches";
        return view('batches.show', compact('batch', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Batches $batch)
    {
        $page = "Batches"; 
        $companyId = Session::get('company_id'); 
        $clients = Client::where('company_id', $companyId)->get();
        $status = Status::where('company_id', $companyId)
                        ->orWhereNull('company_id')
                        ->get();  
        return view('batches.edit', compact('batch', 'status', 'clients', 'page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batches $batch)
    {
        $request->validate([ 
            'batch_no' => 'required',
            'status_code' => 'required', 
        ]);

        $batch->update($request->all());
        return redirect()->route('batches.index')->with('success', 'Batch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batches $batch)
    {
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully.');
    }
}
