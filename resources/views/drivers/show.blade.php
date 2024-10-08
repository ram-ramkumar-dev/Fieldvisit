@extends('layouts.app')

@section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('drivers.index') }}">Drivers</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Show Driver</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('drivers.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Back</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">Show Driver</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <div class="col-md-6 mb-3">
                        <strong>Full Name:</strong>
                        {{ $driver->name }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>IC Number:</strong>
                        {{ $driver->ic_number }}
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>Phone Number:</strong>
                        {{ $driver->phone_number }}
                        </div>
                               
                    
                    <div class="col-md-6 mb-3">
                        <strong>Username:</strong>
                        {{ $driver->username }}
                    </div>
                               
                            
                               
                    
                    <div class="col-md-6 mb-3">
                        <strong>FV Login:</strong>
                        {{ $driver->app_login == '1' ? 'Yes' : 'No' }}

                    </div>
                              
                               
                    
                    <div class="col-md-6 mb-3">
                        <strong>Display Sensitive Detail:</strong>
                        {{  $driver->sensitive }}
                    </div>  
                              
                    
                    <div class="col-md-6 mb-3">
                        <strong>Module Access:</strong>
                        {{ implode(', ', (array) $driver->permissions) }}
                    </div>
                               
                    
                    <div class="col-md-6 mb-3">
                        <strong>Supervisor Configuration:</strong>
                        {{ implode(', ', (array) $driver->supervisor) }}
                    </div>
                                          
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
