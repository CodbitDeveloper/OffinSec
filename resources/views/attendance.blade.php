@extends('layouts.main-layout', ['page_title' => 'Attendance'])
@section('styles')
<link href="{{asset('plugins/fullcalendar/css/fullcalendar.min.css')}}" rel="stylesheet" />
<!--Bootstrap Select-->
<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" />

<!--Date Picker-->
<link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('plugins/bootstrap-timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('plugins/custombox/css/custombox.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/spinkit/spinkit.css')}}" rel="stylesheet" />

<style>
    #view_attendance{
        display: none;
    }

    .text-damage{
        font-size: 12px;
    }
</style>

@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="card-box">
            <div class="row">
                <div class="col-lg-3">
                    <a href="#" data-toggle="modal" data-target="#record-attendance" class="btn btn-lg btn-custom btn-block waves-effect m-t-20 waves-light">
                        Record Attendance
                    </a>
                    <div class="mt-5 d-none d-xl-block">
                        <h5 class="text-center">How It Works ?</h5>

                        <ul class="pl-3">
                            <li class="text-muted mb-3">
                                Select any date on the calendar to begin.
                            </li>
                            <li class="text-muted mb-3">
                                Select a site you want to view attendance for.
                            </li>
                        </ul>
                    </div>
                </div> <!-- end col-->
                <div class="col-lg-9">
                    <div id="calendar"></div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>

        <!-- BEGIN MODAL -->
        <div class="modal fade" id="view-attendance" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center border-bottom-0 d-block">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">View Attendance</h4>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <select class="selectpicker show-tick" data-live-search="true" data-style="btn-light" title="Select A Site"
                                    id="site" name="site">
                                    @foreach($sites as $site)
                                        <option value="{{$site->id}}">{{$site->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button id="view_attendance" class="btn btn-success waves-effect w-md mr-2 mb-2">
                                View Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Add Category -->
        <div class="modal fade" id="record-attendance" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center border-bottom-0 d-block">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title mt-2">Record Attendance</h4>
                    </div>
                    <div class="modal-body p-4">
                        <form role="form" id="record_attendance">
                            <div class="mb-2">
                                <p class="mb-1 font-weight-bold text-muted">Search for guard<br /><span
                                        class="text-muted" style="font-weight: 300; font-size: 12px;"><i>Begin
                                            typing a guard's name in the box below</i></span></p>
                                <input type="text" name="guard" id="autocomplete" class="form-control" />
                                <input type="hidden" name="guard_id" class="verifiable" id="guard_id" />
                            </div>
                            <div class="form-group mb-2">
                                <select class="selectpicker show-tick" data-style="btn-light col-md-12" title="Select A Site"
                                    id="record_site" name="site_id" data-live-search="true">
                                    @foreach($sites as $site)
                                        <option value="{{$site->id}}">{{$site->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
        <!-- END MODAL -->
    </div>
    <!-- end col-12 -->
</div> <!-- end row -->
@endsection
@section('scripts')
    <!-- Jquery-Ui -->
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- SCRIPTS -->
    <script src="{{asset('plugins/moment/moment.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/js/fullcalendar.min.js')}}"></script>

    <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('plugins/autocomplete/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-timepicker/bootstrap-timepicker.js')}}"></script>

    
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

    <script>
        var string = "{{$guards}}";
        var attendance_date;
        
        var string = string.replace(/&quot;/g, '"');
        var string = string.replace(/&amp;/g, '&');
        
        
        var guards = JSON.parse(string);

        var formatted = $.map(guards, function (guard) { return { value: guard.firstname+' '+guard.lastname, data: { id: guard.id } }; });


        $('#calendar').fullCalendar({
            dayClick: function (day) {
                attendance_date = day.format();
                $('#view-attendance').modal('show');
            },
            defaultView: 'month'
        });

        $('#autocomplete').devbridgeAutocomplete({
            lookup: formatted,
            minChars: 1,
            onSelect: function (suggestion) {
                $('#guard_id').val(suggestion.data.id);
            },
            showNoSuggestionNotice: true,
            noSuggestionNotice: 'Sorry, no guard matches your query'
        });

        $('#autocomplete').on('keydown', function () {
            $('#guard_id').val(null);
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
        
        $('#site').on('change', function(){
            if($(this).val() != null || $(this).val() == ''){
                $('#view_attendance').css('display', 'block');
            }
        });

        $('#view_attendance').on('click', function(e){
            e.preventDefault();
            if(attendance_date == null){
                $(this).closest('.modal-body').append('<span class="text-danger text-small">Select a date </span>');
            }else{
                window.open(`/attendance-details?date=${attendance_date}&site=${$('#site').val()}`, "_blank");
            } 
        });


        $('#record_attendance').on('submit', function(e){
            e.preventDefault();
            $('.text-damage').css('display', 'none');
            var error = false;

            $(this).find('.verifiable').each(function(){
                if($(this).val() == '' || $(this).val() == null){
                    error = true;
                    $(this).closest('div').append('<p class="text-danger text-damage">This field is required</p>');
                }
            });

            if($('#record_site').val() == '' || $('#site').val() == null){
                error = true;
                $('#record_site').closest('.form-group').append('<p class="text-danger text-damage">Select a site</p>');
            }


            data = $(this).serialize();
            var date_time = $('#date_in').val()+' '+$('#time_in').val();
            data += '&date_time='+date_time;
            btn = $(this).find('[type="submit"]');
            
            if(!error){
                applyLoading(btn);


                $.ajax({
                    url : '/api/attendance/add',
                    method : 'POST',
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
            }
            
        });
        
    </script>
@endsection