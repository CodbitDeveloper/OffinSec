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
                <h4 class="header-title mt-0 m-b-20">Patrol Details</h4>
                <div class="panel-body">
                    <hr />

                    <div class="text-left">
                        <p class="text-muted font-13"><strong>Officer Name :</strong> <span class="m-l-15">{{$patrol->patrol_officer}}</span></p>

                        <p class="text-muted font-13"><strong>Date:</strong><span class="m-l-15">{{$patrol->created_at}}</span></p>

                        <p class="text-muted font-13"><strong>Notes :</strong> <span class="m-l-15">{{$patrol->notes}}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-sm-12">
            <div class="card-box">
                <table class="table table-striped table-responsive">
                    <thead>
                        <th>Location Name</th>
                        <th>Time Scanned</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($patrol->scans as $scan)
                            <tr>
                                <td>{{$scan->name}}</td>
                                <td>{{$scan->pivot->created_at}}</td>
                                <td>{{$scan->pivot->longitude}}</td>
                                <td>{{$scan->pivot->latitude}}</td>
                                <td><a href="https://www.google.com/maps/search/?api=1&query={{$scan->pivot->latitude}},{{$scan->pivot->longitude}}" target="_blank">View on map</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
            ]
        });

    </script>
@endsection
