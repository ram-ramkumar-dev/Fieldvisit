<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:drivers',
            'username' => 'required|unique:drivers',
            'password' => 'required',
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
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:drivers,email,' . $id,
            'username' => 'required|unique:drivers,email,' . $id,
            'password' => 'nullable',

        ]);

        //$driver = Driver::findOrFail($id);
        //$driver->update($request->all());
        $driver = Driver::findOrFail($id);
        $driver->fill($request->all());
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

