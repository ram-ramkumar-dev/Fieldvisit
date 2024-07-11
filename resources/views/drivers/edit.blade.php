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
                                <li class="breadcrumb-item active" aria-current="page">Edit Driver</li>
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
                <h4 class="font-weight-bold d-flex align-items-center">Edit Driver</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-3">Basic Information</h5> 
                        <form action="{{ route('drivers.update', $driver->id) }}" method="POST"  class="row g-3">
                        @csrf
                        @method('PUT')
                            <div class="col-md-6 mb-3">
                                <label for="Text1" class="form-label font-weight-bold text-muted text-uppercase">Full Name</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Full Name" value="{{ old('name', $driver->name) }}">
                                @error('name')
                                    <div>{{ $message }}</div>
                                @enderror
                            </div>
                             
                            <div class="col-md-6 mb-3">
                                <label for="Text2" class="form-label font-weight-bold text-muted text-uppercase">IC Number</label>
                                <input type="text" required class="form-control" id="ic_number"  name="ic_number" placeholder="Enter IC Number"  value="{{ old('ic_number', $driver->ic_number) }}">
                                @error('ic_number')
                                    <div>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text3" class="form-label font-weight-bold text-muted text-uppercase">Phone Number</label>
                                <input type="text" required class="form-control" id="phone_number"  name="phone_number" placeholder="Enter Phone Number"  value="{{ old('phone_number', $driver->phone_number) }}">
                                @error('phone_number')
                                    <div>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text4" class="form-label font-weight-bold text-muted text-uppercase">Username</label>
                                <input type="text" required class="form-control" autocomplete="off" name="username" id="username" value="{{ old('username', $driver->username) }}" placeholder="Enter Username">
                                @error('username')
                                    <div>{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text5" class="form-label font-weight-bold text-muted text-uppercase">Password</label>
                                <input  type="password"  name="password" autocomplete="off" id="password" class="form-control"   placeholder="Enter Password">
                                @error('password')
                                <div>{{ $message }}</div>
                                 @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text5"  class="form-label font-weight-bold text-muted text-uppercase">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"   placeholder="Enter Confirm Password" id="password_confirmation">
                                @error('password_confirmation')
                                    <div>{{ $message }}</div>
                                @enderror
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label for="Text7" class="form-label font-weight-bold text-muted text-uppercase">Allow FV Login</label> 
                                <input type="checkbox" {{ old('app_login', $driver->app_login) ? 'checked' : '' }} class="form-control" name="app_login" id="app_login" value="1">

                            </div>
                             
                            <div class="col-md-6 mb-3">
                                <label for="Text8" class="form-label font-weight-bold text-muted text-uppercase">Display Sensitive Detail</label>
                                <select name="sensitive" id="sensitive" class="form-control">
                                    <option {{ old('sensitive', $driver->sensitive) == "yes" ? 'selected' : '' }} value="yes">Yes</option> 
                                    <option {{ old('sensitive', $driver->sensitive) == "no" ? 'selected' : '' }} value="no">No</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text8" class="form-label font-weight-bold text-muted text-uppercase">Module Access</label>
                                <select name="permissions[]" id="permissions" class="multipleSelect2 form-control choicesjs" multiple="true">
                                    @php
                                        $permissions = old('permissions', $driver->permissions) ?: [];
                                    @endphp
                                    <option {{ in_array('setting', $permissions) ? 'selected' : '' }} value="setting">Setting</option>
                                    <option {{ in_array('fieldvisit', $permissions) ? 'selected' : '' }} value="fieldvisit">Field Visit</option>
                                    <option {{ in_array('report', $permissions) ? 'selected' : '' }} value="report">Report</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="Text8" class="form-label font-weight-bold text-muted text-uppercase">Supervisor Configuration</label>
                                <select name="supervisor[]" id="supervisor" class="multipleSelect2 form-control choicesjs" multiple="true">
                                    @php
                                        $supervisor = old('supervisor', $driver->supervisor) ?: [];
                                    @endphp
                                    @foreach($drivers as $driver)
                                    <option  {{ in_array($driver->name, $supervisor) ? 'selected' : '' }}  value="{{ $driver->name }}">{{ $driver->name }}</option> 
                                     @endforeach
                                </select>
                            </div>
 
                            <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-end mt-3"> 
                                <button  class="btn btn-primary" type="submit">Update</button>
                            </div>
                            </div> 
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection 