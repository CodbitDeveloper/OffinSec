@extends('layouts.main-layout', ['page_title' => 'Call Checks'])
@section('styles')
<link href="{{ asset('plugins/custombox/css/custombox.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row incidents">
    <div class="row mb-4">
        <div class="col-sm-4">
            <button href="#custom-modal" class="btn btn-custom waves-effect w-md mr-2 mb-2" data-animation="contentscale"
                data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a">
                Reord call check</button>
        </div>
    </div>
    <div class="col-12">
        <div class="">
            <table class="table table-striped table-hover table-borderd">
                <thead>
                    <th>Site</th>
                    <th>User</th>
                    <th>Report</th>
                    <th>Date</th>
                </thead>
                <tbody>
                    @foreach($callChecks as $callCheck)
                    <tr>
                        <td>{{$callCheck->site->name}}</td>
                        <td>{{$callCheck->user->firstname.' '.$callCheck->user->lastname}}</td>
                        <td>{{$callCheck->report}}</td>
                        <td>{{$callCheck->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>
    </div>
</div>

<div id="custom-modal" class="modal-demo">
    <button type="button" class="close" onclick="Custombox.close();">
        <span>&times;</span><span class="sr-only">Close</span>
    </button>
    <h4 class="custom-modal-title">Record Call Check</h4>
    <div class="custom-modal-text">
        <form class="form-horizontal" method="POST" action="/call-checks" id="new_user">
            @csrf
            <div class="form-row mb-4">
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                <div class="col-md-8 col-sm-12">
                    <label for="email">Report</label>
                    <input class="form-control resetable" type="text" id="lastname" placeholder=""
                        name="report">
                </div>
                <div class="form-group col-md-4 col-sm-12">
                    <label for="role" class="col-form-label">Site</label>
                    <select class="selectpicker show-tick form-control" data-style="btn-primary" data-live-search="true" title="Site" id="role"
                        name="site_id">
                        @foreach($sites as $site)
                        <option value="{{$site->id}}">{{$site->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group account-btn text-center m-t-10">
                <div class="col-12">
                    <button class="btn w-lg btn-custom waves-effect waves-light" type="submit">Add
                        User</button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap4.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<script src="{{ asset('plugins/custombox/js/custombox.min.js') }}"></script>
<script src="{{ asset('plugins/custombox/js/legacy.min.js') }}"></script>
<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}" type="text/javascript"></script>
<script>
    var name = " Call Check " + '<?php echo date('Y-m-d'); ?>';
    var table = $('.table').DataTable({
        "bLengthChange": false,
        dom: 'Blfrtip',
        order: [[3, 'desc']],
        buttons: [ 
            {
                extend: 'excelHtml5',
                title: name
            },
            {
                extend: 'pdfHtml5',
                title: name
            }, 'copy', 'print' ]
    });
</script>
@endsection