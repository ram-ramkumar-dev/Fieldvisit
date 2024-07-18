@extends('layouts.app')

@section('content') 
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Client</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('clients.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Back</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">New Client</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">Basic Information</h5> 
                        <form action="{{ route('clients.store') }}" method="POST"  class="row g-3"> 
                        @csrf 
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="client_name">Name:</label>
                            <input type="text" class="form-control" name="client_name" required value="{{ old('client_name') }}">
                            @error('client_name')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="client_group_id">Client Group:</label>
                            <select class="form-control" name="client_group_id">
                                <option value="">Please select</option>
                                @foreach ($clientgroups as $clientgroup)
                                    <option value="{{ $clientgroup->id }}">{{ $clientgroup->name }}</option>
                                @endforeach
                            </select>
                            @error('client_group_id')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="registration_no">Registration No:</label>
                            <input type="text" class="form-control" name="registration_no" required value="{{ old('client_name') }}">
                            
                            @error('registration_no')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="address">Address:</label>
                            <textarea class="form-control" name="address" required>{{ old('client_name') }}</textarea>
                            
                            @error('address')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="city">City:</label>
                            <input type="text" class="form-control" name="city" required value="{{ old('client_name') }}">
                            
                            @error('city')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="state">State:</label>
                            <input type="text" class="form-control" name="state" required value="{{ old('client_name') }}">
                            
                            @error('state')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="postcode">Postcode:</label>
                            <input type="text" class="form-control" name="postcode" required value="{{ old('client_name') }}">
                            
                            @error('postcode')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="phone1">Phone 1:</label>
                            <input type="text" class="form-control" name="phone1" required value="{{ old('phone1') }}">
                            
                            @error('phone1')
                                    <div>{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase"for="phone2">Phone 2:</label>
                            <input type="text" class="form-control" name="phone2"  value="{{ old('phone2') }}">
                        </div> 
                        <button type="submit" class="btn btn-primary">Submit</button>
                     </form> 
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection  