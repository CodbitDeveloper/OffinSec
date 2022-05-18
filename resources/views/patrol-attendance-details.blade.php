@extends('layouts.main-layout', ['page_title' => 'View Attendance'])
@section('styles')
<link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs tabs-bordered">
                        <li class="nav-item">
                            <a href="#time-in" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                 Check In
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="time-in">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Present</th>
                                            <th>Applicable</th>
                                            <th>With Permission</th>
                                            <th>Off Duty</th>
                                            <th>Reliever</th>
                                            <th>With Overtime</th>
                                        </tr>
                                    </thead>
                                    @isset($patrolAttendance)
                                        @foreach($patrolAttendance->lines as $attendance)
                                            <tr>
                                                <td>{{$attendance->security_guard ? $attendance->security_guard->firstname.' '.$attendance->security_guard->lastname : ''}}</td>
                                                <td>{{$attendance->present ? 'Yes' : 'No'}}</td>
                                                <td>{{$attendance->applicable ? 'Yes' : 'No'}}</td>
                                                <td>{{$attendance->with_permission ? 'Yes' : 'No'}}</td>
                                                <td>{{$attendance->off_duty ? 'Yes' : 'No'}}</td>
                                                <td>{{$attendance->reliever != null ? $attendance->reliever->firstname.' '.$attendance->reliever->lastname : 'N/A'}}</td>
                                                <td>{{$attendance->with_overtime ? 'Yes' : 'No'}}</td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="time-out">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width:100%;">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Shift</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        @isset($attendances[0])
                                            @foreach($attendances[0] as $attendance)
                                                <tr>
                                                    <td>{{$attendance->owner_guard->firstname.' '.$attendance->owner_guard->lastname}}</td>
                                                    <td>{{$attendance->shift_type == null ? 'N/A' : $attendance->shift_type->name}}</td>
                                                    <td>{{date('jS F, Y', strtotime($attendance->date_time))}}</td>
                                                    <td>{{date('H:i:s', strtotime($attendance->date_time))}}</td>
                                                    <td><a href="javascript:void(0)" onclick="edit('{{$attendance->id}}', '{{date('m/d/Y', strtotime($attendance->date_time))}}', '{{date('H:i:s', strtotime($attendance->date_time))}}', '{{$attendance->type}}')">Edit</a></td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="random">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Shift</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @isset($attendances[2])
                                        @foreach($attendances[2] as $attendance)
                                            <tr>
                                                <td>{{$attendance->owner_guard->firstname.' '.$attendance->owner_guard->lastname}}</td>
                                                <td>{{$attendance->shift_type == null ? 'N/A' : $attendance->shift_type->name}}</td>
                                                <td>{{date('jS F, Y', strtotime($attendance->date_time))}}</td>
                                                <td>{{date('H:i:s', strtotime($attendance->date_time))}}</td>
                                                <td><a href="javascript:void(0)" onclick="edit('{{$attendance->id}}', '{{date('m/d/Y', strtotime($attendance->date_time))}}', '{{date('H:i:s', strtotime($attendance->date_time))}}', '{{$attendance->type}}')">Edit</a></td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="patrol">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Site</th>
                                            <th>Patrol Officer</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    @isset($patrolAttendances)
                                        @foreach($patrolAttendances as $attendance)
                                            <tr>
                                                <td>{{$attendance->site->name}}</td>
                                                <td>{{$attendance->user->firstname . ' ' . $attendance->user->lastname}}</td>
                                                <td>{{date('jS F, Y', strtotime($attendance->created_at))}}</td>
                                                <td>{{date('H:i:s', strtotime($attendance->created_at))}}</td>
                                                <td><a href="/admin/patrol-attendance/{{$attendance->id}}">View</a></td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </table>
                            </div>
                        </div>
                </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
    <!-- end col-12 -->
</div>
<div class="modal fade" id="edit-attendance" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center border-bottom-0 d-block">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title mt-2">Edit Attendance</h4>
            </div>
            <div class="modal-body p-4">
                <form role="form" id="edit_attendance_form">
                    <div class="form-group mb-4">
                        <select class="selectpicker show-tick" data-style="btn-light col-md-12" title="Attendance Type"
                            id="record_type" name="type" data-live-search="true">
                            <option value="1">Time In</option>
                            <option value="0">Time Out</option>
                            <option value="2">Random Check</option>
                        </select>
                    </div>
                    <div class="form-row mb-4">
                        <div class="col-md-6 col-sm-12">
                            <label class="font-weight-bold text-muted">Date</label>
                            <input class="form-control verifiable" id="date_in"/>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="font-weight-bold text-muted">Time</label>
                            <input class="form-control verifiable" id="time_in"/>
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
@endsection
@section('scripts')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-timepicker/bootstrap-timepicker.js')}}"></script>

<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap4.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script>
    let selected_attendance = null;

    $(".table").DataTable({
        "bLengthChange": false,
        dom: 'Blfrtip',
        buttons: [ 
            {
                extend: 'excelHtml5',
                title: name
            },
            {
                extend: 'pdfHtml5',
                title: name
            }]
    });

    $('#date_in').datepicker({
            autoclose: true,
            todayHighlight: true
        });

    $('#time_in').timepicker({
        defaultTIme: false,
        icons: {
            up: 'mdi mdi-chevron-up',
            down: 'mdi mdi-chevron-down'
        },
        minuteStep: 1,
    });

    const edit = (id, date, time, type) => {
        selected_attendance = id;
        $("#date_in").val(date);
        $("#date_in").datepicker("update", date);
        $("#time_in").val(time);
        $("#record_type").val(type);
        $("#record_type").selectpicker("refresh");

        $("#edit-attendance").modal("show");
    }

    $("#edit_attendance_form").on("submit", function(e){
        e.preventDefault();

        data = $(this).serialize();
        var date_time = $('#date_in').val()+' '+$('#time_in').val();
        data += '&date_time='+date_time;
        btn = $(this).find('[type="submit"]');
        
        applyLoading(btn);


        $.ajax({
            url : `/api/attendance/update/${selected_attendance}`,
            method : 'PUT',
            data : data,
            success: function(data){
                removeLoading(btn, 'Save');
                    if(data.error){
                        removeLoading(btn, 'Save');

                        $.toast({
                            text : data.message,
                            heading : 'Error',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#d9534f'
                        });
                    }else{

                        $('#new_client').trigger('reset');
                        $.toast({
                            text : data.message,
                            heading : 'Done',
                            position: 'top-right',
                            bgColor : '#5cb85c',
                            showHideTransition : 'slide'
                        });
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
    })
</script>
@endsection
