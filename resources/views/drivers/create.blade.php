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
                                <li class="breadcrumb-item active" aria-current="page">Add Driver</li>
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
                <h4 class="font-weight-bold d-flex align-items-center">New Driver</h4>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body"> 
                        <form autocomplete="off" action="{{ route('drivers.store') }}" method="POST"  class="row g-3"> 
                        @csrf
                            <div class="col-md-6 mb-3">
                                <label for="Text1" class="form-label font-weight-bold text-muted text-uppercase">Full Name</label>
                                <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Full Name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                            </div>
                             
                            <div class="col-md-6 mb-3">
                                <label for="Text2" class="form-label font-weight-bold text-muted text-uppercase">IC Number</label>
                                <input type="text" required class="form-control" id="ic_number"  name="ic_number" placeholder="Enter IC Number"  value="{{ old('ic_number') }}">
                                @error('ic_number')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text3" class="form-label font-weight-bold text-muted text-uppercase">Phone Number</label>
                                <input type="text" required class="form-control" id="phone_number"  name="phone_number" placeholder="Enter Phone Number"  value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text4" class="form-label font-weight-bold text-muted text-uppercase">Username</label>
                                <input type="text" required class="form-control" autocomplete="off" name="username" id="username" value="{{ old('username') }}" placeholder="Enter Username" style="text-transform: uppercase;">
                                @error('username')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text5" class="form-label font-weight-bold text-muted text-uppercase">Password</label>
                                <input  type="password" required name="password" autocomplete="off" id="password" class="form-control"   placeholder="Enter Password">
                                @error('password')
                                <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                 @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="Text5" required class="form-label font-weight-bold text-muted text-uppercase">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"   placeholder="Enter Confirm Password" id="password_confirmation">
                                @error('password_confirmation')
                                    <div class="alert alert-danger"><p>{{ $message }}</p></div>
                                @enderror
                            </div> 
                            <div class="col-md-6 mb-3">
                                <label for="app_login" class="form-label font-weight-bold text-muted text-uppercase">Allow FV Login</label>
                                <input type="hidden" name="app_login" value="0">
                                <input type="checkbox" class="form-control" name="app_login" id="app_login" value="1" {{ old('app_login') ? 'checked' : '' }}>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sensitive" class="form-label font-weight-bold text-muted text-uppercase">Display Sensitive Detail</label>
                                <select name="sensitive" id="sensitive" class="form-control">
                                    <option value="Yes" {{ old('sensitive') == "Yes" ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('sensitive') == "No" ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="permissions" class="form-label font-weight-bold text-muted text-uppercase">Module Access</label>
                                
                                <input type="hidden" name="permissions[]" value="null">
                                <select name="permissions[]" id="permissions" class="multipleSelect2 form-control choicesjs" multiple="true">
                                    <option value="setting" {{ in_array('setting', old('permissions', [])) ? 'selected' : '' }}>Setting</option>
                                    <option value="adminstration" {{ in_array('adminstration', old('permissions', [])) ? 'selected' : '' }}>Administration</option>
                                    <option value="report" {{ in_array('report', old('permissions', [])) ? 'selected' : '' }}>Report</option>  
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="Text8" class="form-label font-weight-bold text-muted text-uppercase">Supervisor Configuration</label>
                                <input type="hidden" name="supervisor[]" value="null">
                                <select name="supervisor[]" id="supervisor" class="multipleSelect2 form-control choicesjs" multiple="true" style="text-transform: uppercase;">
                                @foreach($drivers as $driver) 
                                    <option value="{{ $driver->name }}" {{ in_array($driver->name, old('supervisor', [])) ? 'selected' : '' }}>
                                      {{ $driver->name }}
                                    </option>
                                @endforeach
                                </select>
                            </div> 
                            <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-end mt-3"> 
                                <button  class="btn btn-primary" type="submit">Create</button>
                            </div>
                            </div> 
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection 