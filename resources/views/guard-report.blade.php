@extends('layouts.main-layout', ['page_title' => 'Guard Reports'])
@section('styles')
<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    <div class="row">
                <div class="col-xl-4">
                    <!-- Personal-Information -->
                    <div class="card-box">
                        <h4 class="header-title mt-0 m-b-20">Select Month</h4>
                        <div class="panel-body">

                        <form action="#" id="report_type">
                                @csrf
                                    <div class="form-group mb-2">
                                        <select id="type" class="selectpicker show-tick required" data-style="btn-custom" 
                                        title="Report Type">
                                            <option value="1">Guards by site</option>
                                            <option value="2">Guards by gender</option>
                                            <option value="3">Guards by age distribution</option>
                                            <!--option value="4">Site reports</option-->
                                        </select>
                                    </div>
                                    <div class="form-group mb-2" id="site-container" style="display:none;">
                                        <select class="selectpicker show-tick" data-style="btn-light" 
                                        title="Select Site" data-live-search="true" id="site_id">
                                            @foreach($clients as $client)
                                            <optgroup label="{{$client->name}}">
                                            @foreach($client->sites as $site)
                                                <option id="{{$site->id}}">{{$site->name}}</option>
                                            @endforeach
                                            </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-2" id="date-container" style="display:none;">
                                        <label>Report date range</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range">
                                                <input type="text" class="form-control" name="start" id="start" placeholder="Start" />
                                                <input type="text" class="form-control" name="end" id="end" placeholder="End" />
                                            </div>
                                            <span class="text-danger text-small" id="date_error"></span>
                                        </div>
                                    </div>
                                    <div class="text-right mt-4">                               
                                        <button type="submit" class="btn btn-custom">View Report</button>
                                    </div>
                            </form>

                        </div>
                    </div>
                </div>


                <div class="col-xl-8">

                    <div class="row">

                        
                        <div class="card-box col-sm-12">
                            <h4 class="header-title m-t-0 m-b-30">Report Card</h4>
                            <div class="col-sm-12" id="chart-container">
                                <canvas id="reportChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->


                </div>
                <!-- end col -->

            </div>
@endsection
@section('scripts')
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
<script src="{{asset('plugins/chart.js/chart.bundle.js')}}"></script>
<script>
    $('#date-range').datepicker({
            toggleActive: true,
            orientation: "bottom auto"
        });

    $("#type").on("change", function(){
        if($(this).val() == "4"){
            $("#site-container, #date-container").css('display', 'block');
        }else{
            $("#site-container, #date-container").css('display', 'none');
        }
    });

    function resetGraph(){
            $('#chart-container').html('&nbsp;');
            $('#chart-container').html('<canvas id="reportChart"></canvas>');
        }

    $("#report_type").on("submit", function(e){
        e.preventDefault();
        resetGraph();
        var ctx = $('#reportChart').get(0).getContext("2d");
        
        // pointing parent container to make chart js inherit its width
        var container = $('#reportChart').parent();
        var ww = $('#reportChart').attr('width', $(container).width());

        value = $('#type').val();
        $(this).find("text-danger").css("display", 'none');

        var btn = $("#report_type").find("[type='submit']");
        var initial = btn.html();


        switch(value){
            case "1":
                applyLoading(btn);
                $.ajax({
                    url: "/api/guards/reports/site",
                    method: "GET",
                    success: function(data){
                        removeLoading(btn, initial);
                        console.log(data);
                        var dat = data.data;

                        var labels = [];
                        var dataset = [];


                        for(var i = 0; i < dat.length; i++){
                            let temp = dat[i];
                            labels.push(temp.name);
                            dataset.push(temp.guard_count);
                        }
                        
                        var barChart = {
                            labels: labels,
                            datasets: [
                                {
                                    label: "Guard Count",
                                    backgroundColor: "rgba(2, 192, 206, 0.3)",
                                    borderColor: "#F57C00",
                                    borderWidth: 2,
                                    hoverBackgroundColor: "rgba(2, 192, 206, 0.7)",
                                    hoverBorderColor: "#FF9C00",
                                    data: dataset
                                }
                            ]
                        };
                        
                        new Chart(ctx, {type: 'bar', data: barChart, options: null});
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
                break;
            case "2":
                applyLoading(btn);
                $.ajax({
                    url: "/api/guards/reports/gender",
                    method: "GET",
                    success: function(data){
                        removeLoading(btn, initial);
                        console.log(data);

                        var dat = data.data;

                        var labels = [];
                        var dataset = [];


                        for(var i = 0; i < dat.length; i++){
                            let temp = dat[i];
                            labels.push(temp.gender);
                            dataset.push(temp.total);
                        }
                        
                        var barChart = {
                            labels: labels,
                            datasets: [
                                {
                                    label: "Guard Count",
                                    backgroundColor: "rgba(2, 192, 206, 0.3)",
                                    borderColor: "#F57C00",
                                    borderWidth: 2,
                                    hoverBackgroundColor: "rgba(2, 192, 206, 0.7)",
                                    hoverBorderColor: "#FF9C00",
                                    data: dataset
                                }
                            ]
                        };


                        options = {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        userCallback: function(label, index, labels) {
                                            // when the floored value is the same as the value we have a whole number
                                            if (Math.floor(label) === label) {
                                                return label;
                                            }

                                        },
                                    }
                                }],
                            },
                        }
                        
                        new Chart(ctx, {type: 'bar', data: barChart, options: options});
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
                break;
            case "3":
                applyLoading(btn);
                $.ajax({
                    url: "/api/guards/reports/age",
                    method: "GET",
                    success: function(data){
                        removeLoading(btn, initial);
                        console.log(data);

                        var dat = data.data[0];

                        var labels = Object.keys(dat);
                        var dataset = Object.values(dat);
                        
                        console.log(labels);
                        console.log(dataset);

                        var barChart = {
                            labels: labels,
                            datasets: [
                                {
                                    label: "Guard Count",
                                    backgroundColor: "rgba(2, 192, 206, 0.3)",
                                    borderColor: "#F57C00",
                                    borderWidth: 2,
                                    hoverBackgroundColor: "rgba(2, 192, 206, 0.7)",
                                    hoverBorderColor: "#FF9C00",
                                    data: dataset
                                }
                            ]
                        };


                        options = {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        userCallback: function(label, index, labels) {
                                            // when the floored value is the same as the value we have a whole number
                                            if (Math.floor(label) === label) {
                                                return label;
                                            }

                                        },
                                    }
                                }],
                            },
                        }
                        
                        new Chart(ctx, {type: 'bar', data: barChart, options: options});
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
                break;
            case "4":
                if($("#site_id").val() == null){
                    //$("#site_id").closest("div").append('<p class="text-danger text-small">Select a site</p>');
                }else if($("#start").val() == '' || $("#end").val() == ''){
                    //$("#start").closest("div").append('<p class="text-danger text-small">Select a start and end date</p>');
                }else{
                    applyLoading(btn);


                }
                break;
            default:
                break;
        }
    });
</script>
@endsection