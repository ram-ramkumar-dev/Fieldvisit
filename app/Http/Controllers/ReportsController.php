<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchDetail;
use App\Models\Batches;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Survey;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Storage;
use ZipArchive;   
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as WorksheetDrawing;

class ReportsController extends Controller
{
    // Function to get image dimensions
    function getImageDimensions($filePath) {
        list($width, $height) = getimagesize($filePath);
        return ['width' => $width, 'height' => $height];
    }

    public function agentKpi()
    {
        $page = 'agentkpi';
        $companyId = Session::get('company_id');  
        $drivers = Driver::where('company_id', $companyId)->get();
        // Define the start and end dates for the current week
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
 
        $batchDetails = DB::table('batch_details')
            ->join('drivers', 'batch_details.assignedto', '=', 'drivers.id')
            ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
            ->where('batches.company_id',$companyId)
            ->whereBetween('batch_details.assignedon', [$startOfWeek, $endOfWeek])
            ->select(
                'drivers.id as driver_id',
                'drivers.name as driver_name',
                'batches.id as batch_id',
                'batches.batch_no',
                DB::raw('count(case when batch_details.status = "Pending" then 1 end) as pending_count'),
                DB::raw('count(case when batch_details.status = "Completed" then 1 end) as completed_count'),
                DB::raw('count(batch_details.assignedto) as assigned_count') // Count the number of times a driver is assigned
            )
            ->groupBy('drivers.id', 'drivers.name', 'batches.id', 'batches.batch_no')
            ->get();

        // Group the batch details by driver
        $groupedBatchDetails = $batchDetails->groupBy('driver_id')->map(function ($details) {
            return [
                'driver_name' => $details->first()->driver_name,
                'batches' => $details->map(function ($detail) {
                    return [
                        'batch_id' => $detail->batch_id,
                        'batch_no' => $detail->batch_no,
                        'pending_count' => $detail->pending_count,
                        'completed_count' => $detail->completed_count,
                        'assigned_count' => $detail->assigned_count,
                    ];
                }),
            ];
        }); 
         
        // Return the view with the filtered data
        return view('reports.agentkpi', compact('groupedBatchDetails', 'page', 'drivers', 'startOfWeek', 'endOfWeek'));
    }
 
    public function exportAgentKpiReports($driverId, $startDate, $endDate)
    {
            $companyId = Session::get('company_id');  
        // Query the BatchDetails with conditional driver filtering
            $query = DB::table('batch_details')
            ->join('drivers', 'batch_details.assignedto', '=', 'drivers.id')
            ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
            ->where('batches.company_id', $companyId) 
            ->whereBetween('batch_details.assignedon', [$startDate, $endDate])
            ->select(
                'drivers.id as driver_id',
                'drivers.name as driver_name',
                'batches.id as batch_id',
                'batches.batch_no',
                DB::raw('count(case when batch_details.status = "Pending" then 1 end) as pending_count'),
                DB::raw('count(case when batch_details.status = "Completed" then 1 end) as completed_count'),
                DB::raw('count(batch_details.assignedto) as assigned_count') // Count the number of times a driver is assigned
            )
            ->groupBy('drivers.id', 'drivers.name', 'batches.id', 'batches.batch_no');
            
                if ($driverId) {
                    $query->where('batch_details.assignedto', $driverId);
                }
            
                $batchDetails = $query->get();
            
            // Group the batch details by driver
            $groupedBatchDetails = $batchDetails->groupBy('driver_id')->map(function ($details) {
                return [
                    'driver_name' => $details->first()->driver_name,
                    'batches' => $details->map(function ($detail) {
                        return [
                            'batch_id' => $detail->batch_id,
                            'batch_no' => $detail->batch_no,
                            'pending_count' => $detail->pending_count,
                            'completed_count' => $detail->completed_count,
                            'assigned_count' => $detail->assigned_count,
                        ];
                    }),
                ];
            });
    
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set header row
            $sheet->setCellValue('A1', 'No');
            $sheet->setCellValue('B1', 'Agent');
            $sheet->setCellValue('C1', 'File');
            $sheet->setCellValue('D1', 'Assigned');
            $sheet->setCellValue('E1', 'Completed');
            $sheet->setCellValue('F1', 'Incomplete');
            
            $row = 2;
            $no = 1;
            $grandTotalAssigned = $grandTotalCompleted = $grandTotalIncomplete = 0;
                
            foreach ($groupedBatchDetails as $driver) {
                $totalAssigned = $totalCompleted = $totalIncomplete = 0;
                
                foreach ($driver['batches'] as $batch) {
                    $sheet->setCellValue('A' . $row, $no++);
                    $sheet->setCellValue('B' . $row, $driver['driver_name']);
                    $sheet->setCellValue('C' . $row, $batch['batch_no']);
                    $sheet->setCellValue('D' . $row, $batch['assigned_count']);
                    $sheet->setCellValue('E' . $row, $batch['completed_count']);
                    $sheet->setCellValue('F' . $row, $batch['pending_count']);
                    
                    $totalAssigned += $batch['assigned_count'];
                    $totalCompleted += $batch['completed_count'];
                    $totalIncomplete += $batch['pending_count']; 
                    $row++;
                }
                
                // Total for driver
                $sheet->setCellValue('A' . $row, 'TOTAL');
                $sheet->setCellValue('D' . $row, $totalAssigned);
                $sheet->setCellValue('E' . $row, $totalCompleted);
                $sheet->setCellValue('F' . $row, $totalIncomplete);
                $row++;
                
                $grandTotalAssigned += $totalAssigned;
                $grandTotalCompleted += $totalCompleted;
                $grandTotalIncomplete += $totalIncomplete;
            }
            
            // Grand totals
            $sheet->setCellValue('A' . $row, 'GRAND TOTAL');
            $sheet->setCellValue('D' . $row, $grandTotalAssigned);
            $sheet->setCellValue('E' . $row, $grandTotalCompleted);
            $sheet->setCellValue('F' . $row, $grandTotalIncomplete);
            
            $writer = new Xlsx($spreadsheet);
            
            // Export the file
            $fileName = 'Agent_KPI_Report_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($tempFile);
        
            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

        public function handleForm(Request $request)
    {
            $action = $request->input('action');
            $page = 'agentkpi';
            // Retrieve filter inputs
            $driverId = $request->input('driver_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if ($action === 'export') {
                return $this->exportAgentKpiReports($driverId, $startDate, $endDate);
            } else {
            // Handle the filter logic (e.g., displaying filtered results)
            $companyId = Session::get('company_id');
            $drivers = Driver::where('company_id', $companyId)->get();
            $batchDetails = DB::table('batch_details')
                ->join('drivers', 'batch_details.assignedto', '=', 'drivers.id')
                ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
                ->where('batches.company_id', $companyId) 
                ->whereBetween('batch_details.assignedon', [$startDate, $endDate])
                ->when($driverId, function ($query) use ($driverId) {
                    return $query->where('batch_details.assignedto', $driverId);
                })
                ->select(
                    'drivers.id as driver_id',
                    'drivers.name as driver_name',
                    'batches.id as batch_id',
                    'batches.batch_no',
                    DB::raw('count(case when batch_details.status = "Pending" then 1 end) as pending_count'),
                    DB::raw('count(case when batch_details.status = "Completed" then 1 end) as completed_count'),
                    DB::raw('count(batch_details.assignedto) as assigned_count') // Count the number of times a driver is assigned
                )
                ->groupBy('drivers.id', 'drivers.name', 'batches.id', 'batches.batch_no')
                ->get();

            // Group the batch details by driver
            $groupedBatchDetails = $batchDetails->groupBy('driver_id')->map(function ($details) {
                return [
                    'driver_name' => $details->first()->driver_name,
                    'batches' => $details->map(function ($detail) {
                        return [
                            'batch_id' => $detail->batch_id,
                            'batch_no' => $detail->batch_no,
                            'pending_count' => $detail->pending_count,
                            'completed_count' => $detail->completed_count,
                            'assigned_count' => $detail->assigned_count,
                        ];
                    }),
                ];
            });
            
            return view('reports.agentkpi', compact('groupedBatchDetails', 'page', 'drivers', 'startDate', 'endDate'));
        }
    } 

    public function surveyResult()
    {
        $page = "surveyresult";
        // Fetch batches and statuses for the dropdowns
        $companyId = Session::get('company_id');   
        // Fetch batches based on filter
        $batches = Batches::where('company_id', $companyId)->get();  
        $clients = Client::where('company_id', $companyId)->get();
        $agents = Driver::where('company_id', $companyId)->get();
       // $statuses = DB::table('account_statuses')->get();
       $columnDisplayNames = [
            'batch_no' => 'File Name',
            'id' => 'ID',
            'account_no' => 'Account No',
            'name' => 'Owner Name',
            'address' => 'Property Address',
            'batchfile_latitude' => 'Latitude',
            'batchfile_longitude' => 'Longitude',
            'district_la' => 'LA (District)',
            'taman_mmid' => 'MMID (Area)',
            'amount' => 'Balance',
            'occupancy' => 'Occupancy',
            'has_water_meter' => 'Has Water Meter',
            'water_meter_no' => 'Water Meter No',
            'has_water_bill' => 'Has Water Bill',
            'water_bill_no' => 'Water Bill No',
            'is_correct_address' => 'Is Correct Address',
            'correct_address' => 'Correct Address',
            'ownership' => 'Ownership',
            'contact_person_name' => 'Contact Person Name',
            'contact_number' => 'Contact Number',
            'email' => 'Email',
            'nature_of_business_code' => 'Nature of Business Code',
            'shop_name' => 'Shop Name',
            'dr_code' => 'DR Code',
            'property_code' => 'Property Code',
            'remark' => 'Remark',
            'assignedto' => 'Field Officer',
            'assignedon' => 'Assigned Date',
            'visitdate' => 'Visit Date',
            'visittime' => 'Visit Time',
            'photos' => 'Photos'
        ];
        
        $columns = array(); 
        return view('reports.survey', compact('page','batches', 'clients', 'agents', 'columns', 'columnDisplayNames'));
    }

    public function generateReport(Request $request)
    {
        $action = $request->input('action');
        $page = "surveyresult";
        $requestbatches = $request->input('batches');
        $requestclient = $request->input('client'); 
        $requestagent = $request->input('agent');
        $status = $request->input('status');
        $requestdistrict = $request->input('district');
        $columns = $request->input('columns', []); 
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $companyId = Session::get('company_id');  

        $columnDisplayNames = [
            'batch_no' => 'File Name',
            'id' => 'ID',
            'account_no' => 'Account No',
            'name' => 'Owner Name',
            'address' => 'Property Address',
            'batchfile_latitude' => 'Latitude',
            'batchfile_longitude' => 'Longitude',
            'district_la' => 'LA (District)',
            'taman_mmid' => 'MMID (Area)',
            'amount' => 'Balance',
            'occupancy' => 'Occupancy',
            'has_water_meter' => 'Has Water Meter',
            'water_meter_no' => 'Water Meter No',
            'has_water_bill' => 'Has Water Bill',
            'water_bill_no' => 'Water Bill No',
            'is_correct_address' => 'Is Correct Address',
            'correct_address' => 'Correct Address',
            'ownership' => 'Ownership',
            'contact_person_name' => 'Contact Person Name',
            'contact_number' => 'Contact Number',
            'email' => 'Email',
            'nature_of_business_code' => 'Nature of Business Code',
            'shop_name' => 'Shop Name',
            'dr_code' => 'DR Code',
            'property_code' => 'Property Code',
            'remark' => 'Remark',
            'assignedto' => 'Field Officer',
            'assignedon' => 'Assigned Date',
            'visitdate' => 'Visit Date',
            'visittime' => 'Visit Time',
            'photo1' => 'Photo 1',
            'photo2' => 'Photo 2',
            'photo3' => 'Photo 3',
            'photo4' => 'Photo 4',
            'photo5' => 'Photo 5'
        ];
       

        if (in_array('all', $columns)) {
            $columns = [
                'batch_no', 'id', 'account_no', 'name', 'address',
                'batchfile_latitude', 'batchfile_longitude', 'district_la', 'taman_mmid', 'amount',
                'occupancy', 'has_water_meter', 'water_meter_no', 'has_water_bill',
                'water_bill_no', 'is_correct_address', 'correct_address', 'ownership',
                'contact_person_name', 'contact_number', 'email', 'nature_of_business_code',
                'shop_name', 'dr_code', 'property_code', 'remark', 'assignedto',
                'assignedon', 'visitdate', 'visittime', 'photo1', 'photo2', 'photo3', 'photo4', 'photo5'
            ];
        } else {
            if (in_array('photos', $columns)) {
                $columns = array_merge($columns, ['photo1', 'photo2', 'photo3', 'photo4', 'photo5']);
                $columns = array_diff($columns, ['photos']);
            }
        }   
        // Start building the query
        $query = DB::table('batch_details')
            ->join('batches', 'batch_details.batch_id', '=', 'batches.id','left')
            ->join('surveys', 'batch_details.id', '=', 'surveys.batch_detail_id','left')
            ->join('drivers', 'drivers.id', '=', 'surveys.user_id','left')
            ->select('surveys.*', 'batches.batch_no', 'batch_details.*', 'drivers.username as assignedto')
            ->where('batches.company_id', $companyId);

        if (!empty($requestbatches)) {
            $query->where('batch_details.batch_id', $requestbatches);
        }

        if (!empty($requestclient)) {
            $query->where('batches.client_id', $requestclient);
        }

        if (!empty($requestagent)) {
            $query->where('surveys.user_id', $requestagent);
        }
        
        if (!empty($requestdistrict)) { 
            $query->where('batch_details.district_la', 'like', '%' . $requestdistrict . '%');
        }
        
        if ($startDate && $endDate) {
            $query->whereBetween('surveys.visitdate', [$startDate, $endDate]);
        } 
        
        if (!empty($status)) {
            if($status != 'All'){
                $query->where('batch_details.status', $status);
            } 
        }  
        $data = $query->get();
        
        if ($action === 'export') {
            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Add header
            $col = 1;
            foreach ($columns as $column) {
                $sheet->setCellValueByColumnAndRow($col, 1, $columnDisplayNames[$column] ?? $column);
                $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
                $col++;
            }

            // Add data
            $row = 2;
            foreach ($data as $item) {
                $col = 1;
                foreach ($columns as $column) {
                    $value = $item->$column ?? '';
                    if (in_array($column, ['photo1', 'photo2', 'photo3', 'photo4', 'photo5'])) {
                        // Handle images
                        if ($value && file_exists(public_path($value))) {
                            $filePath = public_path($value);
                            $dimensions = $this->getImageDimensions($filePath);

                            $drawing = new WorksheetDrawing();
                            $drawing->setName($column)
                                    ->setDescription($column)
                                    ->setPath($filePath)
                                    ->setCoordinates($this->getExcelCellCoordinate($col, $row)) // Convert column and row to Excel cell reference 
                                    ->setHeight(120) // Adjust height as needed
                                    ->setWorksheet($sheet);

                            // Adjust row height to fit the image
                            $rowHeight = $dimensions['height'] * 0.75; // Adjust factor if needed
                            $sheet->getRowDimension($row)->setRowHeight($rowHeight);

                            // Adjust column width based on image width
                            $colWidth = $dimensions['width'] * 0.075; // Adjust factor if needed
                            $sheet->getColumnDimensionByColumn($col)->setWidth($colWidth);
                        } else {
                            // Handle missing image case
                            $sheet->setCellValueByColumnAndRow($col, $row, '-'); // Optionally add a message
                        }
                    } else {
                        $sheet->setCellValueByColumnAndRow($col, $row, $value);
                    }
                    $col++;
                }
                $row++;
            }

            // Write to a temporary file
            $writer = new Xlsx($spreadsheet);
            $fileName = 'SurveyReport_' . now()->format('Ymd_His') . '.xlsx';
            $filePath = storage_path('app/public/' . $fileName);
            $writer->save($filePath);

            // Return response to download the file
            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
            // Default behavior to generate report view
            
            $batches = Batches::where('company_id', $companyId)->get(); 
            $clients = Client::where('company_id', $companyId)->get();
            $agents = Driver::where('company_id', $companyId)->get();
            return view('reports.survey', [
                'reportData' => $data->map(function ($item) use ($columns) {
                    $filteredItem = [];
                    foreach ($columns as $column) {
                        $filteredItem[$column] = $item->$column ?? null;
                    }
                    return (object) $filteredItem;
                }),
                'page' => $page,
                'batches' => $batches,  
                'clients' => $clients, 
                'agents' => $agents, 
                'status' => $status,
                'columns' => $columns,
                'columnDisplayNames' => $columnDisplayNames,
            ]);
        }
    }
    
    private function getExcelCellCoordinate($column, $row)
    {
        // Convert column index to Excel column letter
        $letters = '';
        while ($column > 0) {
            $column--;
            $letters = chr($column % 26 + 65) . $letters;
            $column = (int)($column / 26);
        }
        return $letters . $row;
    }
    
    public function surveyphotos()
    {
        $page = "surveyphotos";
        // Fetch batches and statuses for the dropdowns
        $companyId = Session::get('company_id');   
        // Fetch batches based on filter
        $batches = Batches::where('company_id', $companyId)->get();
       // $statuses = DB::table('account_statuses')->get(); 
        return view('reports.surveyphotos', compact('page','batches'));
    }

    public function surveyPhotosGenerate(Request $request)
    {
        $action = $request->input('action');
        $page = "surveyphotos";
        $requestbatches = $request->input('batches'); 
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $removeTimestamp = $request->input('remove_timestamp', false); // Default to false if not provided

        $companyId = Session::get('company_id');  
        //get company name 
        $companyname = DB::table('companies')  
        ->select('company_name')
        ->where('id', $companyId)->first();

        // Start building the query
        $query = DB::table('surveys') 
            ->join('batches', 'surveys.batch_id', '=', 'batches.id','left') 
            ->select('surveys.photo1','surveys.photo2', 'surveys.photo3', 'surveys.photo4', 'surveys.photo5')
            ->where('batches.company_id', $companyId);

        if (!empty($requestbatches)) {
            $query->where('surveys.batch_id', $requestbatches);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('surveys.visitdate', [$startDate, $endDate]);
        } 
         
        $data = $query->get();
        if ($action === 'export') {
            $zip = new ZipArchive();
          //  $zipFileName = 'Photos.zip';
            $zipFileName = "{$companyname->company_name}_Photos.zip";

            $zipFilePath = public_path($zipFileName);
            
           
            // Create a new ZIP file
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) { 
                foreach ($data as $item) {
                    // Process each photo
                    $photos = [$item->photo1, $item->photo2, $item->photo3, $item->photo4, $item->photo5];
                    foreach ($photos as $photo) {
                        if ($photo) {
                            $photoPath = public_path($photo);
    
                            if (file_exists($photoPath)) {
                                // Conditionally remove timestamp from the image
                                if ($removeTimestamp == '1') { // Dropdown sends value as string
                                    $photoPath = $this->removeTimestamp($photoPath);
                                }
                                $zip->addFile($photoPath, basename($photoPath));
                            }
                        }
                    }
                } 
                $zip->close();
    
                // Return the ZIP file as a response
                return response()->download($zipFilePath)->deleteFileAfterSend(true);
            } else {
                return response()->json(['error' => 'Could not create ZIP file'], 500);
            }
        } else {
            // Default behavior to generate report view
            
            $batches = Batches::where('company_id', $companyId)->get();
            return view('reports.surveyphotos', [
                'reportData' => $data,
                'page' => $page,
                'batches' => $batches,  
            ]);
        }
    }    

    private function removeTimestamp($photoPath)
    {
        $ext = pathinfo($photoPath, PATHINFO_EXTENSION);
        $image = null;

        // Create image resource based on the file type
        switch (strtolower($ext)) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($photoPath);
                break;
            case 'png':
                $image = imagecreatefrompng($photoPath);
                break;
            case 'gif':
                $image = imagecreatefromgif($photoPath);
                break;
            default:
                return $photoPath; // Unsupported format
        }

        // Define the area to cover (bottom of the image)
        $width = imagesx($image);
        $height = imagesy($image);
        $x = 0;
        $y = $height - 100; // Adjust height according to the area to cover
        $rectWidth = $width;
        $rectHeight = 100; // Height of the cover area

        // Define the color of the rectangle (white in this case)
        $color = imagecolorallocate($image, 0, 0, 0);

        // Draw the rectangle to cover the timestamp
        imagefilledrectangle($image, $x, $y, $x + $rectWidth, $y + $rectHeight, $color);

        // Save the image with the timestamp removed
        $newPhotoPath = pathinfo($photoPath, PATHINFO_FILENAME) . '_no_timestamp.' . $ext;

        switch (strtolower($ext)) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($image, $newPhotoPath);
                break;
            case 'png':
                imagepng($image, $newPhotoPath);
                break;
            case 'gif':
                imagegif($image, $newPhotoPath);
                break;
        }

        // Free up memory
        imagedestroy($image);

        return $newPhotoPath;
    }
}