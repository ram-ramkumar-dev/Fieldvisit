<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StatusController extends Controller
{
    public function index()
    {
        $page = "Status"; 
        $companyId = Session::get('company_id');  
        $statuses = Status::where('company_id', $companyId)
                        ->orWhereNull('company_id')
                        ->get(); 
        return view('statuses.index', compact('statuses', 'page'));
    }

    public function create()
    {   
        $page = "Status";
        return view('statuses.create',  compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'statuscode' => 'required'
        ]);
        // Get the company_id from the session
        $companyId = Session::get('company_id');
        // Merge the company_id into the request data
        $data = $request->all();
        $data['company_id'] = $companyId;
         
        Status::create($data);  
        return redirect()->route('statuses.index')->with('success', 'Status created successfully.');
    }

    public function show(Status $status)
    {
        $page = "Status";
        return view('statuses.show', compact('status', 'page'));
    }

    public function edit(Status $status)
    {
        $page = "Status";
        return view('statuses.edit', compact('status', 'page'));
    }

    public function update(Request $request, Status $status)
    {
        $request->validate([
            'statuscode' => 'required'
        ]);

        $status->update($request->all());
        return redirect()->route('statuses.index')->with('success', 'Status updated successfully.');
    }

    public function destroy(Status $status)
    {
        $status->delete();
        return redirect()->route('statuses.index')->with('success', 'Status deleted successfully.');
    }
}
