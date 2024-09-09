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
                                <li class="breadcrumb-item active" aria-current="page">Add Batch</li>
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
                <h4 class="font-weight-bold d-flex align-items-center">New Batch</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                        <form action="{{ route('batches.store') }}" method="POST"  class="row g-3">
                        @csrf 
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="client_id">Client:</label>
                            <select required class="form-control" name="client_id">
                                <option value="">Please select</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="batch_no">Batch No:</label>
                            <input type="text" class="form-control" name="batch_no" required value="{{ old('batch_no') }}">
                            @error('batch_no')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="status_code">DR Code:</label>
                            <select required class="multipleSelect2 form-control choicesjs" multiple="true" name="status_code[]">
                                <option value="All">Select All</option>
                                @foreach ($status as $stat)
                                    <option value="{{ $stat->id }}">{{ $stat->statuscode }}-{{ $stat->description }}</option>
                                @endforeach
                            </select>
                            @error('status_code')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                        </div>
                        
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="status">Status:</label>
                            <select required class=" form-control " name="status">
                                <option value ="">Please Select</option>
                                <option value ='1'>Active</option>
                                <option value ='0'>Inactive</option>
                            </select>
                            @error('status')
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