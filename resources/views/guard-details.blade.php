@extends('layouts.main-layout', ['page_title' => ucwords($guard->firstname.' '.$guard->lastname)])
@section('styles')

<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet"/>
<style>
    .abs{
        display:absolute;
        position: right;
        float: right;
    }

    .thumb-lg{
        object-fit:cover;
        cursor: pointer;
        transition:0.3s;
    }

    .thumb-lg:hover{
        opacity: 0.8;
    }

    .imgModal{
        background-color: black;
    }
</style>
@endsection
@section('content')
                <div class="row">
                    <div class="col-sm-12">
                        <!-- meta -->
                        <div class="profile-user-box card-box bg-custom">
                            <div class="row">
                                <div class="col-sm-6">
                                    <span class="float-left mr-3"><img src="{{asset('assets/images/guards/'.$guard->photo)}}"  onerror="this.src='{{asset('assets/images/avatar.jpg')}}'" alt="" class="thumb-lg rounded-circle" onclick="enlarge(this)"></span>
                                    <div class="media-body text-white">
                                        <h4 class="mt-1 mb-1 font-18">{{$guard->firstname.' '.$guard->lastname}}</h4>
                                        <p class="font-13 text-light"> {{$guard->phone_number}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-right">
                                        @if($guard->deleted_at == null)
                                        <button type="button" class="btn btn-dark waves-effect" onclick="deleteGuard('{{$guard->id}}')">
                                            Move To Archive
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-warning waves-effect" onclick="undeleteGuard('{{$guard->id}}')">
                                            Remove From Archive
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ meta -->
                        
                        <div class="row text-right">
                            <div class="col-sm-12">
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#delete-fingerprint">
                                    <p class="badge badge-pill">
                                        Delete Fingerprint
                                    </p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-xl-4">
                    <!-- Personal-Information -->
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-20">Guard Details</h4>
                        <div class="panel-body">
                            <hr />

                            <div class="text-left">
                                <p class="text-muted font-13"><strong>Guard Name :</strong> <span class="m-l-15">{{$guard->firstname.' '.$guard->lastname}}</span></p>

                                <p class="text-muted font-13"><strong>Mobile Number:</strong><span class="m-l-15">{{$guard->phone_number}}</span></p>

                                <p class="text-muted font-13"><strong>Emergency :</strong> <span class="m-l-15">{{$guard->emergency_contact}}</span></p>

                                <p class="text-muted font-13"><strong>Gender :</strong> <span class="m-l-15">{{$guard->gender}}</span></p>

                                <p class="text-muted font-13"><strong>Date of birth :</strong> <span class="m-l-15">{{Carbon\Carbon::parse($guard->dob)->format('jS F Y')}}</span></p>

                                <p class="text-muted font-13"><strong>Residential Address :</strong> <span class="m-l-15">{{$guard->address}}</span></p>

                                <p class="text-muted font-13"><strong>ID Number: {{$guard->national_id}}</strong></p>

                                <p class="text-muted font-13"><strong>Current Site :</strong> <span class="m-l-15">
                                @php 
                                    if($guard->duty_rosters->count() > 0){
                                        $is_guarding = false;

                                        foreach($guard->duty_rosters as $duty_roster){
                                            if(!Carbon\Carbon::parse($duty_roster->site->client->end_date)->isPast()){
                                                $is_guarding = true;
                                                echo $duty_roster->site->name;
                                                break;
                                            }
                                        }

                                        if(!$is_guarding){
                                            echo 'Currenttly not guarding any site';
                                        }
                                    }else{
                                        echo 'Currently not guarding any site';
                                    }
                                @endphp    
                                    </span>
                                </p>

                            </div>
                        </div>
                    </div>
                    <!-- Guarantor-Information -->
                    <div class="card-box mt-4">
                        <h4 class="header-title mt-0 m-b-20">Guarantors</h4>
                        <div class="panel-body">
                            <hr />
                            
                            @foreach($guard->guarantors as $guarantor)
                            <div class="text-left mb-3">
                                <h6>{{ucwords($guarantor->firstname.' '.$guarantor->lastname)}} <span class="abs"><a href="javascript:void(0)" onclick="editGuarantor({{$guarantor}})">Edit</a></span></h6>
                                <p class="text-muted font-13"><strong>Mobile Number:</strong><span class="m-l-15">{{$guarantor->phone_number}}</span></p>

                                <p class="text-muted font-13"><strong>Gender :</strong> <span class="m-l-15">{{$guarantor->gender}}</span></p>

                                <p class="text-muted font-13"><strong>Date of birth :</strong> <span class="m-l-15">{{Carbon\Carbon::parse($guarantor->dob)->format('jS F Y')}}</span></p>

                                <p class="text-muted font-13"><strong>Residential Address :</strong> <span class="m-l-15">{{$guarantor->address}}</span></p>

                                <p class="text-muted font-13"><strong>Occupation :</strong> <span class="m-l-15">{{$guarantor->occupation}}</span></p>

                                <p class="text-muted font-13"><strong>ID Number :</strong> <span class="m-l-15">{{$guarantor->national_id}}</span></p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="col-xl-8">

            <div class="card-box">
                <h4 class="header-title mt-0 mb-3">Edit Guard</h4>
                <hr/>
                <form method="post" action = "#" id="edit_guard_form">
                @csrf
                    <div id="personal_information">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstname" class="col-form-label"><b>First</b> Name</label>
                                <input type="text" class="form-control required" id="firstname" name="firstname" value="{{$guard->firstname}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastname" class="col-form-label"><b>Last</b> Name</label>
                                <input type="text" class="form-control required" id="lastname" name="lastname" value="{{$guard->lastname}}">
                            </div>
                            <input type="hidden" name="id" value="{{$guard->id}}"/>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="dob" class="col-form-label"><b>Date of</b> Birth</label>
                                <div class="input-group">
                                    <input type="text" class="form-control required" id="dob" autocomplete="false" name="dob"  value="{{$guard->dob}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="phone_number" class="col-form-label"><b>Phone</b></label>
                                <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control required" name="phone_number" value="{{$guard->phone_number}}">
                                <span class="font-10 text-muted">(233) 244-500500</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="gender" class="col-form-label"><b>&nbsp;</b></label>
                                <select class="selectpicker show-tick required" data-style="btn-custom" title="Gender"
                                    id="gender" name="gender">
                                    <option <?php if($guard->gender == 'Male'){echo 'selected';} ?>>Male</option>
                                    <option <?php if($guard->gender == 'Female'){echo 'selected';} ?>>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-4">
                                <label for="phone_number" class="col-form-label"><b>Occupation</b></label>
                                <!--input type="text" class="form-control required" id="occupation" name="occupation" value="{{$guard->occupation}}"-->
                                <select class="selectpicker show-tick required" data-style="btn-custom" title="Occupation"
                                    id="occupation" name="occupation">
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}" <?php if($guard->occupation == $role->id){echo 'selected';} ?>>{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="marital" class="col-form-label"><b>&nbsp;</b></label>
                                <select class="selectpicker show-tick required" data-style="btn-custom" title="Marital Status"
                                    id="marital" name="marital_status">
                                    <option <?php if($guard->marital_status == 'Single'){echo 'selected';} ?>>Single</option>
                                    <option <?php if($guard->marital_status == 'Married'){echo 'selected';} ?>>Married</option>
                                    <option <?php if($guard->marital_status == 'Divorced'){echo 'selected';} ?>>Divorced</option>
                                    <option <?php if($guard->marital_status == 'Widowed'){echo 'selected';} ?>>Widowed</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="residential" class="col-form-label"><b>Residential</b>
                                    Address</label>
                                <input type="text" class="form-control required" id="residential" name="address" value="{{$guard->address}}">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="bank_name" class="col-form-label"><b>Bank</b> Name</label>
                                <input type="text" class="form-control required" id="bank_name" name="bank_name" value="{{$guard->bank_name}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="account_number" class="col-form-label"><b>Branch</b> Name</label>
                                <input type="text" class="form-control required" id="bank_branch" name="bank_branch" value="{{$guard->bank_branch}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="account_number" class="col-form-label"><b>Account</b> Number</label>
                                <input type="text" class="form-control required" id="account_number" name="account_number" value="{{$guard->account_number}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="bank_name" class="col-form-label"><b>ID</b> Number</label>
                                <input type="text" class="form-control required" id="national_id" name="national_id" value="{{$guard->national_id}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="bank_name" class="col-form-label"><b>SSNIT</b> Number</label>
                                <input type="text" class="form-control required" id="SSNIT" name="SSNIT" value="{{$guard->SSNIT}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Emergency Contact</label>
                                <input type="tel" name="emergency_contact"  placeholder="" data-mask="(999) 999-999999" class="form-control" value="{{$guard->emergency_contact}}"/>
                                <span class="font-10 text-muted">(233) 244-500500</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-custom ml-1 waves-effect waves-light edit-guard" disabled="true">Save</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
@section('modals')
    <div id="deleteGuardModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Are you sure?</h4>	
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to add this guard to the archive? This guard will not be able to take attendance or be added to duty a roster.</p>
                    <input type="hidden" id="delete-guard-id"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="btn-delete-guard">Archive</button>
                </div>
            </div>
        </div>
    </div>
    <div id="undeleteGuardModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Are you sure?</h4>	
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this guard from the archive?.</p>
                    <input type="hidden" id="undelete-guard-id"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="btn-undelete-guard">Remove From Archive</button>
                </div>
            </div>
        </div>
    </div>
    <div id="enlargeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content modal-md" style="background-color:transparent;">
                <div class="modal-body">
                    <img id="enlargedImage" style="min-width: 450px;"/>
                </div>
            </div>
        </div>
    </div>
    <div id="editGuarantorModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Guarantor</h4>	
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form id="edit_guarantor_form">
                    <div class="modal-body">
                        <div class="form-row m-b-25">
                            <div class="col-md-6 col-sm-12">
                                <label for="guarantor_firstname">First Name</label>
                                <input class="form-control required" type="text" id="guarantor_firstname" placeholder="Kwame" name="firstname"  required/>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="guarantor_lastname">Last Name</label>
                                <input class="form-control required" type="text" id="guarantor_lastname" placeholder="Attah" name="lastname" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="guarantor_dob" class="col-form-label"><b>Date of</b> Birth</label>
                                <div class="input-group">
                                    <input type="text" class="form-control required" id="guarantor_dob" name="dob" autocomplete="false" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                                <input type="hidden" name="id" id="guarantor_id"/>
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="guarantor_phone_number" class="col-form-label"><b>Phone</b></label>
                                <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control required" id="guarantor_phone" name="phone_number" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="guarantor_gender" class="col-form-label"><b>&nbsp;</b></label>
                                <select class="selectpicker show-tick" data-style="btn-custom" title="Gender" id="guarantor_gender" name="gender" required>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Transgender</option>
                                    <option>Rather not say</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="col-md-4 col-sm-12">
                                <label for="guarantor_occupation">Occupation</label>
                                <input class="form-control required" type="text" id="guarantor_occupation" placeholder="Seamstress" name="occupation" required>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="guarantor_residential">Residential address</label>
                                <input class="form-control required" type="text" id="guarantor_residential" placeholder="21 Ledzekuku St."
                                    name="address" required>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="guarantor_residential">National ID</label>
                                <input class="form-control required" type="text" id="guarantor_national" placeholder="C019382190931"
                                    name="national_id" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-custom">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="delete-fingerprint" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Fingerprint</h4>	
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form method="post" action="/fingerprint/delete/{{$guard->id}}">
                    @csrf
                    <input type="hidden" name="_method" value="delete"/>
                    <div class="modal-body">
                        <p>Are you sure you want to remove this guard's fingerpirnt. Remember that this process cannot be reversed</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Remove Fingerprint</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
<script>
    $('#edit_guard_form').find('input').on('keyup', function(){
        $('#edit_guard_form').find('[type="submit"]').prop('disabled', false);
    });

    $('#edit_guard_form').find('select').on('change', function(e){
        $('#edit_guard_form').find('[type="submit"]').prop('disabled', false);
    });

    @if(session('success') != null)
    $.toast({
                text : "{{session('success')}}",
                heading : 'Done',
                position: 'top-right',
                bgColor : '#5cb85c',
                showHideTransition : 'slide'
            });
    @endif

    @if($guard->deleted_at == null)
    function deleteGuard(guard)
    {
        $('#delete-guard-id').val(guard);

        $('#deleteGuardModal').modal('show');
    }

    
    $('#btn-delete-guard').on('click', function(e){
        e.preventDefault();
        var btn = $(this);

        $.ajax({
            url: '/api/guard/delete/'+ $("#delete-guard-id").val(),
            method: 'DELETE',
            success: function(data){
                removeLoading(btn, 'Archive');
                    if(data.error){
                        $.toast({
                            text : data.message,
                            heading : 'Error',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#d9534f'
                        });
                    }else{
                        $.toast({
                            text : data.message,
                            heading : 'Done',
                            position: 'top-right',
                            bgColor : '#5cb85c',
                            showHideTransition : 'slide'
                        });

                        setTimeout(function(){
                            location.replace('/guards');
                        }, 500);
                    }
            },
            error: function(err){
                removeLoading(btn, 'Archive');
                $.toast({
                    text : 'Network error',
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }
        });
    });
    @else
    function undeleteGuard(guard){
        $('#undelete-guard-id').val(guard);

        $('#undeleteGuardModal').modal('show');
    }
    
    $('#btn-undelete-guard').on('click', function(e){
        e.preventDefault();
        var btn = $(this);
        
        $.ajax({
            url: '/api/guard/undelete/'+ $("#undelete-guard-id").val(),
            method: 'POST',
            success: function(data){
                removeLoading(btn, 'Remove From Archive');
                    if(data.error){
                        $.toast({
                            text : data.message,
                            heading : 'Error',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#d9534f'
                        });
                    }else{
                        $.toast({
                            text : data.message,
                            heading : 'Done',
                            position: 'top-right',
                            bgColor : '#5cb85c',
                            showHideTransition : 'slide'
                        });

                        setTimeout(function(){
                            location.reload();
                        }, 500);
                    }
            },
            error: function(err){
                removeLoading(btn, 'Remove From Archive');
                $.toast({
                    text : 'Network error',
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }
        });
    });
    @endif



    $('#edit_guard_form').on('submit', function(e){
        e.preventDefault();
        var btn = $(this).find('[type="submit"]');

        data = $(this).serialize();

        var error = false;

        applyLoading(btn);

        $.ajax({
            url: '/api/guard/update',
            method: 'PUT',
            data: data,
            success: function(data){
                removeLoading(btn, 'Save');
                    if(data.error){
                        $.toast({
                            text : data.message,
                            heading : 'Error',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#d9534f'
                        });
                    }else{
                        $.toast({
                            text : data.message,
                            heading : 'Done',
                            position: 'top-right',
                            bgColor : '#5cb85c',
                            showHideTransition : 'slide'
                        });

                        setTimeout(function(){
                            location.replace('/guard/'+data.data.id);
                        }, 500);
                    }
            },
            error: function(err){
                removeLoading(btn, 'Save');
                $.toast({
                    text : 'Network error',
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }
        });
    });

    function editGuarantor(g){
        $('#guarantor_firstname').val(g.firstname);
        $('#guarantor_lastname').val(g.lastname);
        $('#guarantor_occupation').val(g.occupation);
        $('#guarantor_residential').val(g.address);
        $('#guarantor_national').val(g.national_id);
        $('#guarantor_dob').val(g.dob);
        $('#guarantor_phone').val(g.phone_number);
        $('#guarantor_id').val(g.id);
        $('#guarantor_gender').find('option').each(function(){
            if($(this).val() == g.gender){
                $(this).prop('selected', true);
                $('#guarantor_gender').val(g.gender);
                $('.selectpicker').selectpicker('refresh');
            }
        });

        $('#editGuarantorModal').modal('show');
    }

    $('#edit_guarantor_form').on('submit', function(e){
        e.preventDefault();

        var btn = $(this).find('[type="submit"]');
        var initial = btn.html();
        var data = $(this).serialize();
        console.log(data);
        applyLoading(btn);
        
        $.ajax({
            url : '/api/guarantor/update/'+$('#guarantor_id').val(),
            method : 'PUT',
            data : data, 
            success : function(data){
                    removeLoading(btn, initial);
                    if(data.error){
                        $.toast({
                            text : data.message,
                            heading : 'Error',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#d9534f'
                        });
                    }else{
                        $.toast({
                            text : data.message,
                            heading : 'Done',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#5cb85c'
                        });
                        
                        setTimeout(location.reload(), 500);
                    }
                },
                error : function(err){
                    removeLoading(btn, 'Save');
                    $.toast({
                        text : 'Server error',
                        heading : 'Error',
                        position: 'top-right',
                        showHideTransition : 'slide', 
                        bgColor: '#d9534f'
                    });
                }
        })
    });

    function enlarge(element){
        $('#enlargeModal').find('#enlargedImage').attr('src', $(element).attr('src'));
        $('#enlargeModal').modal('show');
    }
</script>
@endsection