<?php

namespace App\Http\Controllers;

use App\Models\Batches;
use App\Models\User;
use App\Models\Driver;
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
        $companyId = Session::get('company_id'); 
        $data['users'] =  Driver::where(array('company_id'=>$companyId,'status'=>1))->get(); 
        $data['totalbatches'] = Batches::where([
                                     'company_id' => $companyId,
                                     'status' => 1
                         ])->withCount('batchDetails')->get();
        $totalBatchDetailsCount = $data['totalbatches']->sum('batch_details_count');
        $data['totalBatchDetailsCount'] = $totalBatchDetailsCount;
 
        $data['page'] = 'Dashboard';
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
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Check in drivers table
        $driver = DB::table('drivers')->where('username', $request->username)->first();
        if ($driver && Hash::check($request->password, $driver->password)) {
            Auth::loginUsingId($driver->id, true); // 'true' means "remember the user"
            $request->session()->put('user_type', 'driver');
            $request->session()->put('user_name', $driver->username);
            $request->session()->put('user_id', $driver->id);
            $request->session()->put('company_id', $driver->company_id);
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
