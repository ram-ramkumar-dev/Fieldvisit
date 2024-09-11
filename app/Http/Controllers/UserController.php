<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Batches; 
use App\Models\BatchDetail; 
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login', 'login_action', 'register', 'register_action']);
    }

    public function register()
    {
        $data['title'] = 'Register';
        return view('user/register', $data);
    }

    public function home()
    {
        $data['title'] = 'Home';  
        if (Session::get('user_id')) 
        {
            return redirect()->route('dashboard');
        }
        else
        {
            return redirect()->route('login');
        }
    }

    public function dashboard()
    {      
        // Check if the logged-in user is a superadmin
        if (!session()->has('is_superadmin') || session('is_superadmin') !== false) {
            // Redirect to login page or an error page if not a superadmin
            return redirect('login')->withErrors(['access' => 'You do not have permission to access this page.']);
        }
        $companyId = Session::get('company_id'); 
        
        $data['users'] =  Driver::where(array('company_id'=>$companyId,'status'=>1))->get(); 
 
        // Initialize an array to store formatted results
        $statusCountsByDriver = [];
        foreach ($data['users'] as $driver) {
            $statusCountsByDriver[$driver->id] = [
                'id' => $driver->id,
                'driver_name' => $driver->name,
                'devicetoken' => $driver->devicetoken, 
                'pending' => 0,
                'completed' => 0,
                'aborted' => 0,
                'all' => 0,
                'assigned' => 0 
            ];
        }

        // Query to get the status counts grouped by driver
        $batchDetailsByDriver = BatchDetail::select('assignedto', 'status', DB::raw('COUNT(*) as status_count'))
            ->whereIn('assignedto', $data['users']->pluck('id'))
            ->groupBy('assignedto', 'status')
            ->get();

        // Populate the status counts
        foreach ($batchDetailsByDriver as $detail) {
            switch ($detail->status) {
                case 'Pending':
                    $statusCountsByDriver[$detail->assignedto]['pending'] = $detail->status_count;
                    break;
                case 'Completed':
                    $statusCountsByDriver[$detail->assignedto]['completed'] = $detail->status_count;
                    break;
                case 'Aborted':
                    $statusCountsByDriver[$detail->assignedto]['aborted'] = $detail->status_count;
                    break;
            }
            // Sum up all statuses to get the total count
            $statusCountsByDriver[$detail->assignedto]['all'] += $detail->status_count;
        }

        // Query to get the assigned count for each driver
        $assignedCounts = BatchDetail::select('assignedto', DB::raw('COUNT(*) as assigned_count'))
            ->whereIn('assignedto', $data['users']->pluck('id'))
            ->whereNotNull('assignedto') // Make sure the 'assignedto' column is not null
            ->groupBy('assignedto')
            ->get();

        // Populate the assigned counts
        foreach ($assignedCounts as $assigned) {
            $statusCountsByDriver[$assigned->assignedto]['assigned'] = $assigned->assigned_count;
        }

        // Calculate the score for each driver (completed / assigned * 100)
        foreach ($statusCountsByDriver as $driverId => $counts) {
            $assigned = $counts['assigned'] ?? 0;
            $completed = $counts['completed'] ?? 0;

            // Avoid division by zero
            if ($assigned > 0) {
                $statusCountsByDriver[$driverId]['score'] = ($completed / $assigned) * 100;
            } else {
                $statusCountsByDriver[$driverId]['score'] = 0;
            }
        }

        // Sort the list by completed surveys in descending order
        $list = collect($statusCountsByDriver)->sortByDesc('score')->values()->all();

        // Extract the top agent without removing them from the list
        $topAgent = $list[0] ?? null; // Safely get the first element if it exists

        // Calculate the total surveys for the top agent
        $totalSurveys = $topAgent ? ($topAgent['assigned']) : 0;

        // Pass the list and top agent to the view
        $data['list'] = $list; // Complete list including the top agent
        $data['topAgent'] = $topAgent; // Top agent separately identified

        $data['totalSurveys'] = $totalSurveys;
        $data['totalbatches'] = Batches::where([
                                     'company_id' => $companyId,
                                     'status' => 1
                         ])->withCount('batchDetails')->get();
        $totalBatchDetailsCount = $data['totalbatches']->sum('batch_details_count');
        $data['totalBatchDetailsCount'] = $totalBatchDetailsCount;
        $counts = Batches::where('company_id', $companyId)->where('status', 1)
                                ->withCount([
                                    'batchDetails as completed_count' => function ($query) {
                                        $query->where('status', 'completed');
                                    },
                                    'batchDetails as pending_count' => function ($query) {
                                        $query->where('status', 'pending');
                                    },
                                    'batchDetails as assign_count' => function ($query) {
                                        $query->whereNotNull('assignedto');
                                    },
                                    'batchDetails as aborted_count' => function ($query) {
                                        $query->where('status', 'aborted');
                                    },
                                    'batchDetails'
                                ])->get();
        $data['totalCompleted'] = $counts->sum('completed_count');
        $data['totalPending'] = $counts->sum('pending_count');
        $data['totalAborted'] = $counts->sum('aborted_count'); 
        $data['totalAssigned'] = $counts->sum('assign_count'); 
        $data['page'] = 'Dashboard';
        $data['counts'] = $counts;   
        // Prepare chart data (monthly and last 4 days)
        $chart01Data = $this->prepareChart01Data($companyId);
        $lastFourDaysData = $this->getLastFourDaysData($companyId);

        $data['chart01completed'] = $chart01Data['chart01completed'];
        $data['chart01visit'] = $chart01Data['chart01visit'];
        $data['chart01months'] = $chart01Data['chart01months'];
        $data['lastFourDaysData'] = $lastFourDaysData;                     
        return view('dashboard', $data);
    }

    public function users()
    {
        // Example usage of $permissions
        if (!empty($permissions) && !in_array('reports', $permissions)) {
            return redirect('/')->withErrors('You do not have permission to access this page.');
        }

        // Fetch or generate reports data here (dummy data used as an example)
        $reportsData = [
            ['id' => 1, 'title' => 'Report 1', 'content' => 'Content of report 1'],
            ['id' => 2, 'title' => 'Report 2', 'content' => 'Content of report 2'],
            // Add more reports as needed
        ]; 

        return view('dashboard');
    }

    public function register_action(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        $user = new User([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        return redirect()->route('login')->with('success', 'Registration success. Please login!');
    }

    public function login()
    {
        $data['title'] = 'Login';
        return view('user/login', $data);
    }

    public function login_action(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Check in users table
        $user = DB::table('users')->where('username', $request->username)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::loginUsingId($user->id);
            $request->session()->put('user_type', 'user');
            $request->session()->put('user_name', $user->username);
            $request->session()->put('user_id', $user->id); 
            $request->session()->put('company_id', $user->company_id);
            $request->session()->put('is_superadmin', false);
            // Check if the user is a superadmin
            if ($user->groups === 1) {  // Assuming you have a role or similar column
                $request->session()->put('is_superadmin', true);
                $request->session()->regenerate();  
                return redirect('/superadmindashboard');
            }

            // Redirect to normal user dashboard
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        // Check in drivers table
        $driver = DB::table('drivers')->where('username', $request->username)->where('app_login', 1)->first();
        if ($driver && Hash::check($request->password, $driver->password)) {
            Auth::loginUsingId($driver->id, true); // 'true' means "remember the user"
            $request->session()->put('user_type', 'driver');
            $request->session()->put('user_name', $driver->username);
            $request->session()->put('user_id', $driver->id);
            $request->session()->put('company_id', $driver->company_id);
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Check if the driver exists but doesn't meet the fv_login condition
        if ($driver && !Hash::check($request->password, $driver->password)) {
            return back()->withErrors([
                'password' => 'Wrong username or password',
            ]);
        }

        // Driver does not meet the fv_login condition
        return back()->withErrors([
            'access' => 'You do not have access to login.',
        ]);
    }

    public function superadmindashboard(){
        // Check if the logged-in user is a superadmin
        if (!session()->has('is_superadmin') || session('is_superadmin') !== true) {
            // Redirect to login page or an error page if not a superadmin
            return redirect('login')->withErrors(['access' => 'You do not have permission to access this page.']);
        }
        $data['title'] = 'Superadmin Dashboard';
        $data['page'] = 'dashboard';
        return view('superadmin/dashboard', $data);
    }

    public function password()
    {
        $data['title'] = 'Change Password';
        return view('user/password', $data);
    }

    public function password_action(Request $request)
    {
        $request->validate([
            'old_password' => 'required|current_password',
            'new_password' => 'required|confirmed',
        ]);
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->save();
        $request->session()->regenerate();
        return back()->with('success', 'Password changed!');
    }

    public function profile(){
        $user_type = Session::get('user_type'); 
        $user_id = Session::get('user_id');
        $data['page'] = 'profile';
        if($user_type == 'user'){
            //$data['user'] = User::find($user_id)->first();
            $data['user'] = DB::table('users')
                ->join('companies', 'users.company_id', '=', 'companies.id')  
                ->where('users.id', $user_id) 
                ->first();
        }else{
            //$data['user'] = Driver::find($user_id)->first();
            $data['user'] = DB::table('drivers')
                ->join('companies', 'drivers.company_id', '=', 'companies.id') 
                ->where('drivers.id', $user_id) 
                ->first();
        }
         
        return view('user/profile', $data);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    } 

    private function prepareChart01Data($companyId)
    { 
          // Array of months
          $months = [];

          // Get the current date
          $now = Carbon::now();
          
          // Iterate over the last 6 months including the current month
          for ($i = 0; $i < 6; $i++) {
              // Get the month for the iteration and format it as 'M' (e.g., 'Jan', 'Feb')
              $months[] = $now->copy()->subMonths($i)->format('F'); // Including the year for clarity
          }
          
          // Reverse the array to show the most recent month first
          $months = array_reverse($months); 
 
          // Query for 'completed' counts from 'batch_details' by month
          $completedCounts = DB::table('batch_details')
              ->select(DB::raw('MONTHNAME(assignedon) as month'), DB::raw('COUNT(*) as count'))
              ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
              ->where('batches.company_id', $companyId) 
              ->where('batches.status', 1) 
              ->whereIn(DB::raw('MONTHNAME(assignedon)'), $months)
              ->where('batch_details.status', 'Completed') // Assuming there's a 'status' column with 'completed' status
              ->groupBy(DB::raw('MONTH(assignedon)'), DB::raw('MONTHNAME(assignedon)'))
              ->orderBy(DB::raw('MONTH(assignedon)'))
              ->pluck('count', 'month')
              ->toArray(); 

          // Query for 'visit' counts from 'survey' by month
          $visitCounts = DB::table('surveys')
              ->select(DB::raw('MONTHNAME(visitdate) as month'), DB::raw('COUNT(*) as count'))
              ->join('batches', 'surveys.batch_id', '=', 'batches.id')
              ->where('batches.company_id', $companyId) 
              ->where('batches.status', 1) 
              ->whereIn(DB::raw('MONTHNAME(visitdate)'), $months)
              ->groupBy(DB::raw('MONTH(visitdate)'), DB::raw('MONTHNAME(visitdate)'))
              ->orderBy(DB::raw('MONTH(visitdate)'))
              ->pluck('count', 'month')
              ->toArray();
  
          // Ensure all months are represented, even if counts are zero
          $completed = [];
          $visit = [];
  
        foreach ($months as $month) {
            $completed[] = $completedCounts[$month] ?? 0;
            $visit[] = $visitCounts[$month] ?? 0;
        }
        $data['chart01completed'] = $completed;
        $data['chart01visit'] = $visit;
        $data['chart01months'] = $months;
 
       return $data;
    }

    private function getLastFourDaysData($companyId)
    { 
        $lastFourDaysData = [
            'dates' => [],
            'completed' => [],
            'pending' => [],
            'assigned' => []
        ];

        for ($i = 4; $i >= 1; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $lastFourDaysData['dates'][] = Carbon::now()->subDays($i)->format('d M'); // For display

            // Completed
            $completedData = DB::table('batch_details')
                ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
                ->where('batch_details.status', 'Completed')
                ->whereDate('batch_details.assignedon', $date)
                ->where('batches.company_id', $companyId) 
                ->where('batches.status', 1) 
                ->get();
            
            $lastFourDaysData['completed'][] = $completedData->count(); 

            // Pending
            $pendingData = DB::table('batch_details')
                ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
                ->where('batch_details.status', 'Pending')
                ->whereDate('batch_details.assignedon', $date)
                ->where('batches.company_id', $companyId) 
                ->where('batches.status', 1) 
                ->get();

            $lastFourDaysData['pending'][] = $pendingData->count(); 

            // Assigned
            $assignedData = DB::table('batch_details')
                ->join('batches', 'batch_details.batch_id', '=', 'batches.id')
                ->whereDate('batch_details.assignedon', $date)
                ->where('batches.company_id', $companyId) 
                ->where('batches.status', 1) 
                ->get();

            $assignedCount = $assignedData->count();
            $lastFourDaysData['assigned'][] = $assignedCount; 
        } 
        return $lastFourDaysData; 
    }
}
