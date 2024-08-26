@extends('layouts.app')

@section('content')
<style>
#fullScreenLoader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent black background */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999; /* Ensure it appears above all other content */
    flex-direction: column;
}
</style>
<!-- Full-Screen Loader (hidden by default) -->
<div id="fullScreenLoader" style="display: none;">
    <div class="spinner-border text-light" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    <p class="text-light mt-2">Uploading...</p>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold">Upload Batch</h4>
                </div>  
                <div class="create-workform">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="modal-product-search d-flex">   <a href="{{ route('batches.import') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-between ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">Back</span>
                    </a>
                            <!-- <form class="mr-3 position-relative">
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control" id="exampleInputText"  placeholder="Search Client">
                                    <a class="search-link" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </a>
                                </div>
                            </form> -->
                            
                           
                        </div>                            
                    </div>
                </div>                    
            </div> 
            <!-- Display any success or error messages -->
            
            @if (session('success'))
            <div class="alert alert-success">
                <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-block card-stretch">
                        <div class="card-body p-0">
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <h5 class="font-weight-bold">Batch {{ $batch->batch_no }} </h5>
                                <!--<button class="btn btn-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-1" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export
                                </button>--> 
                                <a href="{{ asset('assets/template/TemplateFV.xlsx') }}" download>Download Template</a>
                                <form id="uploadForm" action="{{ route('batches.upload.store', $batch->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" id="fileInput" name="file" accept=".xlsx,.xls" style="display: none;" required>
                                <button type="button" class="btn btn-primary position-relative d-flex align-items-center justify-content-between" id="uploadButton">Upload Account</button>
                            </form>
                            </div>
                            <div class="table-responsive">
                            <table class="table data-table mb-0">
                                    <thead class="table-color-heading">
                                        <tr class="text-light">  
                                            
                                        <th scope="col">
                                                <label class="text-muted mb-0" >ID</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >Address</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >LA(District)</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >MMID(Area)</label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Total Account
                                                </label>
                                            </th>  
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Complete Account
                                                </label>
                                            </th>   
                                        </tr></thead>
                                        <tbody>
        @foreach ($batchedetail as $batch)
        <tr class="white-space-no-wrap"> 
            <td>{{ $batch->fileid }}</td>
            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
    <?php echo htmlspecialchars($batch->address); ?>
</td>
            <td>{{ $batch->district_la }}</td>
            <td>{{ $batch->taman_mmid }}</td>
            <td>1</td>
            <td>{{ $batch->status == 'Completed' ? '1' : '0' }}</td>   
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
<script>
        document.getElementById('uploadButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('fullScreenLoader').style.display = 'flex';
                document.getElementById('uploadForm').submit();
            }
        });
    </script>

@endsection 
