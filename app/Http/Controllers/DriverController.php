<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    { 
        $drivers = Driver::all();
        $page = "Drivers";
        return view('drivers.index', compact('drivers','page'));
    }

    public function create()
    {
        $loggedInId = Session::get('user_id'); 
        $drivers = Driver::where('id', '!=', $loggedInId)->get();
        $page = "Drivers";
        return view('drivers.create', compact('drivers','page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
         //   'email' => 'required|email|unique:drivers',
            'username' => 'required|unique:drivers',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        //Driver::create($request->all());
        $driver = new Driver($request->all());
        $driver->password = Hash::make($request->password);
        $driver->save();

        return redirect()->route('drivers.index')->with('success', 'Driver created successfully.');
    }

    public function show($id)
    {
        $driver = Driver::findOrFail($id);
        return view('drivers.show', compact('driver'));
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        $loggedInId = Session::get('user_id'); 
        $drivers = Driver::where('id', '!=', $loggedInId)->get();
        $page = "Drivers";
        return view('drivers.edit', compact('driver', 'drivers','page')); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
           // 'email' => 'required|email|unique:drivers,email,' . $id,
            'username' => 'required|unique:drivers,username,' . $id,
           // 'password' => 'nullable',

        ]);

        //$driver = Driver::findOrFail($id);
        //$driver->update($request->all());
        $driver = Driver::findOrFail($id);
        $driver->fill($request->except(['password'])); // Fill all fields except password
        if ($request->filled('password')) {
            $driver->password = Hash::make($request->password);
        }
        $driver->save();
        
        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully.');
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully.');
    }
}

