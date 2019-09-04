@extends('layouts.main-layout', ['page_title'=>'Shift Types'])
@section('styles')
<link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4">
            <!-- Personal-Information -->
            <div class="card-box">
                <h4 class="header-title mt-0 m-b-20">New Shift Type</h4>
                <div class="panel-body">

                    <form id="shift_type_add">
                        <div class="form-group">
                            <label for="guard" class="col-form-label"><b>Shift Name</b></label>
                            <input type="text" name="name" class="form-control"/>
                        </div>
                        <div class="form-group mb-4">
                            <label for="comment"><b>Start Time</b></label>
                            <input type="text" id="start_time" class="form-control datetimepicker" placeholder="Start time" required/>
                        </div>
                        <div class="form-group mb-4">
                            <label for="comment"><b>End Time</b></label>
                            <input type="text" id="end_time" class="form-control datetimepicker" placeholder="End time" required/>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>

                </div>
            </div>
            <!-- Personal-Information -->
            <!-- offences forms -->



        </div>


        <div class="col-xl-8">

            <div class="row">

                <div class="card-box col-sm-12">

                    <div class="col-12">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($shift_types as $shift)
                                <tr>
                                    <td>{{$shift->name}}</td>
                                    <td>{{date("H:i:s", strtotime($shift->start_time))}}</td>
                                    <td>{{date("H:i:s", strtotime($shift->end_time))}}</td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                        <a href="javascript:void(0)" class="text-info" onclick="editShift('{{$shift->id}}', '{{$shift->name}}', '{{date('H:i:s', strtotime($shift->start_time))}}', '{{date('H:i:s', strtotime($shift->end_time))}}')">Edit</a>
                                        &nbsp;
                                        <a href="javascript:void(0)" class="text-danger" onclick="deleteShift('{{$shift->id}}')">Delete</a>
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
    <div id="delete_shift" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <form id="delete_shift_form">
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
    <div id="edit_shift" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <form id="edit_shift_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Shift</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-sm-12">
                                <label>Shift Name</label>
                                <input class="form-control" name="name" id="edit_name"/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-12 col-md-6">
                                <label>Start Time</label>
                                <input class="form-control datetimepicker" id="edit_start_time"/>
                            </div>
                            <div class="form-group col-sm-12 col-md-6">
                                <label>End Time</label>
                                <input class="form-control datetimepicker" id="edit_end_time"/>
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
    $('.datetimepicker').timepicker({
        defaultTIme: false,
        icons: {
            up: 'mdi mdi-chevron-up',
            down: 'mdi mdi-chevron-down'
        },
        minuteStep: 1,
    });

    $("#shift_type_add").on("submit", function(e){
        e.preventDefault();
        let data = $(this).serialize();
        const start_time = "1970-01-01 "+$("#start_time").val();
        const end_time = "1970-01-01 "+$("#end_time").val();

        data += `&start_time=${start_time}&end_time=${end_time}`;

        const btn = $(this).find("[type=submit]");
        const initial_text = btn.html();

        applyLoading(btn);

        $.ajax({
           url : "/api/shift-type/add",
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
    });

    const deleteShift = (id) => {
        $("#delete-shift-id").val(id);
        $("#delete_shift").modal("show");
    }

    $("#delete_shift_form").on("submit", function(e){
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

    const editShift = (id, name, start, end) => {
        $("#edit_id").val(id);
        $("#edit_name").val(name);
        $("#edit_start_time").val(start);
        $("#edit_end_time").val(end);

        $("#edit_shift").modal("show");
    }

    $("#edit_shift").on("submit", function(e){
        e.preventDefault();
        const btn = $(this).find("[type=submit]");

        let data = $(this).serialize();
        const start_time = "1970-01-01 "+$("#edit_start_time").val();
        const end_time = "1970-01-01 "+$("#edit_end_time").val();

        data += `name=${$('#edit_name').val()}&start_time=${start_time}&end_time=${end_time}`;

        const initial_text = btn.html();

        applyLoading(btn);

        $.ajax({
        url : "/api/shift-type/update/"+$("#edit_id").val(),
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

