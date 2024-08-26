@extends('layouts.app')

@section('content') 
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold">Clients</h4>
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
                            </form> -->
                            <a href="{{ route('clients.create') }}" class="btn btn-primary position-relative d-flex align-items-center justify-content-between">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Client
                            </a>
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
                                <h5 class="font-weight-bold">Clients List</h5>
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
                                                <label class="text-muted mb-0" >Client ID</label>
                                            </th> 
                                            <th scope="col" >
                                                <label class="text-muted mb-0" >Client Group</label>
                                            </th>
                                            
                                            <th scope="col">
                                                <label class="text-muted mb-0" >Name</label>
                                            </th>
                                            
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Status
                                                </label>
                                            </th> 
                                            <th scope="col" >
                                                <label class="text-muted mb-0" >Registration No</label>
                                            </th>
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Phone
                                                </label>
                                            </th>  
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Address
                                                </label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                City
                                                </label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                State
                                                </label>
                                            </th> 
                                            <th scope="col">
                                                <label class="text-muted mb-0" >
                                                Postcode
                                                </label>
                                            </th> 
                                            <th scope="col"  >
                                                <span class="text-muted" >Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($clients as $client) 
                                        <tr class="white-space-no-wrap"> 
                                            <td>{{ $client->id }}</td>
                                            <td>{{ $client->clientgroup->name }}</td>
                                            <td>{{ $client->client_name }}</td>
                                            <td> 
            @php 
                if ($client->status == '1'){ @endphp
                    <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                <small><svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">                                                
                <circle cx="12" cy="12" r="8" fill="#3cb72c"></circle></svg>
                </small>Active</p>
            @php }else{ @endphp
                <p class="mb-0 text-danger font-weight-bold d-flex justify-content-start align-items-center">
                <small><svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">                                                
                <circle cx="12" cy="12" r="8" fill="#F42B3D"></circle></svg>
                </small>Inactive
            </p>
           @php } @endphp
            </td> 
                                            <td>{{ $client->registration_no }}</td>
                                            <td>{{ $client->phone1 }}</td>   
                                            <td><?php echo nl2br(htmlspecialchars($client->address)); ?></td>
                                            <td>{{ $client->city }}</td>
                                            <td>{{  $client->getStateName() ?: 'N/A'  }}</td>
                                            <td>{{ $client->postcode }}</td>                 
                                           
                                            <!--   <td>  <p class="mb-0 text-success font-weight-bold d-flex justify-content-start align-items-center">
                                                    <small>
                                                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="18" viewBox="0 0 24 24" fill="none">                                                
                                                            <circle cx="12" cy="12" r="8" fill="#3cb72c"></circle>
                                                        </svg>
                                                    </small>Active
                                                </p>
                                            </td>-->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="View" href="{{ route('clients.show', $client->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    <a class="" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('clients.edit', $client->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary mx-4" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </a>
                                                    <form id="delete-client-{{ $client->id }}" action="{{ route('clients.destroy', ['client' => $client->id]) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="badge bg-danger" data-toggle="tooltip" data-placement="top" title="Delete" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this client?')) { document.getElementById('delete-client-{{ $client->client_id }}').submit(); }">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </a>
                                                    </form>
                                                </div>
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
