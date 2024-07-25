@extends('layouts.app')

@section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('batches.index') }}">Batches</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Show Batch</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('batches.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Back</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">Show Batch</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    <div class="col-md-6 mb-3">
                        <strong>Batch No:</strong>
                        {{ $batch->batch_no }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Client:</strong>
                        {{ $batch->client->client_name }}
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <strong>DR Code:</strong>
                        {{ implode(', ', (array) $batch->status_code) }}
                        </div>
                               
                    
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        {{ $batch->status == '1' ? 'Active' : 'Inactive' }}
                    </div>
                               
                                        
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
