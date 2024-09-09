@extends('layouts.app')

@section('content') 
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('statuses.index') }}">Status</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Status</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('statuses.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Back</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">New Status</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                        <form action="{{ route('statuses.store') }}" method="POST"  class="row g-3">
                        @csrf 
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="statuscode">Code:</label>
                            <input type="text" class="form-control" name="statuscode" required value="{{ old('statuscode') }}">
                            @error('statuscode')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="description">Description:</label>
                            <textarea class="form-control" name="description" >{{ old('description') }}</textarea>
                            
                            @error('description')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                      
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection  