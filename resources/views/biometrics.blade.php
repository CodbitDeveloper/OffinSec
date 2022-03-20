@extends('layouts.main-layout', ['page_title' => 'Update Guard Biometrics'])
@section('styles')
    <link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet"/>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">Add Biometric Data</h4>
                <form id="biometrics_form" method="POST" action="#">
                    <div class="form-group col-md-4 mb-4">
                        <label for="gender" class="col-form-label">Select Guard</label>
                        <select class="selectpicker show-tick required" data-style="btn-custom" title="Guards"
                            id="guard_id" name="guard_id" data-live-search="true" required>
                            @foreach($guards as $guard)
                            <option value="{{$guard->id}}">{{$guard->firstname." ".$guard->lastname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row mt-4">
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="camera" class="col-form-label"><b>Photo</b> ID</label>
                            <div id="camera" class=" mb-2"></div>
                            <button type="button" class="btn btn-primary" onclick="takePhoto()">Take
                                Photo</button>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="preview" class="col-form-label"><b>Preview</b></label>
                            <div class="sk-circle loader" style="display:none;">
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
                            <div id="preview">
                            </div>
                        </div>
                        <div class="form-group col-md-4 col-sm-12">
                            <label for="imgDiv" class="col-form-label"><b>Fingerprint</b>
                                Registration</label>
                            <br />
                            <span class="text-danger text-small"><b id="current_status"></b></span>
                            <div id="imgDiv">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAkCAYAAABIdFAMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHhJREFUeNo8zjsOxCAMBFB/KEAUFFR0Cbng3nQPw68ArZdAlOZppPFIBhH5EAB8b+Tlt9MYQ6i1BuqFaq1CKSVcxZ2Acs6406KUgpt5/LCKuVgz5BDCSb13ZO99ZOdcZGvt4mJjzMVKqcha68iIePB86GAiOv8CDADlIUQBs7MD3wAAAABJRU5ErkJggg=="
                                    id="imgPlaceholder" class="mb-2" />

                                <button id="enrol" onclick="EnrollTemplate()" type="button" class="btn btn-primary">Enroll
                                    fingerprint</button>
                                <p id="action">
                                    <b></b>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-custom">Save</button>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/webcam.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
    <script>
        //fingerprint scanner initialization
        var webSocket;
        var guardFingerprint;
        var comparingFingerprint;
        var fingers;
        var match = false;
        var image_uri = null;
        
        function connect(host) {
            $('#current_status').html("Connecting to the fingerprint server...");

            try {
                webSocket = new WebSocket(host);
                
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
            } catch (err) {
                $('#current_status').html(err);
            }
        }

        if ("WebSocket" in window) {
            connect("ws://127.0.0.1:21187/fps");
        } else {
            $('#current_status').html("Your browser does not support certain features required by the fingerprint scanner");
        }

        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#camera');

        function takePhoto() {
            $('#preview').html(null);
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

        $('#biometrics_form').on('submit', function(e){
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');

            applyLoading(btn);

            var formData = new FormData(this);
            formData.append('RTB64', guardFingerprint);

            if(image_uri != null){
                formData.append('image', $('#guard_image').attr('src'));
            }

            $.ajax({
                url : '/api/guard/update-bio',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                        removeLoading(btn, 'Save');
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

        });

        $('#guard_id').on('change', function(){
            $('#preview').html(null);
            image_uri = null;
            $('.loader').css('display', 'block');
            var guard = $(this).val();

            $.ajax({
                url : '/api/guards/get',
                method: 'get',
                data: 'guard_id='+guard,
                success: function(data){
                    $('.loader').css('display', 'none');
                    $('#preview').html('<img src="/storage/assets/images/guards/' + data.guard.photo + '" onerror="this.src = \'/assets/images/attachment.jpg\'" id="guard_image" style="width:320px; height:240px;"/>');
                },
                error: function(error){
                    $('.loader').css('display', 'none');
                }
            })
        });

    </script>
@endsection