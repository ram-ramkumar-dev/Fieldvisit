@extends('layouts.app')

@section('content')
 <style>
 .choices__inner{
    height: 10px;    overflow: scroll;
 }
</style>
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

                    <div class="col-md-4 mb-3">
                    <label for="batches">Batches:</label>
                    <select class="form-control" name="batches" id="batches">
                        <option value="">Select Batch</option>
                        @foreach ($batches as $batch)
                            <option {{ $requestbatches == $batch->id ? 'selected' : '' }} value="{{ $batch->id }}">{{ $batch->batch_no }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold text-muted text-uppercase" for="driver_id">Load Coulmn:</label>
                            <select class="multipleSelect2 form-control choicesjs" multiple="true" name="columns[]">
                                            <option value="all">((Select All))</option>
                                            @foreach ($columnDisplayNames as $key=>$column)
                                            
                                                <option {{ in_array($key, $columns) ? 'selected' : '' }} value="{{ $key }}">{{ $column }}</option>
                                            @endforeach
                                        </select>

                        </div>
                        <div class="col-md-4 mb-3">
                                
                        <button type="button" onclick="location.href='{{ route('reports.surveyresult') }}'" class=" btn btn-warning">Reset</button>

                            <button type="submit" onclick="document.getElementById('action').value='generate'"   class="btn btn-primary">Search</button>
                            <button type="submit" onclick="document.getElementById('action').value='export'"    class="btn btn-secondary btn-sm">
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
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>

 
@endsection 

