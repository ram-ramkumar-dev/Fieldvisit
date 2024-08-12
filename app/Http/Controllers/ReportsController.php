<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchDetail;
use App\Models\Batches;
use App\Models\Driver;
use App\Models\Survey;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ReportsController extends Controller
{
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
                DB::raw('count(case when batch_details.status = "Aborted" then 1 end) as aborted_count')
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
                        'aborted_count' => $detail->aborted_count,
                    ];
                }),
            ];
        }); 
         
        // Return the view with the filtered data
        return view('reports.agentkpi', compact('groupedBatchDetails', 'page', 'drivers', 'startOfWeek', 'endOfWeek'));
    }
 
    public function exportAgentKpiReports($driverId, $startDate, $endDate)
    {
        // Query the BatchDetails with conditional driver filtering
            $query = DB::table('batch_details')
            ->join('drivers', 'batch_details.assignedto', '=', 'drivers.id')
            ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
            ->whereBetween('batch_details.assignedon', [$startDate, $endDate])
            ->select(
                'drivers.id as driver_id',
                'drivers.name as driver_name',
                'batches.id as batch_id',
                'batches.batch_no',
                DB::raw('count(case when batch_details.status = "Pending" then 1 end) as pending_count'),
                DB::raw('count(case when batch_details.status = "Completed" then 1 end) as completed_count'),
                DB::raw('count(case when batch_details.status = "Aborted" then 1 end) as aborted_count')
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
                            'aborted_count' => $detail->aborted_count,
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
                    $sheet->setCellValue('D' . $row, $batch['pending_count']);
                    $sheet->setCellValue('E' . $row, $batch['completed_count']);
                    $sheet->setCellValue('F' . $row, $batch['aborted_count']);
                    
                    $totalAssigned += $batch['pending_count'];
                    $totalCompleted += $batch['completed_count'];
                    $totalIncomplete += $batch['aborted_count']; 
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
                    DB::raw('count(case when batch_details.status = "Aborted" then 1 end) as aborted_count')
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
                            'aborted_count' => $detail->aborted_count,
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
       $allColumns = [
        'batch_no', 'id', 'account_no', 'name', 'address',
        'latitude', 'longitude', 'district_la', 'taman_mmid', 'amount',
        'occupancy', 'has_water_meter', 'water_meter_no', 'has_water_bill',
        'water_bill_no', 'is_correct_address', 'correct_address', 'ownership',
        'contact_person_name', 'contact_number', 'email', 'nature_of_business_code',
        'shop_name', 'dr_code', 'property_code', 'remark', 'assignedto',
        'assignedon', 'visitdate', 'visittime', 'photo1', 'photo2', 'photo3', 'photo4', 'photo5'
        ];
        $columns = array();
        $requestbatches = '';
        return view('reports.survey', compact('page','batches','columns','requestbatches','columnDisplayNames'));
    }

    public function generateReport(Request $request)
    {
        $action = $request->input('action');
        $page = "surveyresult";
        $requestbatches = $request->input('batches');
        $statuses = $request->input('statuses');
        $columns = $request->input('columns', []);
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
        $query = DB::table('surveys')
            ->join('batches', 'surveys.batch_id', '=', 'batches.id')
            ->join('batch_details', 'surveys.batch_detail_id', '=', 'batch_details.id')
            ->join('drivers', 'drivers.id', '=', 'batch_details.assignedto')
            ->select('surveys.*', 'batches.batch_no', 'batch_details.*', 'drivers.username as assignedto')
            ->where('batches.company_id', $companyId);

        if (!empty($requestbatches)) {
            $query->where('surveys.batch_id', $requestbatches);
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
                            $drawing = new Drawing();
                            $drawing->setName($column)
                                    ->setDescription($column)
                                    ->setPath(public_path($value)) // Path to the image
                                    ->setCoordinatesByColumnAndRow($col, $row)
                                    ->setWidth(100) // Set the width of the image
                                    ->setHeight(100); // Set the height of the image
                            $drawing->setWorksheet($sheet);
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
                'requestbatches' => $requestbatches,
                'statuses' => $statuses,
                'columns' => $columns,
                'columnDisplayNames' => $columnDisplayNames,
            ]);
        }
    }
    
}
