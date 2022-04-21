@extends('layouts.main-layout', ['page_title'=>'Zones'])
@section('content')
    <div class="row">
        <div class="col-xl-4">
            <div class="card-box">
                <h4 class="header-title mt-0 m-b-20">New Zone</h4>
                <div class="panel-body">
                    <form id="zone_add">
                        <div class="form-group">
                            <label for="guard" class="col-form-label"><b>Zone Name</b></label>
                            <input type="text" name="name" class="form-control"/>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-12">
                                <label for="role" class="col-form-label"><b>Zone Supervisor</b></label>
                                <select class="selectpicker show-tick form-control" data-style="btn-primary" title="Zone Supervisor" id="user_id"
                                    name="user_id">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->firstname . ' ' . $user->lastname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-8">

            <div class="row">

                <div class="card-box col-sm-12">

                    <div class="col-12">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Supervisor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($zones as $zone)
                                <tr>
                                    <td>{{$zone->name}}</td>
                                    <td>{{$zone->user ? $zone->user->first_name . ' ' . $zone->user->lastname : 'N/A'}}</td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                        <a href="javascript:void(0)" class="text-info" onclick="editZone('{{$zone->id}}', '{{$zone->name}}', '{{$zone->user_id}}')">Edit</a>
                                        &nbsp;
                                        <a href="javascript:void(0)" class="text-danger" onclick="deleteZone('{{$zone->id}}')">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end col -->
    </div>
    <div id="delete_zone" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <form id="delete_zone_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Are you sure?</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Do you really want to delete this shift? This process cannot be undone</p>
                        <input type="hidden" id="delete-shift-id"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="btn-delete-shift">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="edit_zone" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <form id="edit_zone_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Zone</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="guard" class="col-form-label"><b>Zone Name</b></label>
                            <input type="text" name="name" id="edit_name" class="form-control"/>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-12">
                                <label for="role" class="col-form-label"><b>Zone Supervisor</b></label>
                                <select class="selectpicker show-tick form-control" data-style="btn-primary" title="Zone Supervisor" id="edit_user_id"
                                    name="user_id">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}">{{$user->firstname . ' ' . $user->lastname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="edit_id"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-edit-shift">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-timepicker/bootstrap-timepicker.js')}}"></script>
<script>
    var table = $('#datatable').DataTable();

    $("#zone_add").on("submit", function(e){
        e.preventDefault();
        let data = $(this).serialize();

        const btn = $(this).find("[type=submit]");
        const initial_text = btn.html();

        applyLoading(btn);

        $.ajax({
           url : "/api/zones",
           method : "POST",
           data : data,
           success: function(data){
                removeLoading(btn, initial_text);
                if(data.error){

                    $.toast({
                        text : data.message,
                        heading : 'Error',
                        position: 'top-right',
                        showHideTransition : 'slide', 
                        bgColor: '#d9534f'
                    });
                }else{
                    $('#zone_add').trigger('reset');
                    $.toast({
                        text : data.message,
                        heading : 'Done',
                        position: 'top-right',
                        bgColor : '#5cb85c',
                        showHideTransition : 'slide'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            },
            error: function(err){
                removeLoading(btn, initial_text);

                $.toast({
                    text : 'Network error',
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }
        })
    });

    const deleteZone = (id) => {
        $("#delete-shift-id").val(id);
        $("#delete_zone").modal("show");
    }

    $("#delete_zone_form").on("submit", function(e){
        e.preventDefault();
        const btn = $(this).find("[type=submit]");
        const initial_text = btn.html();

        applyLoading(btn);
        
        $.ajax({
            url : `/api/shift-type/delete/${$("#delete-shift-id").val()}`,
            method : "DELETE",
            success: function(data){
                removeLoading(btn, initial_text);
                if(data.error){

                    $.toast({
                        text : data.message,
                        heading : 'Error',
                        position: 'top-right',
                        showHideTransition : 'slide', 
                        bgColor: '#d9534f'
                    });
                }else{
                    $('#delete_shift_form').trigger('reset');
                    $.toast({
                        text : data.message,
                        heading : 'Done',
                        position: 'top-right',
                        bgColor : '#5cb85c',
                        showHideTransition : 'slide'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            },
            error: function(err){
                removeLoading(btn, initial_text);

                $.toast({
                    text : 'Network error',
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }
        })
    });

    const editZone = (id, name, user) => {
        $("#edit_id").val(id);
        $("#edit_name").val(name);

        $("#edit_zone").modal("show");
    }

    $("#edit_zone_form").on("submit", function(e){
        e.preventDefault();
        const btn = $(this).find("[type=submit]");

        let data = $(this).serialize();

        const initial_text = btn.html();

        applyLoading(btn);

        $.ajax({
        url : "/api/zones/"+$("#edit_id").val(),
        method : "PUT",
        data : data,
        success: function(data){
                removeLoading(btn, initial_text);
                if(data.error){

                    $.toast({
                        text : data.message,
                        heading : 'Error',
                        position: 'top-right',
                        showHideTransition : 'slide', 
                        bgColor: '#d9534f'
                    });
                }else{
                    $('#shift_type_add').trigger('reset');
                    $.toast({
                        text : data.message,
                        heading : 'Done',
                        position: 'top-right',
                        bgColor : '#5cb85c',
                        showHideTransition : 'slide'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
            },
            error: function(err){
                removeLoading(btn, initial_text);

                $.toast({
                    text : 'Network error',
                    heading : 'Error',
                    position: 'top-right',
                    showHideTransition : 'slide', 
                    bgColor: '#d9534f'
                });
            }
        })
    })
</script>
@endsection

