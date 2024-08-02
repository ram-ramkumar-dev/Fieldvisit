@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between my-schedule mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold">Assign Account</h4>
                </div>                   
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <div class="col-lg-12"><!-- resources/views/batches/assigncase.blade.php -->
  
    <form action="{{ route('batches.assigncase', $batchId) }}" method="GET" class="row g-3">
        
        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold text-muted text-uppercase" for="fr_batch_file">Status</label>
            <select class="form-control" name="status">
                    <option value="">Please Select</option> 
                    <option value="New" {{ request('status') == "New" ? 'selected' : '' }}>New Assignement</option> 
                    <option value="Pending"  {{ request('status') == "Pending" ? 'selected' : '' }}>Reassign</option> 
                    <option value="Completed"  {{ request('status') == "Completed" ? 'selected' : '' }}>Revisit</option> 
                    <option value="Aborted"  {{ request('status') == "Aborted" ? 'selected' : '' }}>Aborted</option>  
            </select>
        </div>
        
        
        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold text-muted text-uppercase" for="fr_la">LA (District)</label>
          
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold text-muted text-uppercase" for="fr_mmid">MMID (Area)</label>
            
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold text-muted text-uppercase" for="fr_city">City:</label>
            <input type="text" class="form-control" id="fr_city" name="fr_city" value="{{ request('fr_city') }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold text-muted text-uppercase" for="fr_postcode">Postcode:</label>
            <input type="text" class="form-control" id="fr_postcode" name="fr_postcode" value="{{ request('fr_postcode') }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label font-weight-bold text-muted text-uppercase" for="fr_state">State:</label>
            <select class="multipleSelect2 form-control choicesjs" multiple="true" name="fr_state[]">
                <option value="">Select All</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ in_array($state->id, request('fr_state', [])) ? 'selected' : '' }}>
                        {{ $state->state_name }}
                    </option>
                @endforeach
            </select>
        </div> 
        <div class="col-md-4 mb-3">
        </div>
        <div class="col-md-4 mb-3">
        <button type="button" onclick="location.href='{{ route('batches.assign') }}'" class="mt-2 btn btn-warning">Reset</button>
        <button type="submit" class="mt-2 btn btn-primary">Submit</button>
        </div>  
    </form>
</div>  




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
                            <table class="table data-table mb-0" data-ordering="false">
                                    <thead class="table-color-heading">
                                        <tr class="text-light">  
                                        <th class="pr-0" style="width: 1px;">
                                        <div class="d-flex justify-content-start align-items-end mb-1 ">
                                       <div class="custom-control custom-checkbox custom-control-inline">
                                          <input type="checkbox" class="custom-control-input m-0" name="all" id="debtorCheckUncheck" value="0">
                                          <label class="custom-control-label" for="debtorCheckUncheck"></label>
                                       </div>
                                    </div> 
                                            </th>
                                            <th>
                                                <label class="text-muted mb-0" >ID</label>
                                            </th> 
                                            <th >
                                                <label class="text-muted mb-0" >Account</label>
                                            </th> 
                                            
                                            <th >
                                                <label class="text-muted mb-0" >Address</label>
                                            </th> 
                                            
                                            <th >
                                                <label class="text-muted mb-0" >District</label>
                                            </th> 
                                            
                                            <th >
                                                <label class="text-muted mb-0" >Taman</label>
                                            </th> 
                                            <th >
                                                <label class="text-muted mb-0" >
                                                Status
                                                </label>
                                            </th>  
                                            <th >
                                                <label class="text-muted mb-0" >
                                                FV Agent
                                                </label>
                                            </th>   
                                        </tr></thead>
                                        <tbody>
        
        @foreach ($batchDetails as $key=>$batch)
        <tr > 
            <td class="pr-0">
            <div class="d-flex justify-content-start align-items-end mb-1 ">
                <div class="checkboxkecik custom-control custom-checkbox custom-control-inline">
                    <input onclick="singlecheck()" type="checkbox" class="check-choose debtorCheck custom-control-input m-0" id="row_{{ $key }}" value="{{ $batch->id }}">
                    <label class="custom-control-label" for="row_{{ $key }}"></label>
                   <span style="    margin-bottom: -5px;"> {{ $loop->iteration }}</span>
                </div>
            </div>
            </td>
            <td>{{ $batch->fileid }}</td>
            <td>{{ $batch->account_no  }}-{{ $batch->name }}</td>            
            <td>{{ $batch->address }}</td>             
            <td>{{ $batch->district_la }}</td>             
            <td>{{ $batch->taman_mmid }}</td> 
            <td>{{ $batch->status }}</td> 
            <td>{{ ucfirst($batch->getDriverName()) ?: '-' }}
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
    </div><div class="bottombar ">
        <nav class="row navbar navbar-expand">
            <div class="col-sm-1 text-center">
                <input type="submit" class="btn btn-sm btn-white" id="totCheck" value="Checked: 0">
            </div>
            <div class="col-sm-1" >
            <input type="submit" class="btn btn-sm btn-danger" id="debtorUncheckAll" value="Clear All">
            </div>
            <label class="form-check-label" for="flexCheckDefault" style="margin-top:4px;">From</label>
            <div class="col-sm-1">
                <input type="number" min="1" class="form-control form-sm" id="fromCheck" value="" style="background-color:white;"> 
            </div>
            <label class="form-check-label" for="flexCheckDefault" style="margin-top:4px;">To</label>
            <div class="col-sm-1">
                <input type="number" min="1" class="form-control form-sm" id="toCheck" value="" style="background-color:white;">
            </div>
            <div class="col-sm-1" style="width: 110px;">
                <input type="submit" class="btn btn-sm btn-success" id="selectCheck" value="Select">
            </div>
            <label class="form-check-label" for="flexCheckDefault" style="margin-top:4px;">FV Agent:</label>
            <div class="col-sm-3" style="">
            <form action="{{ route('batches.assignbatchestodrivers') }}" method="post" id="assignForm">
            @csrf <!-- Add CSRF token for security -->
                <input type="hidden" name="selectedStatus" id="selectedStatus" value="1" required="">
                <input type="hidden" id="selectedId" name="selectedId" class="debtorField" required="">
                <select class="form-control form-select form-select-sm" style="background-color:white;color:black;" id="assignedto" name="assignedto" required="">
                    <option value="">Please Choose</option> 
                    @foreach ($drivers as $k => $driver) 
                    <option value="{{ $driver->id }}" >{{ ucfirst($driver->name) }}</option>
                    @endforeach
                    </select>
            </form>
            </div>
            <div class="col-sm-1" >
                <span class="btn btn-sm btn-success mx-2" id="assignBtn">ASSIGN</span>
            </div>
            
        </nav>
    </div>
</div>

<style> 
.btn-white, .form-check-label{
    color:white;
}
.bottombar {
    position: fixed;
    bottom: 0.1rem;
    left: 0px;
    right: 0;
    height: 60px;
    background: rgb(0 0 0 / 80%);
    border-bottom: 1px solid rgb(255 255 255 / 15%);
    z-index: 1;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>$(document).ready( function () {
    $('.data-table').DataTable({
        "pageLength": 50
    });
});
    function calculateChk() {
        var count = 0;
        var totChk = '';
        var checkboxes = document.getElementsByClassName('debtorCheck');
        for (var i in checkboxes) {
            if (checkboxes[i].checked)
                count++;
        }

        totChk = 'Checked: ' + count;
        $("#totCheck").val(totChk);
    }

    function singlecheck() {
        calculateChk();

        var arr = [];
        $('.debtorCheck:checked').each(function () {
            arr.push($(this).val());
        });

        $(".debtorField").val(arr);
    }

    $("#debtorUncheckAll").click(function () {
        $('.debtorCheck').prop('checked', false);
        $("#fromCheck").val('');
        $("#toCheck").val('');
        $("#selectedId").val('');
        document.getElementById("debtorCheckUncheck").checked = false;

        calculateChk();
    });

    $("#debtorCheckUncheck").change(function () {
        if ($(this).prop('checked')) {
            $('.debtorCheck').prop('checked', true)
        } else {
            $('.debtorCheck').prop('checked', false);
        }
        calculateChk();

        var arr = [];
        $('.debtorCheck:checked').each(function () {
            arr.push($(this).val());
        });

        $(".debtorField").val(arr);
    });
    $("#selectCheck").click(function () {
        $('.debtorCheck').prop('checked', false);
        var countD = $("#countDebt").val();
        var fromC = $("#fromCheck").val();
        var toC = $("#toCheck").val();

        var countDebt = parseInt(countD);
        var fromCheck = parseInt(fromC);
        var toCheck = parseInt(toC);

        var c = 'countDebt : ' + countDebt + ' fromCheck : ' + fromCheck + ' toCheck : ' + toCheck;

        if (toCheck > countDebt) {
            alert("Invalid Select Range : To Value Must Be Less Than number of records");
        } else if (fromCheck > toCheck) {
            alert("Invalid Select Range : From Value Must Be Less Than To Value");
        } else {
            for (var i = fromCheck - 1; i < toCheck; i++) {
                $('.debtorCheck')[i].checked = true;
            }
        }
        calculateChk();
        var arr = [];
        $('.debtorCheck:checked').each(function () {
            arr.push($(this).val());
        });

        $(".debtorField").val(arr);
    });
    
    $('#assignBtn').click(function(){
        var selectedStatus = $("#selectedStatus").val();        
        var selectedId = $("#selectedId").val();  alert(selectedId);
        var assignedto = $("#assignedto").val();  
        if  (selectedStatus == '' || selectedId == '' || assignedto == ''){
            alert ("Please choose at least one account and agent to assign");
        }else{
            $("#assignForm").submit();
        }
    });
    function clearDropdownSelection(dropdownId) {
        var dropdown = document.getElementById(dropdownId);

        for (var i = 0; i < dropdown.options.length; i++) {
            dropdown.options[i].selected = false;
        }
    }

    function clearInputField(inputId) {
        document.getElementById(inputId).value = '';
    }

    function refreshSelectPicker(selectId) {
        $('#' + selectId).selectpicker('refresh');
    }

    function clearAllSelections() {
       // clearDropdownSelection('batchList');
        //clearDropdownSelection('districtList');
       // clearDropdownSelection('areaList');
       // clearDropdownSelection('agentId');
      //  clearDropdownSelection('state');

        clearInputField('fr_city');
        clearInputField('fr_postcode');

      //  refreshSelectPicker('batchList');
      //  refreshSelectPicker('districtList');
      //  refreshSelectPicker('areaList');
     //   refreshSelectPicker('agentId');
    //    refreshSelectPicker('state');
    }

    document.getElementById('debtorUncheckAll').addEventListener('click', function () {
        clearAllSelections();
    });
        </script>
@endsection 

