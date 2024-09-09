<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    public function index()
    {
        $page = "Company";  
        $companies = Company::get(); 
        return view('company.index', compact('companies', 'page'));
    }

    public function create()
    {   
        $page = "Company";
        return view('company.create',  compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|unique:companies,company_name',
            'company_address' => 'required',
            'company_city' => 'required',
            'company_state' => 'required',
            'company_postcode' => 'required',
        ]); 
        $data = $request->all(); 
        Company::create($data);  
        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function show(Company $company)
    {
        $page = "Company";
        return view('company.show', compact('company', 'page'));
    }

    public function edit(Company $company)
    {
        $page = "Company";
        return view('company.edit', compact('company', 'page'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'company_name' => 'required'
        ]);

        $company->update($request->all());
        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
