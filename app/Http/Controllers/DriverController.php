<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    { 
        $companyId = Session::get('company_id'); 
        $drivers = Driver::where('company_id', $companyId)->get();
        $page = "Drivers";
        return view('drivers.index', compact('drivers','page'));
    }

    public function create()
    {        
        $companyId = Session::get('company_id'); 
        $drivers = Driver::where('company_id', $companyId)->get();
        $page = "Drivers";
        return view('drivers.create', compact('drivers','page'));
    }

    public function store(Request $request)
    {
        $companyId = Session::get('company_id');
        $request->validate([
            'name' => 'required',
         //   'email' => 'required|email|unique:drivers',
            'username' => ['required',  
                            Rule::unique('drivers')->where(function ($query) use ($companyId) {
                                return $query->where('company_id', $companyId);
                            }),
                ],
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        //Driver::create($request->all());
        $driver = new Driver($request->all());
        $driver->company_id = $companyId;
        $driver->password = Hash::make($request->password);
        $driver->app_login = $request->input('app_login', 0);
        $driver->save();

        return redirect()->route('drivers.index')->with('success', 'Driver created successfully.');
    }

    public function show($id)
    {
        $driver = Driver::findOrFail($id);
        $page = "Drivers";
        return view('drivers.show', compact('driver' ,'page'));
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        $companyId = Session::get('company_id'); 
        $drivers = Driver::where('company_id', $companyId)
                    ->where('id', '!=', $id)->get();
        $page = "Drivers";
        return view('drivers.edit', compact('driver', 'drivers' ,'page')); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
           // 'email' => 'required|email|unique:drivers,email,' . $id,
            'username' => 'required|unique:drivers,username,' . $id,
           // 'password' => 'nullable',

        ]);
        $permissions = $request->input('permissions', []);

        //$driver = Driver::findOrFail($id);
        //$driver->update($request->all());
        $driver = Driver::findOrFail($id);
        $driver->fill($request->except(['password'])); // Fill all fields except password
        if ($request->filled('password')) {
            $driver->password = Hash::make($request->password);
        }
        $driver->app_login = $request->input('app_login', 0);
        if (in_array('null', $permissions)) {
            $permissions = null;
        } 
        // Save or update the driver with the permissions
        $driver->permissions = $permissions;
        $driver->save();
        
        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully.');
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully.');
    }

    public function cleartoken($id)
    {
        $deleted = DB::table('personal_access_tokens')->where('tokenable_id', $id)->delete();
        if ($deleted) { 
          return redirect()->route('drivers.index')->with('success', 'Token Cleared successfully.');
        } else {
           
          return redirect()->route('drivers.index')->with('error', 'Token not found.'); 
        }
    }
    
    public function streetmap()
    { 
        $page = "streetmap";
        return view('streetmap.streetmap', compact('page')); 
    }
}

