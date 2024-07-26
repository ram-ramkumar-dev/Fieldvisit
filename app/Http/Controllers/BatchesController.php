<?php

namespace App\Http\Controllers;

use App\Models\Batches;
use App\Models\BatchDetail;
use App\Models\Client;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage; 
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                        ->withCount('batchDetails')
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

      
    public function import()
    {
        $page = "ImportBatch"; 
        $companyId = Session::get('company_id');  
        $batches = Batches::where('company_id', $companyId)
                        ->orWhereNull('company_id')
                        ->withCount('batchDetails')
                        ->with('client')->get(); 
        return view('batches.import', compact('batches', 'page'));
    }
    public function viewUploaded(Batches $batch)
    {
        $page = "ImportBatch"; 
        $companyId = Session::get('company_id');   
        $batchedetail = BatchDetail::where('batch_id', $batch->id)->get(); 
         
        return view('batches.viewuploaded', compact('batch', 'batchedetail', 'page'));
    }

    public function uploadBatchDetails(Request $request, $id)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        // Store the uploaded file
        $file = $request->file('file');
        $path = $file->storeAs('uploads', 'batch_' . $id . '.xlsx');
        $filePath = Storage::path($path);

        try {
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Define expected headers
            $headerRow = $rows[0];
            $expectedHeaders = ['ID', 'Name', 'IC no', 'Account No', 'bill no', 'amount', 'Address', 'District (LA)', 'Taman (MMID)', 'Roadname', 'State', 'Post Code', 'Building', 'Building ID', 'MailName', 'Mail Add'];

            // Check for header row mismatch
            if ($headerRow !== $expectedHeaders) {
                return redirect()->back()->with('error', 'Excel file columns do not match the expected format.');
            }

            // Process each row
            $errors = [];
            foreach ($rows as $i => $row) {
                if ($i === 0) continue; // Skip header row

                // Validate row data
                if ($this->validateRow($row)) {
                    // Insert valid row data into the database
                    BatchDetail::create([
                        'batch_id' => $id,
                        'id' => $row[0],
                        'name' => $row[1],
                        'ic_no' => $row[2],
                        'account_no' => $row[3],
                        'bill_no' => $row[4],
                        'amount' => $row[5],
                        'address' => $row[6],
                        'district_la' => $row[7],
                        'taman_mmid' => $row[8],
                        'roadname' => $row[9],
                        'state' => $row[10],
                        'post_code' => $row[11],
                        'building' => $row[12],
                        'building_id' => $row[13],
                        'mail_name' => $row[14],
                        'mail_add' => $row[15],
                    ]);
                } else {
                    $errors[] = "Row " . ($i + 1) . " has invalid data.";
                }
            }

            if (empty($errors)) {
                return redirect()->back()->with('success', 'Batch details uploaded successfully.');
            } else {
                return redirect()->back()->with('error', implode(' ', $errors));
            }
        } catch (\Exception $e) {
            // Handle exceptions and provide a generic error message
            return redirect()->back()->with('error', 'An error occurred while uploading the file.');
        }
    }

    private function validateRow($row)
    {
        return !empty($row[0]) && !empty($row[1]) && !empty($row[2]) && 
               !empty($row[3]) && !empty($row[4]) && !empty($row[5]) && 
               !empty($row[6]) && !empty($row[7]) && !empty($row[8]) && 
               !empty($row[9]) && !empty($row[10]) && !empty($row[11]) && 
               !empty($row[12]) && !empty($row[13]) && !empty($row[14]) && 
               !empty($row[15]);
    }
}
