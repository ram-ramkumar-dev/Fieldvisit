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
                                <li class="breadcrumb-item active" aria-current="page">Edit Client</li>
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
                <h4 class="font-weight-bold d-flex align-items-center">Edit Client</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                        <form action="{{ route('clients.update', $client->id) }}" method="POST"  class="row g-3">
                            @csrf
                            @method('PUT') 
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="client_name">Name:</label>
                                <input type="text" class="form-control" name="client_name" value="{{ $client->client_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="clientgroup_id">Client Group:</label>
                                <select class="form-control" name="clientgroup_id" required>
                                    @foreach ($clientgroups as $clientgroup)
                                        <option value="{{ $clientgroup->id }}" {{ $client->clientgroup_id == $clientgroup->id ? 'selected' : '' }}>{{ $clientgroup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="registration_no">Registration No:</label>
                                <input type="text" class="form-control" name="registration_no" value="{{ $client->registration_no }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="address">Address:</label>
                                <textarea class="form-control" name="address" required>{{ $client->address }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="city">City:</label>
                                <input type="text" class="form-control" name="city" value="{{ $client->city }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="state">State:</label>
                                <select id="state" class=" form-control " name="state" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option {{ $client->state == $state->id ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->state_name }}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="postcode">Postcode:</label>
                                <input type="text" class="form-control" name="postcode" value="{{ $client->postcode }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="phone1">Phone 1:</label>
                                <input type="text" class="form-control" name="phone1" value="{{ $client->phone1 }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="phone2">Phone 2:</label>
                                <input type="text" class="form-control" name="phone2" value="{{ $client->phone2 }}">
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase"for="status">Status:</label>
                                <select required class=" form-control " name="status">
                                    <option value ="">Please Select</option>
                                    <option value ='1' {{ $client->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value ='0' {{ $client->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                        <div>{{ $message }}</div>
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
