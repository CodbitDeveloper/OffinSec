@extends('layouts.main-layout', ['page_title' => ucwords($site->client->name.' - '.$site->name)])
@section('styles')

<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    .abs{
        display:absolute;
        position: right;
        float: right;
    }
    p.badge{
        font-size: 12px;
        padding: 12px;
    }

    .text-tiny{
        font-size: 12px;
    }

    .text-black{
        color: black;
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
                                    <div class="media-body text-white">
                                        <h4 class="mt-1 mb-1 font-18">{{$site->name}}</h4>
                                        <p class="font-13 text-light text-uppercase">{{$site->access_code}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="text-right">
                                        <button type="button" class="btn btn-light waves-effect" onclick="siteEdit({{$site}})">
                                            Edit Site
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ meta -->
                           <!--/ meta -->
                        <div class="row text-right">
                            <div class="col-sm-12">
                                <a href="javascript:void(0)" data-toggle="modal" data-target="#add-contact">
                                    <p class="badge badge-pill">
                                        Add Emergency Contact
                                    </p>
                                </a>
                                <a href="{{route('site.manage-patrol', $site)}}">
                                    <p class="badge badge-pill">
                                        Manage Patrols
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
                        <h4 class="header-title mt-0 m-b-20">Site Details</h4>
                        <div class="panel-body">
                            <hr />

                            <div class="text-left">
                                <p class="text-muted font-13"><strong>Site Name :</strong> <span class="m-l-15">{{$site->name}}</span></p>

                                <p class="text-muted font-13"><strong>Phone Number:</strong><span class="m-l-15">{{$site->phone_number}}</span></p>

                                <p class="text-muted font-13"><strong>Location :</strong> <span class="m-l-15">{{$site->location}}</span></p>

                                <p class="text-muted font-13"><strong>Supervisor :</strong> <span class="m-l-15">{{$site->supervisor != null ? $site->supervisor->firstname.' '.$site->supervisor->lastname : 'N/A'}}</span></p>
                            </div>
                        </div>
                    </div>
                    
                </div>


                <div class="col-xl-8">

            <div class="card-box">
                <ul class="nav nav-tabs tabs-bordered">
                    <li class="nav-item">
                        <a href="#guards" data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="fi-head mr-2"></i> Guards
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#contacts" data-toggle="tab" aria-expanded="true" class="nav-link">
                            <i class="mdi mdi-phone mr-2"></i> Emergency Contacts
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="guards">
                        <div class="col-sm-12">
                            <div class="col-12">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Name of Guard</th>
                                            <th>Contact Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($site->duty_roster != null)
                                        @foreach($site->duty_roster->guards as $guard)
                                        <tr>
                                            <td>{{$guard->firstname.' '.$guard->lastname}}</td>
                                            <td>{{$guard->phone_number}}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="contacts">
                        <div class="col-sm-12">
                            <div class="col-12">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($site->contacts as $contact)
                                        <tr>
                                            <td>{{$contact->contact_name}}</td>
                                            <td>{{$contact->contact_number}}</td>
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
@endsection
@section('modals')
    <div class="modal fade" id="edit-site" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title mt-2">Edit Site</h4>
                </div>
                <div class="modal-body p-4">
                    <form role="form" id="edit_site_form">   
                    @csrf
                    <div class="form-row mb-2">
                        <div class="col-md-6 col-sm-12">
                            <label for="name">Site name</label>
                            <input class="form-control resetable" type="text" placeholder="Codbit ADC" name="name">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="name">Site Location</label>
                            <input class="form-control resetable" type="text" placeholder="Ring Road Central" name="location">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 col-sm-12">
                            <label for="email">Contact Number</label>
                            <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control resetable" name="phone_number">
                            <input type="hidden" name="id"/>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="supervisor" class="col-form-label">Supervisor</label>
                            <select class="selectpicker show-tick form-control" data-style="btn-primary" 
                            title="Supervisor" id="edit-supervisor" name="guard_id" data-live-search="true">
                                @foreach($guards as $guard)
                                <option value="{{$guard->id}}" data-subtext="{{$guard->phone_number}}">{{$guard->firstname.' '.$guard->lastname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-custom ml-1 waves-effect waves-light save-category">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-contact" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title mt-2">Add Emergency Contact</h4>
                </div>
                <div class="modal-body p-4">
                    <form role="form" id="frm_add">
                    @csrf
                        <div class="form-row mb-2">
                            <div class="col-md-6 col-sm-12">
                                <label for="contact_number" class="col-form-label"><b>Contact Name</b></label>
                                <input class="form-control resetable" type="text" id="contact_name" placeholder="Abraham Attah" name="contact_name" required>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="contact_number" class="col-form-label"><b>Emergency Number</b></label>
                                <input class="form-control resetable" type="tel" id="contact_number" placeholder="" name="contact_number" data-mask="(999) 999-999999" required>
                                <span class="font-10 text-muted">(233) 244-500500</span>
                            </div>
                        </div>
                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-icon ml-1 waves-effect waves-light btn-success">Add</button>
                            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js')}}" type="text/javascript"></script>
<script>
    $('.table').DataTable();

    jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();
    function siteEdit(site)
        {
            $('#edit-site').find('[name="name"]').val(site.name);
            $('#edit-site').find('[name="location"]').val(site.location);
            $('#edit-site').find('[name="phone_number"]').val(site.phone_number);
            $('#edit-site').find('[name="id"]').val(site.id);

            $('#edit-supervisor').find('option').each(function(){
                if($(this).val() == site.guard_id){
                    $(this).prop('selected', true);
                    $('#edit-supervisor').val(site.guard_id);
                    $('.selectpicker').selectpicker('refresh');
                }
            });

            $('#edit-site').modal('show');
        }

    $('#edit_site_form').on('submit', function(e){
        e.preventDefault();
        var btn = $(this).find('[type="submit"]');
        
        data = $(this).serialize();

        var error = false;

        $(this).find('.resetable').each(function(){
            if($(this).val() == '' || $(this).val() == null){
                error = true;
                $(this).closest('div').append('<span class="text-danger text-small">This field is required</span>')
            }
        })

        if(!error){
            $(this).find('.text-danger').css('display', 'none');

            applyLoading(btn);

            $.ajax({
                url: '/api/site/update',
                method: 'PUT',
                data: data+'&client_id={{$site->client->id}}',
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
                                location.reload();
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
        }            
    });

    $('#frm_add').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serialize();
        data+="&site_id={{$site->id}}";

        var btn = $(this).find('[type="submit"]');
        var initial = btn.html();

        applyLoading(btn);

        $.ajax({
            url: '/api/contact/add',
            method: 'POST',
            data: data,
            success: function(data){
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
                        $('#add-contact').modal('hide');
                        $('#frm_add').trigger('reset');
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
                    removeLoading(btn, initial);
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
</script>
@endsection