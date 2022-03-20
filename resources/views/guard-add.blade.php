@extends('layouts.main-layout', ['page_title' => 'Add Guard'])
@section('styles')
    <link href="{{asset('plugins/custombox/css/custombox.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/jquery.steps/css/jquery.steps.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/spinkit/spinkit.css')}}" rel="stylesheet" />
    <style>
        #myOnlineCamera video{width:320px;height:240px;margin:15px;float:left;}
        #myOnlineCamera canvas{width:320px;height:240px;margin:15px;float:left;}
        #myOnlineCamera button{clear:both;margin:30px;}
                
        #imgPlaceholder {
            height: 240px;
            width: 320px;
        }   

        .text-small{
            font-size: 12px;
        }

        .text-red{
            color: red;
        }
    </style>
@endsection
@section('content')
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

            <div class="row notLoader" style="display:none;">
                <div class="col-md-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Add Guard Wizard</h4>
                        <p class="text-muted m-b-30 font-13">
                            Create a new guard
                        </p>

                        <div class="pull-in">
                            <form id="basic-form" action="#" method="post">
                                <div>
                                    <h3>Personal Information</h3>
                                    <section>
                                        <div id="personal_information">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="firstname" class="col-form-label"><b>First</b> Name</label>
                                                    <input type="text" class="form-control required" id="firstname" name="firstname">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="lastname" class="col-form-label"><b>Last</b> Name</label>
                                                    <input type="text" class="form-control required" id="lastname" name="lastname">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="dob" class="col-form-label"><b>Date of</b> Birth</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control required" id="dob" autocomplete="false" name="dob">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="phone_number" class="col-form-label"><b>Phone</b></label>
                                                    <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control required" name="phone_number">
                                                    <span class="font-10 text-muted">(233) 244-500500</span>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="gender" class="col-form-label"><b>&nbsp;</b></label>
                                                    <select class="selectpicker show-tick required" data-style="btn-custom" title="Gender"
                                                        id="gender" name="gender">
                                                        <option>Male</option>
                                                        <option>Female</option>
                                                        <option>Transgender</option>
                                                        <option>Rather not say</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row mb-4">
                                                <div class="form-group col-md-4">
                                                    <label for="phone_number" class="col-form-label"><b>Role</b></label>
                                                    <select class="selectpicker show-tick required" data-style="btn-custom" title="Role"
                                                        id="occupation" name="occupation">
                                                        @foreach($roles as $role)
                                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="marital" class="col-form-label"><b>&nbsp;</b></label>
                                                    <select class="selectpicker show-tick required" data-style="btn-custom" title="Marital Status"
                                                        id="marital" name="marital_status">
                                                        <option>Single</option>
                                                        <option>Married</option>
                                                        <option>Divorced</option>
                                                        <option>Widowed</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="residential" class="col-form-label"><b>Residential</b>
                                                        Address</label>
                                                    <input type="text" class="form-control required" id="residential" name="address">
                                                </div>
                                            </div>
                                            
                                            <div class="form-row" style="margin-top:120px;"></div>
                                        </div>
                                    </section>
                                    <h3>ID and Biometrics</h3>
                                    <section>
                                        <div id="identification">
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="id_type" class="col-form-label"><b>ID </b>Type</label>
                                                    <select class="selectpicker show-tick required" data-style="btn-custom" title="ID Type"
                                                        id="type" name="id_type">
                                                        <option>Voter's ID</option>
                                                        <option>Ghana Card</option>
                                                        <option>NHIS</option>
                                                        <option>Passport</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="national_id" class="col-form-label"><b>ID </b>Number</label>
                                                    <input type="text" class="form-control required" id="national_id" name="national_id">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="ssnit" class="col-form-label"><b>SSNIT </b>Number</label>
                                                    <input type="text" class="form-control required" id="ssnit" minlength="13"
                                                        maxlength="13" name="SSNIT"/>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4 col-sm-12">
                                                    <label for="camera" class="col-form-label"><b>Photo</b> ID</label>
                                                    <div id="camera" class=" mb-2"></div>
                                                    <button type="button" class="btn btn-custom" onclick="takePhoto()">Take
                                                        Photo</button>
                                                </div>
                                                <div class="form-group col-md-4 col-sm-12">
                                                    <label for="preview" class="col-form-label"><b>Preview</b></label>
                                                    <div id="preview"></div>
                                                </div>
                                                <div class="form-group col-md-4 col-sm-12">
                                                    <label for="imgDiv" class="col-form-label"><b>Fingerprint</b>
                                                        Registration</label>
                                                    <br />
                                                    <span class="text-danger text-small"><b id="current_status"></b></span>
                                                    <div id="imgDiv">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAkCAYAAABIdFAMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHhJREFUeNo8zjsOxCAMBFB/KEAUFFR0Cbng3nQPw68ArZdAlOZppPFIBhH5EAB8b+Tlt9MYQ6i1BuqFaq1CKSVcxZ2Acs6406KUgpt5/LCKuVgz5BDCSb13ZO99ZOdcZGvt4mJjzMVKqcha68iIePB86GAiOv8CDADlIUQBs7MD3wAAAABJRU5ErkJggg=="
                                                            id="imgPlaceholder" class="mb-2" />

                                                        <button id="enrol" onclick="EnrollTemplate()" type="button" class="btn btn-custom">Enroll
                                                            fingerprint</button>
                                                        <p id="action">
                                                            <b></b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <h3>Guarantors</h3>
                                    <section>
                                        <div id="guarantors">
                                            <div class="row clearfix">
                                                <div class="col-sm-12 col-md-4" id="add_guarantor_div">
                                                    <p class="mb-2">
                                                        Add guarantors for this guard. They will appear in the table when
                                                        added.
                                                        Click on the button below to add a guarantor
                                                    </p>
                                                    <button href="#custom-modal" class="btn btn-block btn-custom waves-effect w-md mr-2 mb-2"
                                                        data-animation="contentscale" data-plugin="custommodal"
                                                        data-overlaySpeed="100" data-overlayColor="#36404a" id="btn_add_guarantor">Add Guarantor</button>
                                                </div>
                                                <div class="col-sm-12 col-md-8">
                                                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>DOB</th>
                                                                <th>Gender</th>
                                                                <th>Occupation</th>
                                                                <th>Phone</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>


                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <h3>Finish</h3>
                                    <section id="finish">
                                        <div class="form-group">
                                            <label>Emergency Contact</label>
                                            <input type="tel" name="emergency_contact"  placeholder="" data-mask="(999) 999-999999" class="form-control"/>
                                            <span class="font-10 text-muted">(233) 244-500500</span>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="bank_name" class="col-form-label"><b>Bank</b> Name</label>
                                                <input type="text" class="form-control required" id="bank_name" name="bank_name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="bank_branch" class="col-form-label"><b>Bank</b> Branch</label>
                                                <input type="text" class="form-control required" id="bank_branch" name="bank_branch">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="account_number" class="col-form-label"><b>Account</b> Number</label>
                                                <input type="text" class="form-control required" id="account_number" name="account_number">
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="col-lg-6 col-sm-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-g" type="checkbox" name="welfare">
                                                    <label for="checkbox-g">
                                                        This guard wants to be part of welfare
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-h" type="checkbox" name="tos">
                                                    <label for="checkbox-h">
                                                        This guard has agreed to our terms and services
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
@endsection
@section('modals')
    <div id="custom-modal" class="modal-demo">
        <button type="button" class="close" onclick="Custombox.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <h4 class="custom-modal-title">New Guarantor</h4>
        <div class="custom-modal-text">
            <form class="form-horizontal" action="#" id="new_guarantor">

                <div class="form-row m-b-25">
                    <div class="col-md-6 col-sm-12">
                        <label for="garuantor_firstname">First Name</label>
                        <input class="form-control required" type="text" id="garuantor_firstname" placeholder="Kwame" name="firstname"/>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="garuantor_lastname">Last Name</label>
                        <input class="form-control" type="text" id="garuantor_lastname" placeholder="Attah" name="lastname">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="garuantor_dob" class="col-form-label"><b>Date of</b> Birth</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="guarantor_dob" name="dob" autocomplete="false">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="garuantor_phone_number" class="col-form-label"><b>Phone</b></label>
                        <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control" name="phone">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="garuantor_gender" class="col-form-label"><b>&nbsp;</b></label>
                        <select class="selectpicker show-tick" data-style="btn-custom" title="Gender" id="gender" name="gender">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Transgender</option>
                            <option>Rather not say</option>
                        </select>
                    </div>
                </div>
                <div class="form-row mb-4">
                    <div class="col-md-4 col-sm-12">
                        <label for="garuantor_occupation">Occupation</label>
                        <input class="form-control" type="text" id="garuantor_occupation" placeholder="Seamstress" name="occupation">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="garuantor_residential">Residential address</label>
                        <input class="form-control" type="text" id="garuantor_residential" placeholder="21 Ledzekuku St."
                            name="address">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="garuantor_residential">National ID</label>
                        <input class="form-control" type="text" id="garuantor_national" placeholder="C019382190931"
                            name="national_id">
                    </div>
                </div>
                <div class="form-group account-btn text-center m-t-10">
                    <div class="col-12">
                        <button class="btn w-lg btn-custom waves-effect waves-light" type="submit">Add
                            Guarantor</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('plugins/jquery.steps/js/jquery.steps.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-inputmask/bootstrap-inputmask.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/js/webcam.min.js')}}"></script>
<script src="{{asset('plugins/custombox/js/custombox.min.js')}}"></script>
<script src="{{asset('plugins/custombox/js/legacy.min.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <script>

        //fingerprint scanner initialization
        var webSocket;
        var guardFingerprint;
        var comparingFingerprint;
        var fingers;
        var match = false;
        var guarantors = [];
        var image_uri;

        //wizard initialization
        !function ($) {
            "use strict";

            var FormWizard = function () { };

            FormWizard.prototype.createBasic = function ($form_container) {
                $form_container.children("div").steps({
                    headerTag: "h3",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    
                    onStepChanging: function (event, currentIndex, newIndex) {
                        //NOTE: Here you can do form validation and return true or false based on your validation logic
                        $('.text-danger').css('display', 'none');
                        var error = false;
                        switch(currentIndex){
                            case 0:
                                $('#personal_information').find('input, select').each(function(){
                                    if($(this).val() == null || $(this).val() == ''){
                                        $(this).closest('.form-group').append('<span class="text-danger text-small">This field is required</span>')
                                        error = true;
                                    }
                                });
                                
                                break;

                            case 1:
                                $('#personal_information').find('input, select').each(function(){
                                    if($(this).val() == null || $(this).val() == ''){
                                        $(this).closest('.form-group').append('<span class="text-danger text-small">This field is required</span>')
                                        error = true;
                                    }
                                });

                                /*
                                if(guardFingerprint == null || guardFingerprint == ''){
                                    error = true;
                                    $('#imageDiv').append('<span class="text-danger text-small">No fingerprint has been recorded</span>');
                                }

                                if($('#preview').html() == '' || $('#preview').html() == null){
                                    error = true;
                                    $('#preview').append('<span class="text-danger text-small">Take an image</span>');
                                }*/

                                break;
                            
                            case 2:
                                if(guarantors.length < 1){
                                    error = true;
                                    $('#add_guarantor_div').append('<span class="text-danger text-small">At least one guarantor is required</span>')
                                }

                                break;
                        }

                        return !error;
                    },

                    onFinished: function (event, currentIndex) {
                        //NOTE: Submit the form, if all validation passed.
                        
                        $("#basic-form").submit();

                    }
                });
                return $form_container;
            },
                //creates vertical form
                FormWizard.prototype.createVertical = function ($form_container) {
                    $form_container.steps({
                        headerTag: "h3",
                        bodyTag: "section",
                        transitionEffect: "fade",
                        stepsOrientation: "vertical"
                    });
                    return $form_container;
                },
                FormWizard.prototype.init = function () {
                    //initialzing various forms

                    //basic form
                    this.createBasic($("#basic-form"));

                    //vertical form
                    this.createVertical($("#wizard-vertical"));
                },
                //init
                $.FormWizard = new FormWizard, $.FormWizard.Constructor = FormWizard
        }(window.jQuery),

            //initializing 
            function ($) {
                "use strict";
                $.FormWizard.init()
            }(window.jQuery);

        if ("WebSocket" in window) {
            connect("ws://127.0.0.1:21187/fps");
        } else {
            $('#current_status').html("Your browser does not support certain features required by the fingerprint scanner");
        }


        //send fingerprint data to the websocket
        function sendToWebSocket(str) {
            try {
                webSocket.send(str)
            } catch (err) {
                $('#current_status').html("Problem capturing fingerprint data: " + err);
            }
        }

        //connecting to websocket
        function connect(host) {
            $('#current_status').html("Connecting to the fingerprint server...");

            try {
                webSocket = new WebSocket(host);
            } catch (err) {
                $('#current_status').html(err);
            }

            webSocket.onopen = function () {
                $('#current_status').html('');
                $('#current_status').css('display', 'none');
            }

            webSocket.onmessage = function (evt) {
                var obj = eval("(" + evt.data + ")");
                var status = document.getElementById("action");
                var scanResult = document.getElementById("scanResult");
                switch (obj.workmsg) {
                    case 1:
                        status.innerHTML = "Enable the device and refresh the page";
                        break;
                    case 2:
                        status.innerHTML = "Place Finger";
                        break;
                    case 3:
                        status.innerHTML = "Lift Finger";
                        break;
                    case 4:
                        status.innerHTML = "Please";
                        break;
                    case 5:
                        if (obj.retmsg == 1) {
                            status.value = "Fingerprint retrieved";
                            //data1 returns the base64 print of the image
                            if (obj.data1 != "null") {
                                comparingFingerprint = obj.data1;
                            }

                            //data2 returns ISO template of the print. will be saving base64;
                            /*if (obj.data2 != "null") {
                                scanResult.innerHTML += '<p>Result2: ' + obj.data2 + '</p>';
                            }*/
                        } else {
                            status.value = "Could not get the fingerprint. Try again";
                        }
                        break;
                    case 6:
                        if (obj.retmsg == 1) {
                            status.innerHTML = "Fingerprint retrieved";
                            if (obj.data1 != "null") {
                                $('#template').val(obj.data1);
                                guardFingerprint = obj.data1;
                            }
                            /*
                            if (obj.data2 != "null") {
                                scanResult.innerHTML += '<p>Result2: ' + obj.data2 + '</p>';
                            }*/
                        } else {
                            status.value = "There was a problem retrieving the fingerprint. Try again";
                        }
                        break;
                    case 7:
                        if (obj.image != "null") {
                            var img = document.getElementById("imgPlaceholder");
                            img.src = "data:image/png;base64," + obj.image;
                            //var en3 = document.getElementById("e3");
                            //en3.value = obj.image;
                        }
                        break;
                    case 8:
                        status.innerHTML = "There was a problem retrieving the fingerprint. Try again";
                        break;
                    case 9:
                        //status.innerHTML = "This is how accurate of a liar you're not: " + obj.retmsg;
                        //this is for testing purposes. This was to compare fingerprints
                        if (obj.retmsg >= 60) {
                            match = true;
                            console.log("match found");
                        }
                        console.log(obj.retmsg);
                        break;
                    case 0x10:
                        /*status.value = "Card SN:" + obj.data1;*/
                        break;
                }
            };

            webSocket.onclose = function () {
                $('#current_status').html("Connection to the fingerprint server was closed!");
            };
        }

        
        //datepicker initialise
        $('#dob').datepicker({
            autoclose: true,
            todayHighlight: true
        });

        $('#guarantor_dob').datepicker({
            autoclose: true,
            todayHighlight: true
        });

        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#camera');

        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();

        function takePhoto() {
            Webcam.snap(function (data_uri) {
                // display results in page --> to do. Figure out how to send it via ajax
                $('#preview').html('<img src="' + data_uri + '" id="guard_image"/>');
                image_uri = data_uri;
            });
        }

        function EnrollTemplate() {
            try {
                //ws.send("enrol");
                var command = "{\"cmd\":\"enrol\",\"data1\":\"\",\"data2\":\"\"}";
                webSocket.send(command);
            } catch (err) {
                $('#current_status').html(err);
            }

            $('#action').html("Place Finger");
        }

        var table = $('#datatable').DataTable();

        $('#new_guarantor').on('submit', function (e) {
            e.preventDefault();
            let temp = {};

            temp.firstname = $(this).find('[name="firstname"]').val();
            temp.lastname = $(this).find('[name="lastname"]').val();
            temp.phone_number = $(this).find('[name="phone"]').val();
            temp.gender = $(this).find('[name="gender"]').val();
            temp.dob = $(this).find('[name="dob"]').val();
            temp.national_id = $(this).find('[name="national_id"]').val();
            temp.address = $(this).find('[name="address"]').val();
            temp.occupation = $(this).find('[name="occupation"]').val();

            guarantors.push(temp);

            table.row.add([
                temp.firstname + ' ' + temp.lastname,
                temp.dob,
                temp.gender,
                temp.occupation,
                temp.phone_number,
                '<a href="#" class="text-primary">Edit</a>   <a href="#" class="text-red">Remove </a>'
            ]).draw();

            $(this).trigger('reset');
            Custombox.close();

            if(guarantors.length == 2){
                $('#btn_add_guarantor').prop('disabled', true);
            }
        });

        $('#basic-form').on('submit', function(e){
            e.preventDefault();

            var result = $('ul[aria-label=Pagination]').children().find('a');
            var btn;


            $(result).each(function ()  { 
                if ($(this).text() == 'Finish') {
                    btn = $(this)
                }else{
                    $(this).css('display', 'none');
                }
            });

            applyLoading(btn);

            var formData = new FormData(this);

            formData.append('guarantors', JSON.stringify(guarantors));
            formData.append('RTB64', guardFingerprint);
            formData.append('image', $('#guard_image').attr('src'));

            $.ajax({
                url : '/api/guard/add',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                        removeLoading(btn, 'Finish');
                        if(data.error){
                            removeLoading(btn, 'Add Client');
                            $(result).each(function ()  {
                                $(this).css('display', 'block');
                            });

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

                            setTimeout(function(){
                                location.replace('/guard/'+data.data.id);
                            }, 500);
                        }
                    },
                    error: function(err){
                        removeLoading(btn, 'Finish');
                        $(result).each(function ()  {
                            $(this).css('display', 'block');
                        });
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

        $(document).ready(function(){
                $('.loader').css('display', 'none');
                $('.notLoader').css('display', 'block');
            }
        );
    </script>
@endsection