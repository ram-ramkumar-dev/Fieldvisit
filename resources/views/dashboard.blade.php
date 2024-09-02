@extends('layouts.app')

@section('content') 
<style>
   p.text-secondary, h5.font-weight-bold{
      color:white !important;
   }
   .table-container {
    overflow: auto; /* Enables both horizontal and vertical scrolling */
    max-height: 310px; /* Set a maximum height for vertical scrolling */
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead th { 
    text-align: left;
}

tbody td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

table th, table td {
    white-space: nowrap; /* Prevents text from wrapping */
}

   </style>
<div class="container-fluid">
   <div class="row">
      <div class="col-md-12 mb-4 mt-1"  style="display:none !important;">
         <div class="d-flex flex-wrap justify-content-between align-items-center">
             <h4 class="font-weight-bold" >Overview</h4>
             <div class="form-group mb-0 vanila-daterangepicker d-flex flex-row">
                  <div class="date-icon-set">
                     <input type="text" name="start" class="form-control" placeholder="From Date">
                     <span class="search-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                     </span>
                  </div>                  
                     <span class="flex-grow-0">
                     <span class="btn">To</span>
                  </span>
                  <div class="date-icon-set">
                     <input type="text" name="end" class="form-control" placeholder="To Date">
                     <span class="search-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                     </span>
                  </div>                  
            </div>
         </div>
      </div>
      <div class="col-lg-12 col-md-12">
         <div class="row"> 
             <div class="col-md-2">
               <div class="card" style="background: #80BCBD; ">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-2 text-secondary">Active Agent</p>
                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                               <h5 class="mb-0 font-weight-bold">{{ count($users) }}</h5>
                               <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                            </div>                            
                        </div>
                     </div>
                  </div>
               </div>   
            </div>
            <div class="col-md-2">
               <div class="card" style="background: #A9B388; ">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-2 text-secondary">Total Batch</p>
                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                               <h5 class="mb-0 font-weight-bold">{{ count($totalbatches) }}</h5>
                               <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                            </div>                            
                        </div>
                     </div>
                  </div>
               </div>   
            </div>
            <div class="col-md-2">
               <div class="card"  style="background: #86A789; ">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-2 text-secondary">Total Account</p>
                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                               <h5 class="mb-0 font-weight-bold">{{ $totalBatchDetailsCount  }}</h5>
                               <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                            </div>                            
                        </div>
                     </div>
                  </div>
               </div>   
            </div>
            <div class="col-md-2">
               <div class="card"  style="background: #C6A969; ">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-2 text-secondary">Completed</p>
                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                               <h5 class="mb-0 font-weight-bold">{{ $totalCompleted  }}</h5>
                               <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                            </div>                            
                        </div>
                     </div>
                  </div>
               </div>   
            </div>
            <div class="col-md-2">
               <div class="card"  style="background: #CD8D7A; ">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-2 text-secondary">In-Complete</p>
                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                               <h5 class="mb-0 font-weight-bold">{{ $totalPending  }}</h5>
                               <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                            </div>                            
                        </div>
                     </div>
                  </div>
               </div>   
            </div>
            <div class="col-md-2">
               <div class="card"   style="background: #FF8F8F; ">
                  <div class="card-body">
                     <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-2 text-secondary">Aborted</p>
                            <div class="d-flex flex-wrap justify-content-start align-items-center">
                               <h5 class="mb-0 font-weight-bold">{{ $totalAborted  }}</h5>
                               <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                            </div>                            
                        </div>
                     </div>
                  </div>
               </div>   
            </div>
            
         </div>
      </div>   
     
      <div class="col-lg-8 col-md-12">
         <div class="row">
          
            <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center flex-wrap">
                     <h4 class="font-weight-bold">Visit & Survey Statistics</h4>
                     <div class="d-flex justify-content-between align-items-center">
                        <div><svg width="24" height="24" viewBox="0 0 24 24" fill="primary" xmlns="../../../../www.w3.org/2000/svg.html">
                              <rect x="3" y="3" width="18" height="18" rx="2" fill="#3378FF" />
                              </svg>
                           <span>Visit</span>
                        </div>
                        <div class="ml-3"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="../../../../www.w3.org/2000/svg.html">
                                          <rect x="3" y="3" width="18" height="18" rx="2" fill="#19b3b3" />
                                          </svg>
                           <span>Completed</span>
                        </div>
                     </div>
                  </div>
                   <div id="chart-apex-column-01" class="custom-chart"></div>
                </div>
            </div>   
            </div>
         </div>
      </div>
      <div class="col-lg-4 col-md-8">
         <div class="card card-block card-stretch card-height" >
            <div class="card-header card-header-border d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Campaign Performance</h4>
               </div>
               <div class="card-header-toolbar d-flex align-items-center">                  
                  <div class="dropdown">
                        <a href="#" class="text-muted pl-3" id="dropdownMenuButton-event" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" xmlns:xlink="http://www.w3.org/1999/xlink" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                              <g fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <circle cx="12" cy="12" r="1"/>
                                 <circle cx="19" cy="12" r="1"/>
                                 <circle cx="5" cy="12" r="1"/></g>
                           </svg>
                        </a>
                        <div class="dropdown-menu" id="CampaignMenu" aria-labelledby="dropdownMenuButton-event"> 

                           <div class="dropdown-item" onclick="campaigncounts('all')">All</div> 
                           <div class="dropdown-item" onclick="campaigncounts('completed')">Completed</div> 
                           <div class="dropdown-item" onclick="campaigncounts('pending')">Pending</div> 
                           <div class="dropdown-item" onclick="campaigncounts('abort')">Aborted</div>
                           
                        </div>
                     </div>
               </div>
            </div>
            <div class="card-body-list" style="overflow: scroll;">               
            <ul class="list-style-3 mb-0" id="batchList" style="height: 275px;">
                  @foreach ($counts as $k => $batch) 
                     <li class="p-3 list-item d-flex flex-column align-items-start">
                           <div class="d-flex justify-content-start align-items-center w-100">
                              <div class="list-style-detail mr-2">
                                 <p class="mb-0">{{ ucfirst($batch->batch_no) }}</p>
                              </div>
                              <div class="list-style-action ml-auto">
                                 <h6 class="font-weight-bold" id="campaignnumbers">{{ $batch->batch_details_count }}</h6>
                              </div>
                           </div>
                           <div class="w-100">
                              <progress style="width: 100%;height: 5px;" id="file" value="{{ $batch->batch_details_count }}" max="{{ $batch->batch_details_count }}">{{ $batch->batch_details_count }}%</progress>
                           </div>
                     </li>
                  @endforeach
               </ul>
            </div>
         </div>
         
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-12">
               <div class="card bg-primary">
                   <div class="card-body">
                       <div class="d-flex align-items-center">
                           <div class="fit-icon-2 text-info text-center">
                               <div id="circle-progress-01" class="circle-progress-01 circle-progress circle-progress-light" data-min-value="0" data-max-value="100" data-value="{{     $percentage = ($totalCompleted / $totalBatchDetailsCount) * 100 }}" data-type="percent"></div>
                           </div>
                           <div class="ml-3">
                               <h5 class="text-white font-weight-bold">{{ $totalCompleted }} <small> / {{ $totalBatchDetailsCount }}</small></h5>
                               <small class="mb-0">Agents Performance</small>
                           </div>
                       </div>
                   </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold">Active Users</h6>
                        <div class="d-flex align-items-center"> 
                           <!-- <select>
                              <option>Select</option>
                              @foreach ($users as $k => $v) 
                                 <option value="{{ $v->id }}">{{ ucfirst($v->username) }}</option>
                              @endforeach
                           </select>  -->
                        </div>
                     </div>
                     <p class="mb-0">Visits Per Day</p>
                     <div id="chart-apex-column-02" class="custom-chart"></div>
                     <div class="d-flex justify-content-between align-items-center">
                     @foreach($lastFourDaysData['dates'] as $date)
                        <p class="mb-0 pt-3">{{ $date }}</p>
                     @endforeach
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-4 col-md-12">
       <div class="card leaderboard">
         <div class="leaderboard-header">
            <h4>Leaderboard</h4>
            <div class="driver-profile">
               <img src="{{ asset('assets/images/user/1.jpg') }}" alt="Driver Avatar">
               <div class="driver-info">
                  <h5 style="text-transform: uppercase;">
                        @php if ($topAgent['devicetoken']) { @endphp
                           <span class="d-flex align-items-center">
                              <svg xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="8" fill="#3cb72c"></circle>
                              </svg>
                              {{ $topAgent['driver_name'] }}
                           </span>
                        @php } else { @endphp
                           {{ $topAgent['driver_name'] }}
                        @php } @endphp
                  </h5>

                  @php
                  $width = $totalSurveys > 0 ? ($topAgent['completed'] / $totalSurveys) * 100 : 0;
                  @endphp

                  <div class="progress-bar">
                        <div class="progress" style="width: {{ $width }}%;"></div>
                  </div>

                  <span class="progress-count">{{ $topAgent['completed'] }}/{{ $totalSurveys }}</span>
               </div>
            </div>
         </div>  
         <div class="leaderboard-body">
         <div class="table-container">
            <table>
                  <thead>
                     <tr>
                        <th>No</th>
                        <th>Score</th>
                        <th>Full Name</th>
                        <th>Assign</th>
                        <th>Completed</th>
                        <th>Pending</th>
                     </tr>
                  </thead>
                  <tbody>
                        @foreach ($list as $k => $l)  
                     <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ number_format($l['score'], 0) }}%</td> <!-- Display the score -->
                        <td style="text-transform: uppercase;">
                           @php if ($l['devicetoken']) { @endphp
                           <span class="d-flex align-items-center">
                              <svg  xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="8" fill="#3cb72c"></circle>
                              </svg>
                              {{ $l['driver_name'] }}
                           </span>
                           @php } else { @endphp
                              {{ $l['driver_name'] }}
                           @php } @endphp
                        </td>
                        <td>{{ $l['assigned'] }}</td>
                        <td>{{ $l['completed'] }}</td>
                        <td>{{ $l['pending'] }}</td>
                     </tr>
                     @endforeach
                  </tbody>
            </table>
            </div>
         </div>
        </div>
         
      </div>
      <div class="col-lg-4 col-md-6">
         <div class="card">
           
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Batch Progress</h4>
               </div>
               <div class="card-header-toolbar d-flex align-items-center">                  
                  <div class="dropdown">
                        <a href="#" class="text-muted pl-3" id="dropdownMenuButton-event" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" xmlns:xlink="http://www.w3.org/1999/xlink" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                              <g fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <circle cx="12" cy="12" r="1"/>
                                 <circle cx="19" cy="12" r="1"/>
                                 <circle cx="5" cy="12" r="1"/></g>
                           </svg>
                        </a>
                        <div class="dropdown-menu" id="batchDropdownMenu" aria-labelledby="dropdownMenuButton-event">
                           @foreach ($counts as $k => $batch) 
                              <div class="dropdown-item" onclick="fetchDataAndUpdateChart({{ $batch->id }})">
                                 {{  ucfirst($batch->batch_no) }}
                              </div>
                           @endforeach
                           
                        </div>
                     </div>
               </div>
            </div>
            <div class="card-body"> 
               <div id="chart-apex-column-03" class="custom-chart"></div>
               <div class="d-flex justify-content-around align-items-center">
                  <div><svg width="24" height="24" viewBox="0 0 24 24" fill="#ffbb33" xmlns="../../../../www.w3.org/2000/svg.html">
                        <rect x="3" y="3" width="18" height="18" rx="2" fill="#ffbb33" />
                        </svg>
                        
                        <span>Progress</span>
                  </div>
                  <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#e60000" xmlns="../../../../www.w3.org/2000/svg.html">
                        <rect x="3" y="3" width="18" height="18" rx="2" fill="#e60000" />
                        </svg>
                        
                        <span>Aborted</span>
                  </div>
               </div>
               <div class="d-flex justify-content-around align-items-center mt-3">
                  <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="primary" xmlns="../../../../www.w3.org/2000/svg.html">
                        <rect x="3" y="3" width="18" height="18" rx="2" fill="#04237D" />
                        </svg>
                        
                        <span>Assigned</span>
                  </div>
                  <div>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="primary" xmlns="../../../../www.w3.org/2000/svg.html">
                        <rect x="3" y="3" width="18" height="18" rx="2" fill="#8080ff" />
                        </svg>
                        
                        <span>Completed</span>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-12 col-md-12">
         <div class="card">
            <div class="card-body"> 
               <div id="chart-map-column-04" class="custom-chart"></div>
            </div>
         </div>
      </div>
      
      <div class="col-lg-4 col-md-6"  style="display:none;">
         <div class="card card-block card-stretch card-height">
            <div class="card-header d-flex justify-content-between">
               <div class="header-title">
                  <h4 class="card-title">Upcoming Events</h4>
               </div>
               <div class="card-header-toolbar d-flex align-items-center">                  
                  <div class="dropdown">
                        <a href="#" class="text-muted pl-3" id="dropdownMenuButton-event" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" xmlns:xlink="http://www.w3.org/1999/xlink" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                              <g fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                 <circle cx="12" cy="12" r="1"/>
                                 <circle cx="19" cy="12" r="1"/>
                                 <circle cx="5" cy="12" r="1"/></g>
                           </svg>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-event">
                           <a class="dropdown-item" href="#">
                                 <svg class="svg-icon text-secondary" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                 </svg>
                                 Edit
                           </a>
                           <a class="dropdown-item" href="#">
                                 <svg class="svg-icon text-secondary" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                 </svg>
                                 View
                           </a>
                           <a class="dropdown-item" href="#">
                                 <svg class="svg-icon text-secondary" width="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                 </svg>
                                 Delete
                           </a>
                        </div>
                     </div>
               </div>
            </div>
            <div class="card-body p-0">
               <div class="table-responsive">
                  <table class="table table-spacing mb-0">
                     <tbody>
                        <tr class="white-space-no-wrap">
                           <td>
                              <h6 class="mb-0 text-uppercase text-secondary">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="pr-2" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                 </svg>
                                 30 Jun, Tue</h6>
                           </td>
                           <td class="pl-0 py-3">
                           Big Billion Day Sale
                           </td>
                        </tr>
                        <tr class="white-space-no-wrap">
                           <td>
                              <h6 class="mb-0 text-uppercase text-secondary">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="pr-2" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                 </svg>
                                 09 July, Mon</h6>
                           </td>
                           <td class="pl-0 py-3">
                              5% Off on Mobile
                           </td>
                        </tr>
                        <tr class="white-space-no-wrap">
                           <td>
                              <h6 class="mb-0 text-uppercase text-secondary">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="pr-2" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                 </svg>
                                 15 Aug, Sun</h6>
                           </td>
                           <td class="pl-0 py-3">
                           Electronics Sale 
                           </td>
                        </tr>
                        <tr class="white-space-no-wrap">
                           <td>
                              <h6 class="mb-0 text-uppercase text-secondary">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="pr-2" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                 </svg>
                                 26 Oct, Thu</h6>
                           </td>
                           <td class="pl-0 py-3">
                           Fashionable Sale
                           </td>
                        </tr>
                        <tr class="white-space-no-wrap">
                           <td>
                              <h6 class="mb-0 text-uppercase text-secondary">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="pr-2" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                 </svg>
                                 25 Dec, Fri</h6>
                           </td>
                           <td  class="pl-0 py-3">
                              5% Off on Cloths
                           </td>
                        </tr>                        
                     </tbody>
                  </table>
                  
               </div>
               <div class="d-flex justify-content-end align-items-center border-top-table p-3">
                     <button class="btn btn-secondary btn-sm">See All</button>
                  </div>
            </div>
         </div>
      </div>
      
</div>
<style>
.leaderboard { 
    border-radius: 10px;
    padding: 20px; 
}

.leaderboard-header {
    text-align: center;
}

.driver-profile {
    display: flex;
    align-items: center;
    justify-content: center;
}

.driver-profile img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.driver-info {
    margin-left: 10px;
}

.driver-info h3 {
    margin: 0;
}

.progress-bar {
    background-color: #e0e0e0;
    border-radius: 10px;
    width: 100px;
    height: 10px;
    position: relative;
}

.progress {
    background-color: #4caf50;
    height: 100%;
    border-radius: 10px;
}

.progress-count {
    font-size: 12px;
    margin-top: 5px;
}

.leaderboard-body {
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    text-align: center;
    padding: 10px;
}

thead { 
    font-weight: bold;
}

tbody tr { 
    border-bottom: 1px solid #dddddd;
}

tbody tr:nth-child(even) { 
}

tbody tr.green {
    background-color: #d9f5d9;
}

tbody tr.yellow {
    background-color: #fffdd0;
}

tbody tr.red {
    background-color: #fdd9d9;
}
.score-circle {
    display: inline-block;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    color: white;
    line-height: 30px;
    font-weight: bold;
    text-align: center;
}
   </style>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('assets/js/charts/chartupdates.js')}}"></script> 
<script>
var drivers = @json($users);
var chartData03 = {
    total: <?php echo $totalBatchDetailsCount; ?>,
    completed: <?php echo $totalCompleted; ?>,
    pending: <?php echo $totalPending; ?>,
    aborted: <?php echo $totalAborted; ?>,
    assign: <?php echo $totalAssigned; ?>
};

var chart01completed = @json($chart01completed);
var chart01visit = @json($chart01visit);
var chart01months = @json($chart01months);

var chartData = @json($lastFourDaysData);

var getBatchforchartData03 = '{{ route("getBatchProgressForChart03") }}';

</script>
<script>

       function campaigncounts(filter){ 
            var color;
            if(filter == 'completed'){
               color = '#3cb72c';
            }else if(filter == 'pending'){
               color = '#ffbb33';
            }else if(filter == 'abort'){
               color = '#e60000';
            }else{
               color = '';
            } 
            $.ajax({
                url: '{{ route("getCampaignPerformance") }}', // Your API endpoint
                type: 'GET',
                data: { filter: filter },
                success: function(response) {
                    updateBatchList(response.batches, color);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching batch counts:', error);
                }
            });
        }

        function updateBatchList(batches, color) { 
           // Hide the dropdown menu
           var dropdownMenu = document.getElementById('CampaignMenu');
            if (dropdownMenu) {
                dropdownMenu.classList.remove('show');
            }
            $('#batchList').empty(); 
            batches.forEach(function(batch, index) { 
            var listItem = `
               <li class="p-3 list-item d-flex flex-column align-items-start" data-batch-id="${batch.id}">
                     <div class="d-flex justify-content-start align-items-center w-100">
                        <div class="list-style-detail mr-2">
                           <p class="mb-0" style="color: ${color};">${batch.batch_no.charAt(0).toUpperCase() + batch.batch_no.slice(1)}</p>
                        </div>
                        <div class="list-style-action ml-auto">
                           <h6 class="font-weight-bold" style="color: ${color};" id="campaignnumbers">${batch.count}</h6>
                        </div>
                     </div>
                     <div class="w-100">
                        <progress style="accent-color: ${color}; width: 100%; height: 5px;" id="file" value="${batch.count}" max="${batch.count}">${batch.count}%</progress>
                     </div>
               </li>`;
            
            $('#batchList').append(listItem);
         });
        }
</script>
@endsection
    <!-- Page end  -->