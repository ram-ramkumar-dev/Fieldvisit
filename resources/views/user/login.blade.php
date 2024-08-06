@extends('app')
@section('content')

<section class="login-content">
         <div class="container h-100">
            <div class="row align-items-center justify-content-center h-100">
               <div class="col-md-5">
                  <div class="card p-3">
                     <div class="card-body">
                        <div class="auth-logo">
                           <img src="../assets/images/V-Ranger_350x150.png" class="img-fluid  rounded-normal  darkmode-logo" alt="logo">
                           <img style="background:black;" src="../assets/images/V-Ranger_350x150.png" class="img-fluid rounded-normal light-logo" alt="logo">
                        </div>
                        <h3 class="mb-3 font-weight-bold text-center">Sign In</h3>
                        <p class="text-center text-secondary mb-4">Log in to your account to continue</p>
                        @if(session('success'))
                        <p class="alert alert-success">{{ session('success') }}</p>
                        @endif
                        @if($errors->any())
                        @foreach($errors->all() as $err)
                        <p class="alert alert-danger">{{ $err }}</p>
                        @endforeach
                        @endif  
                        <form action="{{ route('login.action') }}" method="POST">
                         @csrf
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="form-group">
                                    <label class="text-secondary">Username <span class="text-danger">*</span></label>
                                    <input class="form-control" type="username" name="username" value="{{ old('username') }}"  placeholder="Enter Email">
                                 </div>
                              </div>  
                              <div class="col-lg-12 mt-2">
                                 <div class="form-group">
                                     <div class="d-flex justify-content-between align-items-center">
                                         <label class="text-secondary">Password <span class="text-danger">*</span></label>
                                     </div>
                                    <input class="form-control" type="password" name="password"  placeholder="Enter Password">
                                 </div>
                              </div>
                           </div>
                           <button type="submit" class="btn btn-primary btn-block mt-2">Log In</button> 
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>