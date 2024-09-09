@extends('layouts.superadmin')

@section('content') 
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                   <div class="d-flex align-items-center justify-content-between">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('companyusers.index') }}">Company User</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Company User</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('companyusers.index') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Back</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-12 mb-3 d-flex justify-content-between">
                <h4 class="font-weight-bold d-flex align-items-center">New Company User</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                    @if($errors->any())
                    @foreach($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                    @endforeach
                    @endif
                    <form action="{{ route('companyusers.store') }}" method="POST"  class="row g-3">
                        @csrf  
                  
                             <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="name">Name:</label> 
                                <input class="form-control" type="text" name="name" required value="{{ old('name') }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="username">Username:</label>
                               
                                 <input class="form-control" type="username" required name="username" value="{{ old('username') }}"  style="text-transform: uppercase;"/>
                            </div>
                       
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="email">Email:</label>
                                <input class="form-control" type="email" required name="email" value="{{ old('email') }}" />
                            </div>
                       
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="password">Password:</label>
                                <input class="form-control" type="password" required name="password" />
                            </div>
                       
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted text-uppercase" for="password_confirm">Password Confirmation:</label>   
                                <input class="form-control" required type="password" name="password_confirm" />
                            </div> 
                       
                       <div class="col-md-6 mb-3">
                           <label class="form-label font-weight-bold text-muted text-uppercase" for="company_id">Company:</label>   
                           <select class="form-control" name="company_id" required>
                                <option value="">Please select</option>
                                @foreach ($companies as $comp)
                                    <option {{ old('company_id') == $comp->id ? 'selected' : '' }} value="{{ $comp->id }}">{{ $comp->company_name }}</option>
                                @endforeach
                            </select>
                       </div> 
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                      
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection  