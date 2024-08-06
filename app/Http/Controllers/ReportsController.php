<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BatchDetail;
use App\Models\Batches;
use App\Models\Driver;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
}
