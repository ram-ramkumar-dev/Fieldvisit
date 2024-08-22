@extends('layouts.app')

@section('content') 
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold">Survey Photos</h4>
                </div>  
                
            </div>
            <div class="col-lg-12"> 
                
                <form action="{{  route('reports.surveyphotosgenerate')  }}" method="POST" class=" g-3"> 
                @csrf
                <input type="hidden" name="action" id="action" value="generate">
                <div class="row">
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
                        <div class="col-md-12 mb-3">
                         <div  >       
                        <button type="button" onclick="location.href='{{ route('reports.surveyphotos') }}'" class=" btn btn-warning">Reset</button>

                            <button type="submit" onclick="document.getElementById('action').value='generate'"   class="btn btn-primary">Search</button>
                            <button type="submit" onclick="document.getElementById('action').value='export'"    class="btn btn-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>Download Zip</button>
                      </div>   
                    </div> 
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
                                        <tr class="text-light">  
                                            <th scope="col">
                                                <label class="text-muted mb-0" >Photo1</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >Photo2</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Photo3
                                                </label>
                                            </th>  
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Photo4
                                                </label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Photo5
                                                </label>
                                            </th>  
                                        </tr>
                                    </thead>
                                        <tbody>
        @foreach ($reportData as $data)
        <tr class="white-space-no-wrap"> 
        <td>
        @if($data->photo1)
            <img src="{{ asset($data->photo1) }}" alt="Photo1" style="max-width: 150px; height: auto;">
            @else
                            -
        @endif
        </td> 
        <td>
        @if($data->photo2)
            <img src="{{ asset($data->photo2) }}" alt="Photo2" style="max-width: 150px; height: auto;">
            @else
                            -
                        @endif
                        </td> 
        <td>
        @if($data->photo3)
            <img src="{{ asset($data->photo3) }}" alt="Photo3" style="max-width: 150px; height: auto;">
            @else
                            -
                        @endif
                        </td> 
        <td>
        @if($data->photo4)
            <img src="{{ asset($data->photo4) }}" alt="Photo4" style="max-width: 150px; height: auto;">
            @else
                            -
                        @endif
                        </td> <td>
        @if($data->photo5)
            <img src="{{ asset($data->photo5) }}" alt="Photo5" style="max-width: 150px; height: auto;">
            @else
                            -
                        @endif
                        </td> 
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

