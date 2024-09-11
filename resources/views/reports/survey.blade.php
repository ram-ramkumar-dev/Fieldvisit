@extends('layouts.app')

@section('content') 
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold">Survey Result</h4>
                </div>  
                
            </div>
            <div class="col-lg-12"> 
                
                <form action="{{  route('reports.generate')  }}" method="POST" class="row g-3"> 
                @csrf
                <input type="hidden" name="action" id="action" value="generate">
                <div class="row">
                    <div class="col-md-4 mb-3">
                    <label for="client">Client:</label>
                    <select class="form-control" name="client" id="client">
                        <option value="">Select Client</option>
                        @foreach ($clients as $client)
                            <option {{ request('client') == $client->id ? 'selected' : '' }} value="{{ $client->id }}">{{ ucfirst($client->client_name) }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col-md-4 mb-3">
                    <label for="batches">Batches:</label>
                    <select class="form-control" name="batches" id="batches">
                        <option value="">Select Batch</option>
                        @foreach ($batches as $batch)
                            <option {{ request('batches') == $batch->id ? 'selected' : '' }} value="{{ $batch->id }}">{{ ucfirst($batch->batch_no) }}</option>
                        @endforeach
                    </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                    <label for="agent">Agent:</label>
                    <select class="form-control" name="agent" id="agent">
                        <option value="">Select Agent</option>
                        @foreach ($agents as $agent)
                            <option {{ request('agent') == $agent->id ? 'selected' : '' }} value="{{ $agent->id }}">{{ ucfirst($agent->username) }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase" for="driver_id">Load Coulmn:</label>
                            <select class="multipleSelect2 form-control choicesjs" multiple="true" name="columns[]">
                                            <option selected {{ in_array('all', request('columns', [])) ? 'selected' : '' }} value="all">((Select All))</option>
                                            @foreach ($columnDisplayNames as $key=>$column)
                                            
                                                <option {{ in_array($key, request('columns', [])) ? 'selected' : '' }}  value="{{ $key }}">{{ $column }}</option>
                                            @endforeach
                                        </select>
 
                        </div>
                    <div class="col-md-4 mb-3">
                    <label for="batches">Account Status:</label>
                    <select class="form-control form-select form-select-sm" name="status" id="status">
                                <option {{ request('status') == "All" ? 'selected' : '' }} value="All">All</option>
                                 
                                    <option {{ request('status') == "New" ? 'selected' : '' }} value="New">New</option>  
                                 
                                    <option {{ request('status') == "Pending" ? 'selected' : '' }} value="Pending">Pending</option>  
                                 
                                    <option {{ request('status') == "Completed" ? 'selected' : '' }} value="Completed">Completed</option>  
                                 
                                    <option {{ request('status') == "Aborted" ? 'selected' : '' }} value="Aborted">Aborted</option>  
                                                            </select>
                    </div>
                 
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                                <label for="district">District</label>
                                <input type="text" class="form-control" id="district" name= "district" value="{{ request('district') }}">
                        </div>
                        </div>
                        
                    <div class="col-md-4 mb-3">
                    <label for="batchstatus">Status:</label>
                    <select class="form-control" name="batchstatus" id="batchstatus">
                        <option value="">Select Status</option>
                        @foreach ($batchstatus as $status)
                            <option {{ request('batchstatus') == $status->id ? 'selected' : '' }} value="{{ $status->id }}">{{ ucfirst($status->description) }}</option>
                        @endforeach
                    </select>
                    </div>
                        <div class="col-md-4 mb-3">
                        <div class="form-group">
                                <label for="exampleInputdate">From</label>
                                <input type="date" class="form-control" id="exampleInputdate" name= "start_date" value="{{ request('start_date') }}">
                        </div>
                        </div>
                        <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="exampleInputdate">To</label>
                            <input type="date" class="form-control" id="exampleInputdate" name ="end_date" value="{{ request('end_date') }}">
                        </div>
                        </div>  
                        <div class="col-md-4 mb-3">
                         <div style="margin-top:7%">       
                        <button type="button" onclick="location.href='{{ route('reports.surveyresult') }}'" class=" btn btn-warning">Reset</button>

                            <button type="submit" onclick="document.getElementById('action').value='generate'"   class="btn btn-primary">Search</button>
                            <button type="submit" onclick="document.getElementById('action').value='export'"    class="btn btn-secondary btn-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                </svg>Export to Excel</button>
                      </div>   </div> 
                </div>
                   
            </form>
            </div>                   
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block card-stretch">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center p-3"> 
                            
                            </div>
                            @if (isset($reportData))

                            <div class="table-responsive"> 
                            <table class="table data-table mb-0"  data-ordering="false">
                                    <thead class="table-color-heading">
                                    <tr>
                                        @foreach ($columns as $column)
                                        <th>{{ $columnDisplayNames[$column] ?? ucfirst(str_replace('_', ' ', $column)) }}</th>

                                        @endforeach
                                    </tr>
                                    </thead>
                                        <tbody>
                                        @foreach($reportData as $data)
            <tr>
                @foreach($columns as $column)
                    @if(str_starts_with($column, 'photo'))
                        <td>
                            @if($data->$column)
                                <img src="{{ asset($data->$column) }}" alt="{{ $columnDisplayNames[$column] ?? 'Photo' }}" style="max-width: 150px; height: auto;">
                                <br>
                                <p>{{ basename($data->$column) }}</p>
                            @else
                                -
                            @endif
                        </td>
                    @else
                        <td>{{ $data->$column ?? '-' }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
            </tbody>
                                </table>
                            </div>
                            @endif

                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready( function () {
    $('.data-table').DataTable({
        "pageLength": 100
    });
});
    </script>
@endsection 

