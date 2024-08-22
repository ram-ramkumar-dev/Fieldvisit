@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold">Upload Batch</h4>
                </div>  
                <div class="create-workform">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="modal-product-search d-flex">
                            <!-- <form class="mr-3 position-relative">
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" id="exampleInputText"  placeholder="Search Client">
                                    <a class="search-link" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </a>
                                </div>
                            </form> 
                            <a href="{{ route('batches.create') }}" class="btn btn-primary position-relative d-flex align-items-center justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Batch
                            </a>-->
                        </div>                            
                    </div>
                </div>                    
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block card-stretch">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <!--<h5 class="font-weight-bold">Upload Batch</h5>-->
                                <!--<button class="btn btn-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export
                                </button>-->
                            </div>
                            <div class="table-responsive">
                            <table class="table data-table mb-0">
                                    <thead class="table-color-heading">
                                        <tr class="text-light">  
                                            <th scope="col">
                                                <label class="text-muted mb-0" >Batch File</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >Batch Date</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Total Account
                                                </label>
                                            </th>  
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Total FV
                                                </label>
                                            </th>  
                                            <th scope="col"  >
                                                <span class="text-muted" >Action</span>
                                            </th> 
                                        </tr></thead>
                                        <tbody>
        @foreach ($batches as $batch)
        <tr class="white-space-no-wrap"> 
            <td>{{ $batch->batch_no }}</td>
            <td>{{ $batch->created_at->format('Y-M-d') }}</td>
            <td>{{ $batch->batch_details_count }}</td> 
            <td>{{ $batch->completed_count }}</td> 
            <td>
            <a class="" data-toggle="tooltip" data-placement="top" title="Upload" href="{{ route('batches.viewuploaded', $batch->id) }}">
            <button class="btn btn-info btn-sm mr-2"  >Upload </button>
            </a> 
            
            <form id="delete-batch-{{ $batch->id }}" class="mx-4" action="{{ route('batches.details.delete', $batch->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <a href="#" class="badge bg-danger" data-toggle="tooltip" data-placement="top" title="Delete All Customers" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete all customer from this batch?')) { document.getElementById('delete-batch-{{ $batch->id }}').submit(); }">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </a>
           </form>
            </td>
            
        </tr>
        @endforeach
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

