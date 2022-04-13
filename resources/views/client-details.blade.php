@extends('layouts.main-layout', ['page_title' => $client->name])
@section('styles')
<link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/spinkit/spinkit.css')}}" rel="stylesheet" />
<style>
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
                            <h4 class="mt-1 mb-1 font-18">{{$client->name}}</h4>
                            <p class="font-13 text-light">Security Provision</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-right">
                            <button type="button" class="btn btn-light waves-effect" data-toggle="modal" data-target="#edit-client">
                                Edit Client
                            </button>
                        </div>
                    </div>
                </div>
        </div>
                    <!--/ meta -->
            <div class="row text-right">
                <div class="col-sm-12">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#change-duration">
                        <p class="badge badge-pill">
                            Edit contract duration
                        </p>
                    </a>
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#generate-code">
                        <p class="badge badge-pill">
                            Manage access codes
                        </p>
                    </a>
                    <a href="/client/manage-salaries/{{$client->id}}">
                        <p class="badge badge-pill">
                            Manage salaries
                        </p>
                    </a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-xl-4">
                    <!-- Personal-Information -->
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-20">Project Details</h4>
                        <div class="panel-body">
                            <p class="text-muted font-13" style="min-height: 120px;">
                                {{$client->description}}
                            </p>

                            <hr />

                            <div class="text-left">
                                <p class="text-muted font-13"><strong>Client Name :</strong> <span class="m-l-15">{{$client->name}}</span></p>

                                <p class="text-muted font-13"><strong>Mobile :</strong><span class="m-l-15">{{$client->phone_number}}</span></p>

                                <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15">{{$client->email}}</span></p>

                                <p class="text-muted font-13"><strong>Contract Start :</strong> <span class="m-l-15">{{Carbon\Carbon::parse($client->start_date)->format('jS F Y')}}</span></p>

                                <p class="text-muted font-13"><strong>Contract End :</strong> <span class="m-l-15">{{Carbon\Carbon::parse($client->end_date)->format('jS F Y')}}</span></p>


                            </div>
                        </div>
                    </div>
                    <!-- Personal-Information -->

                    <div class="card-box ribbon-box">
                        <div class="ribbon ribbon-primary">Sites</div>
                        <div class="clearfix"></div>
                        <div class="inbox-widget">
                            @foreach($client->sites as $site)
                                <div class="inbox-item">
                                    <p class="inbox-item-author">{{$site->name}} - [<b>{{$site->access_code}}</b>]</p>
                                    <p class="inbox-item-text">{{$site->location}}</p>
                                    <p class="inbox-item-date m-t-10">
                                        <a href="/site/{{$site->id}}"><button type="button" class="btn btn-icon btn-sm waves-effect waves-light btn-success">
                                            View </button></a>
                                        <br/><span class="text-tiny"><a href="/roster/{{$site->id}}">Duty Roster</a></span>
                                    </p>
                                </div>
                            @endforeach
                            @if($client->sites->count() < 1)
                                <p class="text-muted">No sites for this client yet. Add one.</p>
                            @endif
                        </div>
                    </div>

                </div>


                <div class="col-xl-8">

                    <div class="row">

                        <div class="col-sm-4">
                            <div class="card-box tilebox-one">
                                <i class="icon-people float-right text-muted"></i>
                                <h6 class="text-muted text-uppercase mt-0">Guards</h6>
                                <h2 class="m-b-20">{{$client->guards->count()}}</h2>
                            </div>
                        </div><!-- end col -->

                        <div class="col-sm-4">
                            <div class="card-box tilebox-one">
                                <i class="icon-home float-right text-muted"></i>
                                <h6 class="text-muted text-uppercase mt-0">Sites</h6>
                                <h2 class="m-b-20"><span>{{$client->sites->count()}}</span></h2>
                            </div>
                        </div><!-- end col -->

                        <div class="col-sm-4">
                            <div class="card-box tilebox-one">
                                <i class="icon-calender float-right text-muted"></i>
                                <h6 class="text-muted text-uppercase mt-0">Days Elapsed</h6>
                                <?php $date = Carbon\Carbon::parse($client->start_date)?>
                                <h2 class="m-b-20">{{$date->diffInDays(Carbon\Carbon::now())}}
                                    @if(!$date->isPast())
                                        <span class="text-muted text-small"> DAYS FROM TODAY</span>
                                    @endif
                                </h2>
                            </div>
                        </div><!-- end col -->

                    </div>
                    <!-- end row -->
            <div class="card-box">
                <h4 class="header-title mt-0 mb-3">Reports</h4>
                <div class="">
                    <div class="row mb-4">
                        <div class="col-sm-10">
                            <div class="project-sort float-right">
                                <div class="project-sort-item">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <select class="show-tick" data-style="btn-primary"
                                                title="Select Site" id="graph_sites">
                                                @foreach($client->sites as $site)
                                                    <option value="{{$site->id}}">{{$site->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="date" placeholder="Date">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="loader">
                    <div class="sk-circle loader">
                        <div class="sk-circle1 sk-child"></div>
                        <div class="sk-circle2 sk-child"></div>
                        <div class="sk-circle3 sk-child"></div>
                        <div class="sk-circle4 sk-child"></div>
                        <div class="sk-circle5 sk-child"></div>
                        <div class="sk-circle6 sk-child"></div>
                        <div class="sk-circle7 sk-child"></div>
                        <div class="sk-circle8 sk-child"></div>
                        <div class="sk-circle9 sk-child"></div>
                        <div class="sk-circle10 sk-child"></div>
                        <div class="sk-circle11 sk-child"></div>
                        <div class="sk-circle12 sk-child"></div>
                    </div>
                </div>
                <canvas id="lineChart" height="350" class="mt-4" style="display:none;"></canvas>
                <div id="chartError"></div>
            </div>

            <div class="card-box">
                <h4 class="header-title mb-3">Add Site</h4>

                <form class="form-horizontal" id="new_site_form">
                    <div class="form-row m-b-25">
                        <div class="col-md-6 col-sm-12">
                            <label for="name">Site Name</label>
                            <input class="form-control required" type="text" placeholder="Codbit ADC" name="name">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="location">Site Location</label>
                            <input class="form-control required" type="text" id="location" placeholder="Ring Road Central"
                                name="location">
                        </div>
                    </div>

                    <div class="form-row mb-4">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="garuantor_phone_number" class="col-form-label"><b>Contact Number</b></label>
                            <input type="tel" placeholder="" class="form-control required" name="phone_number" id="phone_number" data-mask="(999) 999-999999">
                            <span class="font-10 text-muted">(233) 244-500500</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="supervisor" class="col-form-label"><b>&nbsp;</b></label>
                            <select class="selectpicker show-tick form-control" data-style="btn-primary" 
                            title="Supervisor" id="supervisor" name="guard_id" data-live-search="true">
                                @foreach($guards as $guard)
                                <option value="{{$guard->id}}" data-subtext="{{$guard->phone_number}}">{{$guard->firstname.' '.$guard->lastname}}</option>
                                @endforeach
                            </select>
                            <div id="sup_error">

                            </div>
                            <input type="hidden" name="client_id" class="required" value="{{$client->id}}"/>
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="form-group col-md-6">
                            <label for="supervisor" class="col-form-label"><b>&nbsp;</b></label>
                            <select class="selectpicker show-tick form-control" data-style="btn-primary" 
                            title="Zone" id="zone" name="zone_id" data-live-search="true">
                                @foreach($zones as $zone)
                                <option value="{{$zone->id}}">{{$zone->name}}</option>
                                @endforeach
                            </select>
                            <div id="sup_error">

                            </div>
                            <input type="hidden" name="client_id" class="required" value="{{$client->id}}"/>
                        </div>
                    </div>
                    <div class="form-group account-btn text-center m-t-10">
                        <div class="col-12">
                            <button class="btn w-lg btn-custom waves-effect waves-light" type="submit">Add
                                Site</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
                <!-- end col -->

    </div>
@endsection
@section('modals')
    <div class="modal fade" id="edit-client" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title mt-2">Edit Client</h4>
                </div>
                <div class="modal-body p-4">
                    <form role="form" id="edit_client_form">
                        
                    @csrf
                    <div class="form-row mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="name">Client Name</label>
                            <input class="form-control resetable" type="text" id="name" placeholder="Codbit Ghana Ltd" name="name" value="{{$client->name}}">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="email">Email address</label>
                            <input class="form-control resetable" type="text" id="email" placeholder="info@codbitgh.com" name="email" value="{{$client->email}}">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label for="email">Phone</label>
                            <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control resetable" name="phone_number" value="{{$client->phone_number}}">
                        </div>
                        <input type="hidden" name="id" value="{{$client->id}}"/>
                    </div>

                    <div class="form-row mb-4">
                        <div class="col-md-4 col-sm-12">
                            <label for="contact_person">Contact Person Name</label>
                            <input class="form-control resetable" type="text" placeholder="Abraham Attah" name="contact_person_name" value="{{$client->contact_person_name}}">
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <label for="no_of_guards">No. of Guards</label>
                            <input class="form-control resetable" type="number" min="1" id="no_of_guards" name="number_of_guards" value="{{$client->number_of_guards}}">
                        </div>
                    </div>
                    <div class="form-row mb-4">
                        <div class="col-md-12 col-sm-12">
                            <label for="description">Job Description</label>
                            <input class="form-control resetable" type="text" id="description" name="description" value="{{$client->description}}">
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
    <div class="modal fade" id="change-duration" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title mt-2">Are you sure?</h4>
                </div>
                <div class="modal-body p-4">
                    <p>Changing the contract duration for this client cannot be undone. Make sure you know what you are doing before you save.</p>
                    <form role="form" id="change_contract">
                        
                    @csrf
                        <div class="form-group col-md-12">
                            <div>
                                <div class="input-daterange input-group" id="date-range-2">
                                    <input type="text" class="form-control" name="start_date" placeholder="Start" />
                                    <input type="text" class="form-control" name="end_date" placeholder="End" />
                                </div>
                                <span class="text-danger text-small" id="date_error_2"></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger ml-1 waves-effect waves-light save-category">Yes, I understand</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="generate-code" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title mt-2">Generate and Send Access Code</h4>
                </div>
                <div class="modal-body p-4">
                    <p>Generate access code for client. Send this code to client via client's email address to help the client access their page.</p>
                    <table class="table table-striped">
                        <thead>
                            <th>Access code</th>
                            <th>Expiry date</th>
                        </thead>
                        <tbody>
                            @foreach($client->access_codes as $code)
                            <tr>
                                <td>{{$code->access_code}}</td>
                                <td>{{$code->expires_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <form role="form">
                        
                    @csrf
                        <div class="form-row mb-2">
                            <div class="col-md-12 col-sm-12">
                                <input class="form-control resetable" type="text" id="token_email" placeholder="info@codbitgh.com" name="email" value="{{$client->email}}">
                            </div>
                        </div>
                        <div class="text-right mt-2">
                            <button type="button" class="btn btn-light waves-effect"  id="btn_generate">Generate Code</button>
                            <button type="button" class="btn btn-icon ml-1 waves-effect waves-light btn-success" id="btn_send">Send</button>
                            <button type="button" class="btn btn-danger ml-1 waves-effect waves-light save-category" id="btn_reset"> Reset Code</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!--Telephone Mask-->
    <script src="{{asset('/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js')}}" type="text/javascript"></script>
    
    <!--Bootstrap Datepicker-->
    <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    
    <!--JQUERY toast-->
    
    <!-- Chart JS -->
    <script src="{{asset('plugins/chart.js/chart.bundle.js')}}"></script>
    
    <!--Bootstrap Select-->
    <script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}" type="text/javascript"></script>

    <script>

        $('.selectpicker').selectpicker();
        $('#graph_sites').selectpicker('val', $('#graph_sites option:first').val());

        $('#date').datepicker({
            autoclose: true,
            todayHighlight: true
        });

        $('#date-range, #date-range-2').datepicker({
            toggleActive: true
        });
        
        $('#new_site_form').on('submit', function(e){
            $(this).find('.text-danger').css('display', 'none');
            e.preventDefault();

            var btn = $(this).find('[type="submit"]');
            var data = $(this).serialize();


            var error = false;

            $(this).find('.required').each(function(){
                if($(this).val() == '' || $(this).val() == null){
                    error = true;
                    $(this).closest('div').append('<p class="text-danger text-small">This field is required</p>');
                }
            });

            if($('#supervisor').val() == '' || $('#supervisor').val() == null){
                error = true;
                $('#sup_error').append('<p class="text-danger text-small">This field is required</p>');
            }


            if(!error){
                $('.text-danger').css('display', 'none');
                applyLoading(btn);

                $.ajax({
                    url: '/api/sites/add',
                    method: 'POST',
                    data: data,
                    success: function(data){
                        removeLoading(btn, 'Add Client');
                        if(data.error){
                            removeLoading(btn, 'Add Client');
                        
                            $.toast({
                                text : data.message,
                                heading : 'Error',
                                position: 'top-right',
                                showHideTransition : 'slide', 
                                bgColor: '#d9534f'
                            });
                        }else{
                            $('#new_site_form').trigger('reset');
                            $.toast({
                                text : data.message,
                                heading : 'Done',
                                position: 'top-right',
                                bgColor : '#5cb85c',
                                showHideTransition : 'slide'
                            });

                            $('.inbox-widget').append('<a href="javascript:void(0)">'+
                            '<div class="inbox-item"><p class="inbox-item-author">'+data.data.name+'</p>'+
                            '<p class="inbox-item-text">'+data.data.location+'</p><p class="inbox-item-date m-t-10">'+
                            '<a href="/site/'+data.data.id+'"><button type="button" class="btn btn-icon btn-sm waves-effect waves-light btn-success">'+
                            'View </button></a>'+
                            '<br/><span class="text-tiny"><a href="/roster/'+data.data.id+'">Duty Roster</a></span></p></div></a>'); 
                                
                        }
                    },
                    error: function(err){
                        removeLoading(btn, 'Add Client');
                        
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

        $(document).ready(function(){
            @if($client->sites->count() > 0)
                loadGraph({{date('Y-m-d')}}, $('#graph_sites').val());
            @else
                $('.loader').css('display', 'none');
                $('#chartError').css('display', 'block');
                $('#chartError').html('<p class="text-muted text-small text-center"><b>This client has no sites yet</b></p>');
            @endif
        });
        

        function loadGraph(date, site){
            $('#chartError, #lineChart').css('display', 'none');
            $('.loader').css('display', 'block')
            $.ajax({
                url : '/api/client/report',
                data: 'site='+site+'&date='+date+'&client={{$client->id}}',
                method: 'GET',
                success: function(data){
                    $('.loader').css('display', 'none');
                    $('#lineChart').css('display', 'block');
                    
                    var dataset = [];
                    for(var i = 1; i <= 7; i++){
                        var found = false;
                        for(var k = 0; k < data.sites.length; k++){
                            if(data.sites[k].day == i){
                                dataset.push(data.sites[k].total);
                                found = true;
                            }
                        }

                        if(!found){
                            dataset.push(0);
                        }
                    }
                    !function ($) {
                        "use strict";

                        var ChartJs = function () { };

                        ChartJs.prototype.respChart = function (selector, type, data, options) {
                            // get selector by context
                            var ctx = selector.get(0).getContext("2d");
                            // pointing parent container to make chart js inherit its width
                            var container = $(selector).parent();

                            // enable resizing matter
                            $(window).resize(generateChart);

                            // this function produce the responsive Chart JS
                            function generateChart() {
                                // make chart width fit with its container
                                var ww = selector.attr('width', $(container).width());
                                switch (type) {
                                    case 'Line':
                                        new Chart(ctx, { type: 'line', data: data, options: options });
                                        break;
                                    default:
                                        break;
                                }
                                // Initiate new chart or Redraw

                            };
                            // run function - render chart at first load
                            generateChart();
                        },

                            //init
                            ChartJs.prototype.init = function () {
                                //creating lineChart
                                var lineChart = {
                                    labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                                    datasets: [{
                                        label: data.site.name,
                                        fill: false,
                                        backgroundColor: '#4eb7eb',
                                        borderColor: '#4eb7eb',
                                        data: dataset
                                    }]
                                };

                                var lineOpts = {
                                    responsive: true,
                                    // title:{
                                    //     display:true,
                                    //     text:'Chart.js Line Chart'
                                    // },
                                    tooltips: {
                                        mode: 'index',
                                        intersect: false
                                    },
                                    hover: {
                                        mode: 'nearest',
                                        intersect: true
                                    },
                                    scales: {
                                        xAxes: [{
                                            display: true,
                                            // scaleLabel: {
                                            //     display: true,
                                            //     labelString: 'Month'
                                            // },
                                            gridLines: {
                                                color: "rgba(0,0,0,0.1)"
                                            }
                                        }],
                                        yAxes: [{
                                            gridLines: {
                                                color: "rgba(255,255,255,0.05)",
                                                fontColor: '#fff'
                                            },
                                            ticks: {
                                                max: 100,
                                                min: 0,
                                                stepSize: 10
                                            }
                                        }]
                                    }
                                };

                                this.respChart($("#lineChart"), 'Line', lineChart, lineOpts);
                            },
                            $.ChartJs = new ChartJs, $.ChartJs.Constructor = ChartJs

                    }(window.jQuery),

                        //initializing
                        function ($) {
                            "use strict";
                            $.ChartJs.init()
                        }(window.jQuery);
                },
                error: function(data){
                    $('.loader').css('display', 'none');
                    $('#chartError').css('display', 'block');
                    $('#chartError').html('<p class="text-muted text-small text-center"><b>Could not fetch report data.</b></p>')
                }
            });
        }

        $('#date').on('change', function(){
            loadGraph($(this).val(), $('#graph_sites').val());
        });

        $('#edit_client_form').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');
            
            data = $(this).serialize();

            var error = false;
            
            $(this).find('.resetable').each(function(){
                if($(this).val()=='' || $(this).val()==null){
                    error = true;
                    $(this).closest('div').append('<span class="text-danger text-small">This field is required</span>')
                }
            });

            $('#date-range').find('input').each(function(){
                if($(this).val()=='' || $(this).val() == null){
                    error = true;
                    $('#date_error').html('All date fields are required');
                } 
            });

            if(!error){
                $(this).find('.text-danger').css('display', 'none');

                applyLoading(btn);

                $.ajax({
                    url: '/api/client/update',
                    method: 'POST',
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
                                    location.replace('/client/'+data.data.id);
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

        $('#change_contract').on('submit',function(e){
            e.preventDefault();
            var error = false;
            var data = $(this).serialize();
            data+='&id={{$client->id}}'

            $('#date-range-2').find('input').each(function(){
                if($(this).val()=='' || $(this).val() == null){
                    error = true;
                    $('#date_error_2').html('All date fields are required');
                } 
            });

            var btn = $(this).find('[type="submit"]');
            if(!error){
                applyLoading(btn);

                $.ajax({
                    url: '/api/client/change-duration',
                    method: 'PUT',
                    data: data,
                    success: function(data){
                        removeLoading(btn, 'Yes, I understand');
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
                                    location.replace('/client/'+data.data.id);
                                }, 500);
                            }
                    },
                    error: function(err){
                        removeLoading(btn, 'Yes, I understand');
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

        $('#btn_generate').on('click', function(){
            var error = false;
            var data = "client_id={{$client->id}}"
            var btn = $(this);
            var initial = btn.html();
            
            if(!error){
                applyLoading(btn);

                $.ajax({
                    url: '/api/access-code/add',
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
            }
        });

        $('#btn_send').on('click', function(){
            var error = false;
            var data = "client_id={{$client->id}}&email="+$('#token_email').val()
            var btn = $(this);
            var initial = btn.html();

            if(!error){
                applyLoading(btn);

                $.ajax({
                    url: '/api/access-code/send-token',
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
            }
        });

        $('#btn_reset').on('click', function(){
            var error = false;
            var data = "client_id={{$client->id}}";
            var btn = $(this);
            var initial = btn.html();

            if(!error){
                applyLoading(btn);

                $.ajax({
                    url: '/api/access-code/reset',
                    method: 'PUT',
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
            }
        });
    </script>
@endsection