@extends('layouts.main-layout', ['page_title' => 'Patrols'])
@section('styles')
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <style>
        .btn-outline {
            background-color: transparent;
            border: 1px solid #666666;
            border-radius: 4px;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-3 col-sm-12">
            <div class="card-box">
                <h4 class="header-title mt-0 m-b-20">Manage Scannable Areas</h4>
                <div class="panel-body">
                    @foreach ($scannableAreas as $scannableArea)
                        <p>{{ $scannableArea->name }} @if (!is_null($scannableArea->latitude))
                                ({{ $scannableArea->latitude }},{{ $scannableArea->longitude }}) @endif <br /> <span
                                class="text-small text-muted">{{ $scannableArea->location_code }}</span></p>
                        <a class="badge badge-pill d-inline" href="javascript:void(0)"
                            onclick="print('{{ $scannableArea->location_code }}')">Print QR Code</a>
                        <a class="badge badge-pill d-inline" href="javascript:void(0)">Edit</a>
                        <form method="POST" action="" style="display: inline">
                            @method("DELETE")
                            @csrf
                            <input type="submit" value="Delete"
                                style="display: inline; color: red; border:none; background:none; font-size: 11px; font-weight:bold; cursor: pointer;"
                                value="Delete">
                        </form>
                        <hr />
                    @endforeach
                    @if ($scannableAreas->count() == 0)
                        <hr />
                    @endif
                    <button class="btn btn-custom" data-toggle="modal" data-target="#createScannableAreaModal">Add
                        New</button>
                        <button class="btn btn-custom" data-toggle="modal" data-target="#assignPatrolOfficer">Assign Patrol Officer</button>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12">
            <div class="card-box">
                <p>Patrol Officer : {{$site->patrol_supervisor ? $site->patrol_supervisor->firstname." ".$site->patrol_supervisor->lastname : "N/A"}}</p>
                <div class="card-header tab-card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                          <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Site Supervisors</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Patrol Officers</a>
                      </li>
                    </ul>
                  </div>
          
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <th>Patrol ID</th>
                                <th>Patrol Officer</th>
                                <th>Notes</th>
                                <th>Created At</th>
                                <th>Images</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($patrols as $patrol)
                                    <tr>
                                        <th>{{ $patrol->id }}</th>
                                        <th>{{ $patrol->patrol_officer }}</th>
                                        <th>{{ $patrol->notes }}</th>
                                        <th>{{ $patrol->created_at }}</th>
                                        <th>@if($patrol->images->count() > 0) <a href="{{$patrol->images[0]->url}}" target="_blank">View</a> @else N/A @endif</th>
                                        <th><a href="/patrol/{{ $patrol->id }}">View</a></th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>          
                    </div>
                    <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <th>Patrol ID</th>
                                <th>Patrol Officer</th>
                                <th>Notes</th>
                                <th>Created At</th>
                                <th>Images</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach ($supervisedPatrols as $patrol)
                                    <tr>
                                        <th>{{ $patrol->id }}</th>
                                        <th>{{ $patrol->patrol_officer }}</th>
                                        <th>{{ $patrol->notes }}</th>
                                        <th>{{ $patrol->created_at }}</th>
                                        <th>@if($patrol->images->count() > 0) <a href="{{$patrol->images[0]->url}}" target="_blank">View</a> @else N/A @endif</th>
                                        <th><a href="/patrol/{{ $patrol->id }}">View</a></th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>             
                    </div>
          
                  </div>
                
                
            </div>
        </div>
    </div>
@endsection
@section('modals')
    <div id="createScannableAreaModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Scannable Area</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form role="form" id="frm_add">
                        @csrf
                        <div class="form-row mb-2">
                            <div class="col-md-12 col-sm-12">
                                <label for="contact_number" class="col-form-label"><b>Name</b></label>
                                <input class="form-control resetable" type="text" id="name" placeholder="eg (Main Entrance)"
                                    name="name" required>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="contact_number" class="col-form-label"><b>Latitude</b></label>
                                <input class="form-control resetable" type="text" id="latitude" placeholder=""
                                    name="latitude">
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="contact_number" class="col-form-label"><b>Longitude</b></label>
                                <input class="form-control resetable" type="text" id="longitude" placeholder=""
                                    name="longitude">
                            </div>
                        </div>
                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-icon ml-1 waves-effect waves-light btn-custom">Add</button>
                            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="assignPatrolOfficer" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Assign Patrol Officer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form role="form" id="assign_supervisor" method="POST" action="/sites/{{$site->id}}/user">
                        @csrf
                        <div class="form-row mb-2">
                            <div class="col-md-12 col-sm-12">
                                <label for="contact_number" class="col-form-label"><b>Name</b></label>
                                <select class="custom-select" id="user_id" placeholder="eg (Main Entrance)"
                                    name="user_id" required>
                                    <option selected hidden disabled>Select a user</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->firstname." ".$user->lastname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-right mt-2">
                            <button type="submit" class="btn btn-icon ml-1 waves-effect waves-light btn-custom">Add</button>
                            <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="printQrModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content" style="background:none">
                <div class="modal-body">
                    <div id="qr_container"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap4.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

    <script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/vendor/kjua.min.js') }}" type="text/javascript"></script>
    <script>
        let selected_month = null;
        let temp_date = null;

        const table = $('.table').DataTable({
            "language": {
                "emptyTable": "No patrols for this site"
            },
            "bLengthChange": false,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    title: name
                },
                {
                    extend: 'pdfHtml5',
                    title: name
                }, 'copy', 'print'
            ],
            order: [[3, 'desc']],
        });

        $('#frm_add').on('submit', function(e) {
            e.preventDefault();
            var data = $(this).serialize();
            data += "&site_id={{ $site->id }}";

            var btn = $(this).find('[type="submit"]');
            var initial = btn.html();

            applyLoading(btn);

            $.ajax({
                url: '/api/scannable-area/add',
                method: 'POST',
                data: data,
                success: function(data) {
                    removeLoading(btn, initial);
                    if (data.error) {
                        $.toast({
                            text: data.message,
                            heading: 'Error',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            bgColor: '#d9534f'
                        });
                    } else {
                        $('#createScannableAreaModal').modal('hide');
                        $('#frm_add').trigger('reset');
                        $.toast({
                            text: data.message,
                            heading: 'Done',
                            position: 'top-right',
                            bgColor: '#5cb85c',
                            showHideTransition: 'slide'
                        });

                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    }
                },
                error: function(err) {
                    removeLoading(btn, initial);
                    $.toast({
                        text: 'Network error',
                        heading: 'Error',
                        position: 'top-right',
                        showHideTransition: 'slide',
                        bgColor: '#d9534f'
                    });
                }
            });
        });

        const print = (text) => {
            $("#qr_container").html("");
            $("#qr_container").kjua({
                text: text,
                size: 400
            });
            $("#printQrModal").modal("show");
        }
    </script>
@endsection
