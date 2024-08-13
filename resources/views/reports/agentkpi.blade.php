@extends('layouts.app')

@section('content')
<style>
    table, td, tr, th {
        border: solid 1px
    }.text-end {
    text-align: right !important;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold">Agent KPI</h4>
                </div>  
                
            </div>
            <div class="col-lg-12"> 
  
  <form action="{{  route('handle.form')  }}" method="POST" class="row g-3"> 
  @csrf
        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold text-muted text-uppercase" for="driver_id">FV Agent:</label>
            <select class=" form-control "name="driver_id">
                <option value="" selected>Select All</option>
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->username }}
                    </option>
                @endforeach
            </select>
        </div> 

      <div class="col-md-4 mb-3">
      <div class="form-group">
            <label for="exampleInputdate">From</label>
            <input type="date" class="form-control" id="exampleInputdate" name= "start_date" value="{{ old('start_date', $startDate ?? $startOfWeek) }}">
      </div>
      </div>
      <div class="col-md-4 mb-3">
      <div class="form-group">
        <label for="exampleInputdate">To</label>
        <input type="date" class="form-control" id="exampleInputdate" name ="end_date" value="{{ old('end_date', $endDate ?? $endOfWeek) }}">
      </div>
      </div>  
      <div class="col-md-4 mb-3">  
        <button type="submit" name="action" value="filter" class="btn btn-primary">Filter</button>
        <button type="submit" name="action" value="export" class="btn btn-secondary btn-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                </svg>Export to Excel</button>
      </div>  
  </form>
</div>                   
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block card-stretch">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center p-3"> 
                            
                            </div>
                            <div class="table-responsive"> 
                            <table class="table data-table mb-0"  data-ordering="false">
                                    <thead class="table-color-heading">
                                    <tr class="text-light">
                                        <th rowspan="2"><label class="text-muted mb-0" >No</label></th>
                                        <th rowspan="2"> <label class="text-muted mb-0" >Agent</label></th>
                                        <th rowspan="2"> <label class="text-muted mb-0" >File</label></th>
                                        <th colspan="3" class="text-center"> <label class="text-muted mb-0" >No. of Accounts</label></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="border: solid 1px;">Assigned</th>
                                        <th  class="text-center"style="border: solid 1px;">Completed</th>
                                        <th  class="text-center" style="border: solid 1px;">Incomplete</th>
                                    </tr>
                                          </thead>
                                        <tbody> @php
            $grandTotalAssigned = 0;
            $grandTotalCompleted = 0;
            $grandTotalIncomplete = 0;
        @endphp
                                        @foreach ($groupedBatchDetails as $key=>$batch)
                                        @php
                                            $totalAssigned = 0;
                                            $totalCompleted = 0;
                                            $totalIncomplete = 0;
                                        @endphp 
                <tr>
                <td style="border: solid 1px;" rowspan="{{ count($batch['batches']) + 1 }}">{{ $loop->iteration }}</td>
                <td style="border: solid 1px;    text-transform: uppercase;
" rowspan="{{ count($batch['batches']) + 1 }}">{{ $batch['driver_name'] }}</td>

                @foreach ($batch['batches'] as $batch)
              
                    @if ($loop->first)
                        <td style="border: solid 1px;">{{ $batch['batch_no'] }}</td>
                        <td style="border: solid 1px;" class="text-center">{{ $batch['assigned_count'] }}</td>
                        <td style="border: solid 1px;" class="text-center">{{ $batch['completed_count'] }}</td>
                        <td style="border: solid 1px;" class="text-center">{{ $batch['pending_count'] }}</td>
                    @else
                        <tr>
                            <td>{{ $batch['batch_no'] }}</td>
                            <td class="text-center">{{ $batch['assigned_count'] }}</td>
                            <td class="text-center">{{ $batch['completed_count'] }}</td>
                            <td class="text-center">{{ $batch['pending_count'] }}</td>
                        </tr>
                    @endif
                    @php
                        $totalAssigned += $batch['assigned_count'];
                        $totalCompleted += $batch['completed_count'];
                        $totalIncomplete += $batch['pending_count'];
                    @endphp
                    @endforeach      
                 <tr>
                    <td class="text-end font-weight-bold" style="background: rgba(0,0,0,0.1);">TOTAL</td>
                    <td class="text-center font-weight-bold" style="background: rgba(0,0,0,0.1);">{{ $totalAssigned }}</td>
                    <td class="text-center font-weight-bold" style="background: rgba(0,0,0,0.1);">{{ $totalCompleted }}</td>
                    <td class="text-center font-weight-bold" style="background: rgba(0,0,0,0.1);">{{ $totalIncomplete }}</td>
                </tr> @php
                    $grandTotalAssigned += $totalAssigned;
                    $grandTotalCompleted += $totalCompleted;
                    $grandTotalIncomplete += $totalIncomplete;
                @endphp
                                        @endforeach
                                        <tr>
            <td class="text-end font-weight-bold" colspan="3" style="background: rgba(0,0,0,0.3);">GRAND TOTAL</td>
            <td class="text-center font-weight-bold" style="background: rgba(0,0,0,0.3);">{{ $grandTotalAssigned }}</td>
            <td class="text-center font-weight-bold" style="background: rgba(0,0,0,0.3);">{{ $grandTotalCompleted }}</td>
            <td class="text-center font-weight-bold" style="background: rgba(0,0,0,0.3);">{{ $grandTotalIncomplete }}</td>
        </tr>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

 
@endsection 

