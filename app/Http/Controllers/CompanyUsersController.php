<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class CompanyUsersController extends Controller
{
    public function index()
    {
        $page = "CompanyUsers";
        $companyusers =  User::with('company')->where('groups', '!=', '1')->get(); 
        return view('companyusers.index', compact('companyusers', 'page'));
    }

    public function create()
    {   
        $page = "CompanyUsers"; 
        $companies = Company::get(); 
        return view('companyusers.create',  compact('companies', 'page'));
    }

    public function store(Request $request)
    { 
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
            'company_id' => 'required'
        ]);

        $user = new User([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $request->company_id
        ]);
        
        $user->save();

        return redirect()->route('companyusers.index')->with('success', 'Company User created successfully.'); 
    }

    public function show(User $companyuser)
    {
        $page = "CompanyUsers";
        return view('companyusers.show', compact('companyuser', 'page'));
    }

    public function edit(User $companyuser)
    {
        $page = "CompanyUsers"; 
        $companies = Company::get(); 
        return view('companyusers.edit', compact('companyuser', 'companies', 'page'));
    }

    public function update(Request $request, User $companyuser)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $companyuser->id,
            'username' => 'required|unique:users,username,' . $companyuser->id, 
            'company_id' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ]); 

        // Update user except for password
        $companyuser->fill($request->except(['password']));

        // Check if password is filled and update
        if ($request->filled('password')) {
            $companyuser->password = Hash::make($request->password);
        }

        // Save the updated user
        $companyuser->save();

        return redirect()->route('companyusers.index')->with('success', 'Company User updated successfully.');
    }

    public function destroy(User $companyuser)
    {
        $companyuser->delete();
        return redirect()->route('companyusers.index')->with('success', 'Company User deleted successfully.');
    }
}
