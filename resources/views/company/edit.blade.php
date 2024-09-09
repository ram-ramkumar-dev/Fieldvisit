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
                                <li class="breadcrumb-item active" aria-current="page">Edit Company</li>
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
                <h4 class="font-weight-bold d-flex align-items-center">Edit Company</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                        <form action="{{ route('companies.update', $company->id) }}" method="POST"  class="row g-3">
                        @csrf
                        @method('PUT')
                       
                        <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="company_name">Name:</label>
                                <input type="text" class="form-control" name="company_name" value="{{ $company->company_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="company_address">Address:</label>
                               
                                <textarea class="form-control" name="company_address" >{{ $company->company_address }}</textarea>
                            </div>
                       
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="company_city">City:</label>
                                <input type="text" class="form-control" name="company_city" value="{{ $company->company_city }}" required>
                            </div>
                       
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="company_state">State:</label>
                                <input type="text" class="form-control" name="company_state" value="{{ $company->company_state }}" required>
                            </div>
                       
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="company_postcode">Post Code:</label>
                                <input type="text" class="form-control" name="company_postcode" value="{{ $company->company_postcode }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="company_phone">Phone:</label>
                                <input type="number" class="form-control" name="company_phone" value="{{ $company->company_phone }}" required>
                            </div>
                       
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                        
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
