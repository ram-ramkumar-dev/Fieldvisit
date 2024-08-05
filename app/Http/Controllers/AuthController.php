<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Batches;
use App\Models\BatchDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        ]);

        $driver_id = $request->driver_id;
        $batch_id = $request->batch_id;
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
     
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

        $response = $batches->map(function ($batch) use ($driver_id, $batch_id) {
            $batchDetailsQuery = BatchDetail::where('assignedto', $driver_id);
            if ($batch_id) {
                $batchDetailsQuery->where('batch_id', $batch_id);
            } else {
                $batchDetailsQuery->where('batch_id', $batch->id);
            }
            $batchDetails = $batchDetailsQuery
                ->select('id', 'batch_id', 'address', 'district_la', 'taman_mmid', 'assignedto', 'batchfile_latitude', 'status', 'batchfile_longitude') // Include 'assignedto'
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
}
