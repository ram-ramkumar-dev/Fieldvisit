<!-- resources/views/manage-client.blade.php -->
@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Setting</div>
            <div class="ps-3 breadcrumb-sub">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Manage Client</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <h6>Client Grouping</h6>
                        <div class="row">
                            <div class="col-6">
                                <p class="my-2">Click to view Client Group details or create a new Client Group.</p>
                                <div class="overflow-auto" style="height:200px;">
                                    <table class="table table-bordered border-1 border-dark table-hover table-responsive">
                                        <thead class="table-dark">
                                            <tr>
                                                <td>Client Group</td>
                                                <td>Description</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($clientGroups as $group)
                                            <tr class="getClientGroupDetail" data-value="{{ $group->id }}">
                                                <td>{{ $group->name }}</td>
                                                <td>{{ $group->description }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-6" style="height:200px;">
                                <form action="{{ url('setting/submitClientGroup') }}" method="post">
                                    @csrf
                                    <div class="card-body">
                                        <input type="hidden" name="newClientG" id="newClientG" value="1"/>
                                        <input type="hidden" name="clientGId" id="clientGId" value=""/>
                                        <div class="col-12">
                                            <div class="row mb-3">
                                                <label for="" class="col-sm-4 col-form-label">Group Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control form-control-sm" id="clientGName" name="clientGName" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="" class="col-sm-4 col-form-label">Description</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" id="clientGDesc" name="clientGDesc"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="d-flex align-items-center">
                                            <button type="submit" id="submitBtn" class="btn btn-sm btn-outline-success mx-2">Create</button>
                                            <span id="cancel" class="btn btn-sm btn-outline-success mx-2">Cancel</span>
                                            <span id="delete" class="btn btn-sm btn-outline-success mx-2">Delete</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr>
                        <div class="tab-content">
                            <h6>Client</h6>
                            <table class="display table table-border table-hover table-responsive table-search1">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="no-sort">Client ID</th>
                                        <th class="no-sort">Client Group</th>
                                        <th class="no-sort">Name</th>
                                        <th class="no-sort">Address</th>
                                        <th class="no-sort">State</th>
                                        <th class="no-sort">Flag Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                    <tr class="getClientDetail" data-value="{{ $client->id }}">
                                        <td>{{ $client->id }}</td>
                                        <td>{{ $client->clientGroup->name ?? '' }}</td>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->address }}</td>
                                        <td>{{ $client->state }}</td>
                                        <td>{{ $client->flag_active ? 'True' : 'False' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="search1">
                                        <th>ID</th>
                                        <th>Client Group</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>State</th>
                                        <th>Flag Active</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <hr>
                            <div class="card">
                                <form action="{{ url('setting/submitClient') }}" method="post">
                                    @csrf
                                    <div class="card-body">
                                        <input type="hidden" name="newClient" id="newClient" value="1"/>
                                        <input type="hidden" name="client_d" id="client_Id" value=""/>
                                        <h6>Client Details</h6>
                                        <div class="row">
                                            <div class="col-12 col-lg-6">
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">Client ID</label>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control form-control-sm" id="client_id" name="client_id" value="NEW" readonly>
                                                    </div>
                                                    <label for="client_status" class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-3">
                                                        <select name="client_status" id="client_status" class="form-control form-select form-select-sm">
                                                            <option value="1">ACTIVE</option>
                                                            <option value="0">INACTIVE</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">Client Name</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_name" name="client_name" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="client_clientG_ID" class="col-sm-4 col-form-label">Client Group</label>
                                                    <div class="col-sm-8">
                                                        <select name="client_clientG_ID" id="client_clientG_ID" class="form-control form-select form-select-sm">
                                                            <option value="">--Please Choose--</option>
                                                            @foreach($clientGroups as $group)
                                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">Registration No.</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_companyreg" name="client_companyreg" >
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">Phone 1</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_phone1" name="client_phone1">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">Phone 2</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_phone2" name="client_phone2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">Client Address</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_address" name="client_address">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">City</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_city" name="client_city">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">State</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_state" name="client_state">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="col-sm-4 col-form-label">Postcode</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="client_postcode" name="client_postcode">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mb-3">
                                        <div class="d-flex align-items-center">
                                            <button type="submit" id="submitBtn" class="btn btn-sm btn-outline-success mx-2">Create</button>
                                            <span id="cancel" class="btn btn-sm btn-outline-success mx-2">Cancel</span>
                                            <span id="delete" class="btn btn-sm btn-outline-success mx-2">Delete</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
