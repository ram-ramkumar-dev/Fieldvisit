<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Batches;
use App\Models\BatchDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $driver = Driver::where('username', $request->username)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $driver->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'details' => $driver
        ]);
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

        return response()->json(['message' => 'Logged out successfully']);
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

    public function getBatchesForDriver(Request $request)
    {
        $request->validate([
            'driver_id' => 'required',
        ]);

        // Validate the token and get the driver (if needed for additional validation)
        $driver = $request->user();
        if (!$driver || $driver->id != $request->driver_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
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
                        ];
                    }),
                ];
            }),
        ];

        return response()->json($response); 
    }

    public function dashboardForDriver(Request $request)
    {
        $request->validate([
            'driver_id' => 'required',
        ]);

        // Validate the token and get the driver (if needed for additional validation)
        $driver = $request->user();
        if (!$driver || $driver->id != $request->driver_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get all batch IDs assigned to the driver
        $batchIds = BatchDetail::where('assignedto', $request->driver_id)
                    ->pluck('batch_id')
                    ->unique()
                    ->toArray();

        $totalBatches = Batches::whereIn('id', $batchIds)->count();

        $totalBatchDetails = BatchDetail::whereIn('batch_id', $batchIds)->count();

        $totalCompletedBatchDetails = BatchDetail::whereIn('batch_id', $batchIds)
            ->where('status', 'Completed')
            ->count();

        // Get the batch details without the counts
        $batches = Batches::whereIn('id', $batchIds)
           // ->with('batchDetails')
            ->get();

        return response()->json([
            'total_batches' => $totalBatches,
            'total_batch_details' => $totalBatchDetails,
            'total_completed_batch_details' => $totalCompletedBatchDetails,
            'batches' => $batches
        ]); 
    }
}
