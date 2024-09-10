@extends('layouts.superadmin')

@section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Companies</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Show Company</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('companies.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Back</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">Show Status</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        {{ $company->company_name }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Address:</strong>
                        {{ $company->company_address }}
                    </div>
                                      
                    <div class="col-md-6 mb-3">
                        <strong>City:</strong>
                        {{ $company->company_city }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>State:</strong>
                        {{ $company->company_state }}
                    </div>
                                     
                    <div class="col-md-6 mb-3">
                        <strong>Post Code:</strong>
                        {{ $company->company_postcode }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        {{ $company->company_phone }}
                    </div> 
                                       
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection