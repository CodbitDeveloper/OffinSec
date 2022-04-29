<!--

    Created By: Nii Codbit
    Date: 30/03/13

    empty container with the base layout of the dashboard.
    Created this so that we can have something to play around layouts with.
    Copy this whenever you want to create a new page.

    
-->
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{$client->name}} | GUARD ATTENDANCE MANAGEMENT SYSTEM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Guard attendance management system. Allows security agencies to track the attendance of their guards." name="description" />
    <meta content="Codbit Developers" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!--Custom css-->
    <link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/spinkit/spinkit.css')}}" rel="stylesheet" />
    <style>
        .text-small {
            font-size: 14px;
        }

        .text-tiny {
            font-size: 12px;
        }

        .text-black {
            color: black;
        }
    </style>


    <!-- App css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/jquery-toastr/jquery.toast.min.css')}}" rel="stylesheet" />


    <script src="{{asset('assets/js/modernizr.min.js')}}"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Rubik" rel="stylesheet">

</head>

<body>

    <!-- Navigation Bar-->
    <header id="topnav">
        <div class="topbar-main">
            <div class="container-fluid">

                <!-- Logo container-->
                <div class="logo">
                    <a href="index.html" class="logo">
                        <img src="{{asset('assets/images/offin-logo.png')}}" alt="" height="48" class="logo-small">
                        <img src="{{asset('assets/images/offin-logo.png')}}" alt="" height="42" class="logo-large">
                    </a>

                </div>

            </div> <!-- end container -->
        </div>
        <!-- end topbar-main -->

    </header>
    <!-- End Navigation Bar-->


    <div class="wrapper" style="margin-top:-40px;">
        <div class="container-fluid">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="btn-group float-right">
                            <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="#">OSAMS</a></li>
                                <li class="breadcrumb-item active">{{$client->name}}</li>
                            </ol>
                        </div>
                        <h4 class="page-title">{{$client->name}}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title end breadcrumb -->

            <!--
                content you want comes here
            -->
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
                        </div>
                    </div>
                    <!--/ meta -->
                </div>
            </div>
            <div class="row">
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
                            <a href="#">
                                <div class="inbox-item">
                                    <p class="inbox-item-author">{{$site->name}}</p>
                                    <p class="inbox-item-text">{{$site->location}}</p>
                                </div>
                            </a>
                            @endforeach
                            @if($client->sites->count() < 1) <p class="text-muted">No sites for this client yet. Add one.</p>
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
                                <?php $date = Carbon\Carbon::parse($client->start_date) ?>
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
                                                    <select class="show-tick" data-style="btn-primary" title="Select Site" id="graph_sites">
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
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Attendance</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Patrols</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="patrol-loader">
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
                                <div class="table-responsive" id="patrol-table-container">
                                    <table id="patrols-table" class="table table-striped">
                                        <thead>
                                            <th>Site</th>
                                            <th>Patrol Officer</th>
                                            <th>Notes</th>
                                            <th>Areas scanned</th>
                                            <th>Date</th>
                                        </thead>
                                        <tbody id="patrols-table-body"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end col -->

            </div>
        </div> <!-- end container -->
    </div>
    <!-- end wrapper -->

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    2019 &copy; OSAMS. - Made with ❤️ By <a href="http://codbitgh.com">Codbit Ghana Limited</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->


    <!-- jQuery  -->
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/waves.js')}}"></script>
    <script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
    <script src="{{asset('assets/js/avatar.js')}}"></script>
    <script src="{{asset('plugins/jquery-toastr/jquery.toast.min.js')}}" type="text/javascript"></script>

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
        var clientId = "{{$client->id}}";
        $('.selectpicker').selectpicker();
        $('#graph_sites').selectpicker('val', $('#graph_sites option:first').val());

        $('#date').datepicker({
            autoclose: true,
            todayHighlight: true
        });

        $('#date-range, #date-range-2').datepicker({
            toggleActive: true
        });


        $(document).ready(function() {
            @if($client->sites->count() > 0)
            loadGraph("{{date('Y-m-d')}}", $('#graph_sites').val());
            loadPatrols("{{date('Y-m-d')}}", $('#graph_sites').val());
            @else
            $('.loader').css('display', 'none');
            $('.patrol-loader').css('display', 'none');
            $('#chartError').css('display', 'block');
            $('#chartError').html('<p class="text-muted text-small text-center"><b>This client has no sites yet</b></p>');
            @endif
        });


        function loadGraph(date, site) {
            $('#chartError, #lineChart').css('display', 'none');
            $('.loader').css('display', 'block')
            $.ajax({
                url: '/api/client/report',
                data: 'site=' + site + '&date=' + date + '&client={{$client->id}}',
                method: 'GET',
                success: function(data) {
                    $('.loader').css('display', 'none');
                    $('#lineChart').css('display', 'block');

                    var dataset = [];
                    for (var i = 0; i <= 6; i++) {
                        var found = false;
                        for (var k = 0; k < data.sites.length; k++) {
                            if (data.sites[k].day == i) {
                                dataset.push(data.sites[k].total);
                                found = true;
                            }
                        }

                        if (!found) {
                            dataset.push(0);
                        }
                    }! function($) {
                        "use strict";

                        var ChartJs = function() {};

                        ChartJs.prototype.respChart = function(selector, type, data, options) {
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
                                            new Chart(ctx, {
                                                type: 'line',
                                                data: data,
                                                options: options
                                            });
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
                            ChartJs.prototype.init = function() {
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
                    function($) {
                        "use strict";
                        $.ChartJs.init()
                    }(window.jQuery);
                },
                error: function(data) {
                    $('.loader').css('display', 'none');
                    $('#chartError').css('display', 'block');
                    $('#chartError').html('<p class="text-muted text-small text-center"><b>Could not fetch report data.</b></p>')
                }
            });
        }

        $('#date').on('change', function() {
            loadGraph($(this).val(), $('#graph_sites').val());
            loadPatrols($(this).val(), $('#graph_sites').val());
        });

        function loadPatrols(date, site)
        {
            $(".patrol-loader").css("display", "block");
            $("#patrol-table-container").css("display", "none");

            $.ajax({
                url: `/clients/${site}/patrols?date=${date}&site=${site}`,
                success: function(response){
                    $(".patrol-loader").css("display", "none");
                    $("#patrols-table-body").html('');
                    for(let item of JSON.parse(response)){
                        console.log(item)
                        $("#patrols-table-body").append(`
                            <tr>
                            <td>${$("#graph_sites").attr("title")}</td>
                            <td>${item.patrol_officer}</td>
                            <td>${item.notes}</td>
                            <td>${item.scans_count}</td>
                            <td>${item.created_at}</td>
                            </tr>
                        `)
                    }
                    $("#patrol-table-container").css("display", "block");
                }
            });
        }
    </script>

    <!-- App js -->
    <script src="{{asset('assets/js/jquery.core.js')}}"></script>
    <script src="{{asset('assets/js/jquery.app.js')}}"></script>

</body>

</html>