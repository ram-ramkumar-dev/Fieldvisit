<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Batches;
use App\Models\BatchDetail;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

/**
 * @OA\Info(title="API Documentation", version="1.0")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a driver",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="driver@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'devicetoken' => 'required|string', // Validate the device_token
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $driver = Driver::where('username', $request->username)->first();
        
        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }

         // Check if the driver is already logged in with a different device token
        if ($driver->devicetoken && $driver->devicetoken !== $request->devicetoken) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are already logged in on another device. Please log out from the other device first.',
            ], 403);
        }

        // If the driver was logged in on the same device before but with a different token (e.g., after reinstallation)
        if ($driver->tokens()->count() > 0) {
            // Log out from the previous session
            $driver->tokens()->delete();
        }

        // Update the device token
        $driver->devicetoken = $request->devicetoken;
        $driver->save();

        $token = $driver->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'details' => $driver
        ], 200); // Return a 200 OK status code
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout a driver",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     )
     * )
     */ 
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

     /**
     * @OA\Post(
     *     path="/api/batches",
     *     summary="Get batches for a driver",
     *     tags={"Batches"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"driver_id"},
     *             @OA\Property(property="driver_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of batches",
     *         @OA\JsonContent(
     *             @OA\Property(property="batches", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="batch_no", type="string"),
     *                     @OA\Property(property="batchDetails", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="status", type="string")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function getBatchesForDriverold(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate the token and get the driver (if needed for additional validation)
        $driver = $request->user();
        if (!$driver || $driver->id != $request->driver_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Get all batch IDs assigned to the driver
        $batchIds = BatchDetail::where('assignedto', $request->driver_id)
                    ->pluck('batch_id')
                    ->unique()
                    ->toArray();

        // Get the batch details
        $batches = Batches::whereIn('id', $batchIds)
        ->with('batchDetails') // Eager load batch details
        ->get();

         // Calculate status counts
         $pendingCount = BatchDetail::whereIn('batch_id', $batchIds)->where('status', 'Pending')->count();
         $completedCount = BatchDetail::whereIn('batch_id', $batchIds)->where('status', 'Completed')->count();
         $abortedCount = BatchDetail::whereIn('batch_id', $batchIds)->where('status', 'Aborted')->count();
 
         // Prepare the response
        $response = [
            'pending_count' => $pendingCount,
            'completed_count' => $completedCount,
            'aborted_count' => $abortedCount,
            'batches' => $batches->map(function ($batch) {
                return [
                    'id' => $batch->id,
                    'batch_no' => $batch->batch_no,
                    'pending_details' => $batch->batchDetails->where('status', 'Pending')->map(function ($detail) {
                        return [
                            'status' => $detail->status,   
                            'name' => $detail->name,
                            'ic_no' => $detail->ic_no,
                            'account_no' => $detail->account_no,
                            'bill_no' => $detail->bill_no,
                            'amount' => $detail->amount,
                            'address' => $detail->address,
                            'district_la' => $detail->district_la, 
                            'taman_mmid' => $detail->taman_mmid, 
                            'roadname' => $detail->roadname, 
                            'state' => $detail->state, 
                            'post_code' => $detail->post_code,  
                            'batchfile_latitude' => $detail->batchfile_latitude, 
                            'batchfile_longitude' => $detail->batchfile_longitude, 
                        ];
                    }),
                    'completed_details' => $batch->batchDetails->where('status', 'Completed')->map(function ($detail) {
                        return [
                            'status' => $detail->status,   
                            'name' => $detail->name,
                            'ic_no' => $detail->ic_no,
                            'account_no' => $detail->account_no,
                            'bill_no' => $detail->bill_no,
                            'amount' => $detail->amount,
                            'address' => $detail->address,
                            'district_la' => $detail->district_la, 
                            'taman_mmid' => $detail->taman_mmid, 
                            'roadname' => $detail->roadname, 
                            'state' => $detail->state, 
                            'post_code' => $detail->post_code, 
                            'batchfile_latitude' => $detail->batchfile_latitude, 
                            'batchfile_longitude' => $detail->batchfile_longitude, 
                        ];
                    }),
                    'aborted_details' => $batch->batchDetails->where('status', 'Aborted')->map(function ($detail) {
                        return [
                            'status' => $detail->status,   
                            'name' => $detail->name,
                            'ic_no' => $detail->ic_no,
                            'account_no' => $detail->account_no,
                            'bill_no' => $detail->bill_no,
                            'amount' => $detail->amount,
                            'address' => $detail->address,
                            'district_la' => $detail->district_la, 
                            'taman_mmid' => $detail->taman_mmid, 
                            'roadname' => $detail->roadname, 
                            'state' => $detail->state, 
                            'post_code' => $detail->post_code, 
                            'batchfile_latitude' => $detail->batchfile_latitude, 
                            'batchfile_longitude' => $detail->batchfile_longitude, 
                        ];
                    }),
                ];
            }),
        ];

        return response()->json($response, 200); 
    }

    public function dashboardForDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Validate the token and get the driver (if needed for additional validation)
        $driver = $request->user();
        if (!$driver || $driver->id != $request->driver_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
    
        // Get all batch IDs assigned to the driver
        $batchIds = BatchDetail::where('assignedto', $request->driver_id)
                    ->pluck('batch_id')
                    ->unique()
                    ->toArray();
    
        // Total counts
        $totalBatches = Batches::whereIn('id', $batchIds)->count();
        $totalBatchDetails = BatchDetail::whereIn('batch_id', $batchIds)->where('assignedto', $request->driver_id)->count();
        $totalCompletedBatchDetails = BatchDetail::whereIn('batch_id', $batchIds)
            ->where('status', 'Completed')  ->where('assignedto', $request->driver_id)
            ->count();
    
        // Get the batch details where the driver is assigned
        $batches = Batches::whereIn('id', $batchIds)
            ->select('id', 'batch_no') // Select specific columns from Batches
            ->with(['batchDetails' => function($query) use ($request) {
                $query->select('id', 'batch_id', 'address', 'district_la', 'taman_mmid', 'assignedto', 'batchfile_latitude','batchfile_longitude') // Include 'assignedto'
                    ->where('assignedto', $request->driver_id); // Filter by the driver
            }])
            ->get();
    
        return response()->json([
            'status' => 'success', 
            'total_batches' => $totalBatches,
            'total_batch_details' => $totalBatchDetails,
            'total_completed_batch_details' => $totalCompletedBatchDetails,
            'batches' => $batches
        ], 200); 
    }  
     
    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:drivers,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Driver::find($request->driver_id);
        if (!$driver) {
            return response()->json(['status' => 'error', 'message' => 'Driver not found'], 404);
        }

        $driver->latitude = $request->latitude;
        $driver->longitude = $request->longitude;
        $driver->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Location updated successfully',
            'driver' => $driver
        ], 200);
    }

    public function getBatchesForDriver(Request $request)
    {        
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|string',
            'batch_id' => 'nullable|exists:batches,id',
            'search' => 'nullable|string', // Add validation for the search parameter
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver_id = $request->driver_id;
        $batch_id = $request->batch_id;
        $search = $request->search; // Get the search parameter

        $batches = Batches::whereHas('batchDetails', function($query) use ($driver_id, $batch_id) {
            $query->where('assignedto', $driver_id);
            if ($batch_id) {
                $query->where('batch_id', $batch_id);
            }
        })
        ->select('id', 'batch_no')
        ->get();

        if ($batches->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No batch details found for the specified driver and batch.',
                'data' => null
            ], 404);
        }

        $response = $batches->map(function ($batch) use ($driver_id, $batch_id, $search) {
            $batchDetailsQuery = BatchDetail::where('assignedto', $driver_id)
                ->where('softdelete', '!=', '1') // Exclude soft deleted details
                ->orderBy('pinned_at', 'desc'); // Order by pinned_at

            if ($batch_id) {
                $batchDetailsQuery->where('batch_id', $batch_id);
            } else {
                $batchDetailsQuery->where('batch_id', $batch->id);
            }

            if ($search) {
                $batchDetailsQuery->where(function($query) use ($search) {
                    $query->where('account_no', 'like', "%{$search}%")
                          ->orWhere('name', 'like', "%{$search}%")
                          ->orWhere('address', 'like', "%{$search}%")
                          ->orWhere('taman_mmid', 'like', "%{$search}%")
                          ->orWhere('state', 'like', "%{$search}%")
                          ->orWhere('district_la', 'like', "%{$search}%");
                });
            }

            $batchDetails = $batchDetailsQuery
                ->select('id', 'batch_id', 'name', 'ic_no', 'account_no', 'bill_no', 'amount', 'address', 'district_la', 'taman_mmid', 'assignedto', 'batchfile_latitude', 'status', 'batchfile_longitude', 'pinned_at') 
                ->get();

            $pending = $batchDetails->where('status', 'Pending')->values();
            $completed = $batchDetails->where('status', 'Completed')->values();
            $aborted = $batchDetails->where('status', 'Aborted')->values();

            // Count of each status
            $pendingCount = $pending->count();
            $completedCount = $completed->count();
            $abortedCount = $aborted->count();

            return [
                'batch_id' => $batch->id,
                'batch_no' => $batch->batch_no,
                'pending_count' => $pendingCount,
                'completed_count' => $completedCount,
                'aborted_count' => $abortedCount,
                'pending_details' => $pending,
                'completed_details' => $completed,
                'aborted_details' => $aborted,
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Batch details retrieved successfully.',
            'data' => $response
        ], 200);
    }

    public function storeSurvey(Request $request)
    {
         
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required|exists:batches,id',
            'batch_detail_id' => 'required|exists:batch_details,id',
            'user_id' => 'required', 
            'photo1' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
            // Check if a survey already exists for the given batch_id, batch_detail_id, and user_id
        $survey = Survey::where('batch_id', $request->batch_id)
        ->where('batch_detail_id', $request->batch_detail_id)
        ->where('user_id', $request->user_id)
        ->first();

        if (!$survey) {
            $survey = new Survey();
            $survey->batch_id = $request->batch_id;
            $survey->batch_detail_id = $request->batch_detail_id; 
            $survey->user_id = $request->user_id;
        } 
        
        $convertedDate = Carbon::createFromFormat('d/m/y', $request->visitdate)->format('Y-m-d');

        $survey->has_water_meter = $request->has_water_meter;
        $survey->water_meter_no = $request->water_meter_no;
        $survey->has_water_bill = $request->has_water_bill;
        $survey->water_bill_no = $request->water_bill_no;
        $survey->is_correct_address = $request->is_correct_address;
        $survey->correct_address = $request->correct_address;
        $survey->ownership = $request->ownership;
        $survey->contact_person_name = $request->contact_person_name;
        $survey->contact_number = $request->contact_number;
        $survey->email = $request->email;
        $survey->nature_of_business_code = $request->nature_of_business_code;
        $survey->shop_name = $request->shop_name;
        $survey->dr_code = $request->dr_code;
        $survey->property_code = $request->property_code;
        $survey->occupancy = $request->occupancy; 
        $survey->remark = $request->remark;
        $survey->visitdate = $convertedDate;
        $survey->visittime = $request->visittime;  
        // Save other survey fields here

        // Handle photo uploads
        if ($request->hasFile('photo1')) {
            $survey->photo1 = $this->uploadPhoto($request->file('photo1'));
        }
        if ($request->hasFile('photo2')) {
            $survey->photo2 = $this->uploadPhoto($request->file('photo2'));
        }
        if ($request->hasFile('photo3')) {
            $survey->photo3 = $this->uploadPhoto($request->file('photo3'));
        }
        if ($request->hasFile('photo4')) {
            $survey->photo4 = $this->uploadPhoto($request->file('photo4'));
        }
        if ($request->hasFile('photo5')) {
            $survey->photo5 = $this->uploadPhoto($request->file('photo5'));
        }

        $survey->save();

        //update batchdetails status to completed 
        $batchDetail = BatchDetail::find($request->batch_detail_id); 
        $batchDetail->status = 'Completed'; 
        $batchDetail->save();

        // Return a success response
        return response()->json(['success' => 'Survey saved successfully', 'data' => $survey], 201);
    }

    private function uploadPhoto($photo)
    {
        $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
        $path = $photo->storeAs('public/surveyphotos', $filename);
        return Storage::url($path);
    }

    public function updateBatchDetail(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'batch_detail_id' => 'required',
            'action' => 'required|in:pin,unpin,abort,softdelete',
        ]);
 
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $batchDetail = BatchDetail::find($request->batch_detail_id);

        switch ($request->action) {
            case 'pin':
                $batchDetail->pinned_at = Carbon::now();
                break;
                
            case 'unpin':
                $batchDetail->pinned_at = null;
                break;

            case 'abort':
                $batchDetail->status = 'Aborted';
                break;

            case 'softdelete':
                $batchDetail->softdelete = '1';
                break;
        }

        $batchDetail->save();

        return response()->json(['message' => 'Batch detail updated successfully'], 201);
    }

    public function getBatchDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
            'batch_id' => 'nullable|exists:batches,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $batch_id = $request->batch_id; 
        $driver_id = $request->driver_id;
        $search = $request->search; // Get the search parameter

        $batchDetailsQuery = BatchDetail::where('assignedto', $driver_id)->where('status', '!=', 'soft_deleted')
        ->orderBy('pinned_at', 'desc');

        if ($batch_id) {
            $batchDetailsQuery->where('batch_id', $batch_id);
        }
        
        if ($search) {
            $batchDetailsQuery->where(function($query) use ($search) {
                $query->where('account_no', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhere('taman_mmid', 'like', "%{$search}%")
                      ->orWhere('state', 'like', "%{$search}%")
                      ->orWhere('district_la', 'like', "%{$search}%");
            });
        }

        $batchDetails = $batchDetailsQuery
        ->select('id', 'batch_id', 'name', 'ic_no', 'account_no', 'bill_no', 'amount', 'address', 'district_la', 'taman_mmid', 'assignedto', 'batchfile_latitude', 'status', 'batchfile_longitude', 'pinned_at') // 
        ->get(); 

        $pending = $batchDetails->where('status', 'Pending')->values();
        $completed = $batchDetails->where('status', 'Completed')->values();
        $aborted = $batchDetails->where('status', 'Aborted')->values();

        // Count of each status
        $pendingCount = $pending->count();
        $completedCount = $completed->count();
        $abortedCount = $aborted->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch details retrieved successfully.',
            'data' => [
                'pending_count' => $pendingCount,
                'completed_count' => $completedCount,
                'aborted_count' => $abortedCount,
                'pending_details' => $pending,
                'completed_details' => $completed,
                'aborted_details' => $aborted,
            ]
        ], 200);
    }

    public function getDropDowns()
    {
         // Fetch data from all three tables where status is 1
         $classification = DB::table('classification')->select('id', 'classification_name')->where('status', 1)->get(); 
         $dr_code = DB::table('dr_code')->select('id', 'code','dr_code_name')->where('status', 1)->get();
         $nature_of_bussiness_code = DB::table('nature_of_bussiness_code')->select('id', 'code','nature_of_bussiness_code_name')->where('status', 1)->get();
         $occupancy_status = DB::table('occupancy_status') ->select('id', 'occupancy_status_name')->where('status', 1)->get();
         $ownerships = DB::table('ownerships') ->select('id', 'ownershipname')->where('status', 1)->get();
         $property_type = DB::table('property_type') ->select('id','code', 'property_type_name')->where('status', 1)->get();
         
         // Combine the results into a single response
         return response()->json([
             'status' => 'success',
             'classification' => $classification,
             'dr_code' => $dr_code,
             'nature_of_bussiness_code' => $nature_of_bussiness_code,
             'occupancy_status' => $occupancy_status,
             'ownerships' => $ownerships,
             'property_type' => $property_type, 
         ], 200);
    }
    
    public function DriversProfile(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|string', 
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $driver = Driver::select('id', 'username', 'name', 'phone_number', 'ic_number', 'sensitive')->where('id', $request->driver_id)->first();  

        return response()->json([  
            'status' => 'success',
            'profile' => $driver
        ], 200); // Return a 200 OK status code
    }

    public function driversLeaderBoard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $loggedInDriverId = $request->input('driver_id');

        // Retrieve the logged-in driver and associated company_id
        $loggedInDriver = Driver::find($loggedInDriverId);

        if (!$loggedInDriver) {
            return response()->json(['error' => 'Driver not found.'], 404);
        }

        $companyId = $loggedInDriver->company_id;

        // Get the list of other drivers excluding the logged-in driver
        $otherDrivers = Driver::where('id', '!=', $loggedInDriverId)->pluck('id');

            // Get the list of other drivers in the same company, excluding the logged-in driver
        $otherDrivers = Driver::where('company_id', $companyId)
                ->where('id', '!=', $loggedInDriverId)
                ->pluck('id');
         
        // Get status counts for other drivers
        $otherDriversStatusCounts = DB::table('batch_details')
        ->select('assignedto', DB::raw('COUNT(*) as assigned_count'), DB::raw('SUM(CASE WHEN batch_details.status = "Pending" THEN 1 ELSE 0 END) as pending_count'), DB::raw('SUM(CASE WHEN batch_details.status = "Completed" THEN 1 ELSE 0 END) as completed_count'))
        ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
        ->whereIn('assignedto', $otherDrivers)
        ->where('batches.company_id', $companyId) // Filter by company_id
        ->groupBy('assignedto')
        ->get()
            ->keyBy('assignedto');

        // Format the status counts for each other driver
        $otherDriversStatus = Driver::whereIn('id', $otherDrivers)->get()->map(function ($driver) use ($otherDriversStatusCounts) {
            $statusCounts = $otherDriversStatusCounts->get($driver->id, collect([
                'assigned_count' => 0,
                'pending_count' => 0,
                'completed_count' => 0,
            ]));

            return [
                'driver_id' => $driver->id,
                'driver_name' => $driver->name,
                'status_counts' => [
                    'pending' => $statusCounts->pending_count ?? 0,
                    'assigned' => $statusCounts->assigned_count ?? 0,
                    'completed' => $statusCounts->completed_count ?? 0,
                ],
            ];
        });

        // Get status counts for the logged-in driver
        $loggedInDriverStatusCounts = DB::table('batch_details')
            ->select(DB::raw('COUNT(*) as assigned_count'), DB::raw('SUM(CASE WHEN batch_details.status = "Pending" THEN 1 ELSE 0 END) as pending_count'), DB::raw('SUM(CASE WHEN batch_details.status = "Completed" THEN 1 ELSE 0 END) as completed_count'))
            ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
            ->where('assignedto', $loggedInDriverId)
            ->where('batches.company_id', $companyId) // Filter by company_id
            ->first();

        // Format the logged-in driver status counts
        $loggedInDriverStatus = [
            'driver_id' => $loggedInDriverId,
            'driver_name' => $loggedInDriver->name,
            'status_counts' => [
                'pending' => $loggedInDriverStatusCounts->pending_count ?? 0,
                'assigned' => $loggedInDriverStatusCounts->assigned_count ?? 0,
                'completed' => $loggedInDriverStatusCounts->completed_count ?? 0,
            ],
        ];

        // Return the response as JSON
        return response()->json([
            'status' => 'success',
            'requested_driver' => $loggedInDriverStatus,
            'other_drivers' => $otherDriversStatus,
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer',
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $driver = Driver::find($request->driver_id);
    
        if (!$driver || !Hash::check($request->old_password, $driver->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
        }
    
        $driver->password = Hash::make($request->new_password);
        $driver->save();
    
        return response()->json(['status' => 'success','message' => 'Password changed successfully'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|min:10|max:15',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Driver::where('phone_number', $request->phone_number)->first();
    
        if (!$driver) {
            return response()->json(['status' => 'error', 'message' => 'Mobile number not found'], 404);
        }
    
        // Generate a 4-digit random code
        // $code = rand(1000, 9999);
        $code = '1234';
        // Store the code in the database (e.g., in a `password_resets` table or in the `drivers` table)
        DB::table('password_resets')->updateOrInsert(
            ['phone_number' => $request->phone_number],
            ['token' => $code, 'created_at' => now()]
        );
    
        // Send the code via SMS (use an SMS gateway API like Twilio)
        // Example: SMS::send($request->phone_number, "Your password reset code is $code");
    
        return response()->json(['status' => 'success','message' => 'Password reset code sent successfully'], 200);
    }

    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|min:10|max:15',
            'reset_code' => 'required|string|size:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $record = DB::table('password_resets')
                    ->where('phone_number', $request->phone_number)
                    ->where('token', $request->reset_code)
                    ->first();
    
        if (!$record || Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired reset code'], 400);
        }
    
        return response()->json(['status' => 'success', 'message' => 'Reset code verified successfully'], 200);
    }
     
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|min:10|max:15',
            'reset_code' => 'required|string|size:4',
            'new_password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $record = DB::table('password_resets')
                    ->where('phone_number', $request->phone_number)
                    ->where('token', $request->reset_code)
                    ->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired reset code'], 400);
        }

        $driver = Driver::where('phone_number', $request->phone_number)->first();
        $driver->password = Hash::make($request->new_password);
        $driver->save();

        // Optionally, delete the reset record
        DB::table('password_resets')->where('phone_number', $request->phone_number)->delete();

        return response()->json(['status' => 'success','message' => 'Password reset successfully'], 200);
    }




}
