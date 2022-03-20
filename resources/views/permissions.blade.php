@extends('layouts.main-layout', ['page_title' => 'Permissions'])

@section('styles')
<link href="{{asset('plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
<link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    @php
        $sorted_permissions = collect($permissions)->groupBy('approval');
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="header-title">Manage permissions</h4>

                <div class="text-center mt-4 mb-4">
                    <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <div class="card-box widget-flat border-custom bg-custom text-white">
                                <i class="fi-tag"></i>
                                <h3 class="m-b-10">{{$permissions->count()}}</h3>
                                <p class="text-uppercase m-b-5 font-13 font-600">Total permissions</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card-box bg-primary widget-flat border-primary text-white">
                                <i class="fi-archive"></i>
                                <h3 class="m-b-10">{{isset($sorted_permissions['2']) ? $sorted_permissions['2']->count() : 0}}</h3>
                                <p class="text-uppercase m-b-5 font-13 font-600">Pending permissions</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card-box widget-flat border-success bg-success text-white">
                                <i class="fi-help"></i>
                                <h3 class="m-b-10">{{isset($sorted_permissions['1']) ? $sorted_permissions['1']->count() : 0}}</h3>
                                <p class="text-uppercase m-b-5 font-13 font-600">Accepted permissions</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card-box bg-danger widget-flat border-danger text-white">
                                <i class="fi-delete"></i>
                                <h3 class="m-b-10">{{isset($sorted_permissions['0']) ? $sorted_permissions['0']->count() : 0}}</h3>
                                <p class="text-uppercase m-b-5 font-13 font-600">Declined permissions</p>
                            </div>
                        </div>
                    </div>
                </div>


                <table class="table table-responsive table-hover m-0 permissions-list table-actions-bar dt-responsive nowrap" cellspacing="0" width="100%" id="datatable">
                    <thead>
                    <tr>
                        <th>
                            ID
                        </th>
                        <th>Requested By</th>
                        <th>Reason</th>
                        <th>Reliever</th>
                        <th>Status</th>
                        <th>Requested Date</th>
                        <th>Created At</th>
                        <th class="hidden-sm">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($permissions as $permission)
                    @php
                    $permisson_date = Carbon\Carbon::parse($permission->date)
                    @endphp
                    <tr>
                        <td><b>#{{$permission->id}}</b></td>
                        <td>
                            @if($permission->owner_guard != null)
                            <a href="javascript: void(0);">
                                <img src="{{$permission->owner_guard->photo == '' || $permission->owner_guard->photo == null ? asset('assets/images/avatar.jpg') : asset('storage/assets/images/guards/'.$permission->owner_guard->photo)}}" onerror="this.src={{asset('assets/images/avatar.jpg')}}" class="rounded-circle thumb-sm" alt="friend" />
                                <span class="ml-2">{{ucwords($permission->owner_guard->firstname.' '.$permission->owner_guard->lastname)}}</span>
                            </a>
                            @else
                                <span class="text-small text-muted">N/A</span>
                            @endif
                        </td>

                        <td>
                            {{$permission->reason}}
                        </td>

                        
                        <td id="reliever-cell">
                            @if($permission->relieving_guard != null)
                            <a href="javascript: void(0);">
                            <img src="{{$permission->relieving_guard->photo == '' || $permission->relieving_guard->photo == null ? asset('assets/images/avatar.jpg') : asset('storage/assets/images/guards/'.$permission->relieving_guard->photo)}}" onerror="this.src={{asset('assets/images/avatar.jpg')}}" class="rounded-circle thumb-sm" alt="friend" />
                                <span class="ml-2">{{ucwords($permission->relieving_guard->firstname.' '.$permission->relieving_guard->lastname)}}</span>
                            </a>
                            @else
                                <span class="text-small text-muted">No reliever assigned</span>
                            @endif
                        </td>

                        <td id="status-badge">
                            @if($permission->approval == 2)
                                <span class="badge badge-primary">Pending</span>
                            @elseif($permission->approval == 1)
                                <span class="badge badge-success">Accepted</span>
                            @elseif($permission->approval == 0)
                                <span class="badge badge-danger">Declined</span>
                            @endif
                        </td>
                        <td>
                            {{$permisson_date->format('Y-m-d')}}
                        </td>

                        <td>
                            {{Carbon\Carbon::parse($permission->created_at)->diffForHumans()}}
                        </td>

                        <td>
                            <div class="btn-group dropdown">
                                <a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm {{$permisson_date->isPast() ? 'disabled' : ''}}" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript: void(0);" onclick="approval({{$permission->id}}, this)"><i class="mdi mdi-check-all mr-2 text-muted font-18 vertical-middle"></i>Approve</a>
                                    <a class="dropdown-item" href="javascript: void(0);" onclick="changeStatus(0, {{$permission->id}}, this)"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Reject</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('modals')
<div id="approvePermission" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select a reliever for this guard</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
            <form id="permission-approval"> 
            @csrf 
                <label for="reliever" class="col-form-label">Reliever</label>
                <select class="selectpicker show-tick form-control" data-style="btn-primary" 
                title="Reliever" id="reliever" name="reliever" data-live-search="true" required>
                    @foreach($guards as $guard)
                    <option value="{{$guard->id}}" data-subtext="{{$guard->phone_number}}">{{$guard->firstname.' '.$guard->lastname}}</option>
                    @endforeach
                </select>
                <input type="hidden" id="approve-guard" name="permission_id"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="btn-approve">Approve</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>

<!--Bootstrap Select-->
<script src="{{asset('plugins/bootstrap-select/js/bootstrap-select.js')}}" type="text/javascript"></script>

<script>
    table = $('table').DataTable();

    function changeStatus(status, permission, element){
        el = $(element);
        status_badge = el.closest('tr').find('#status-badge');

        temp = el.html();
        status_badge_temp = status_badge.html();

        status_badge.html('<span class="badge badge-light">Loading</span>');
        el.prop('disabled', true);

        $.ajax({
            url: '/api/permission/approval',
            method: 'post',
            data: 'permission_id='+permission+'&approval='+status,
            success: function(data){
                el.prop('disabled', false);
                el.html(temp);

                if(status == 1){
                   table.cell(status_badge).data('<span class="badge badge-success">Accepted</span>').draw();
                }else if(status == 0){
                   table.cell(status_badge).data('<span class="badge badge-danger">Declined</span>').draw();
                }
            },
            error: function(err){
                el.prop('disabled', false);
                status_badge.html(status_badge_temp);
            }
        });

    }

    var el;

    function approval(permission, element) {
        $('#approve-guard').val(permission);

        el = $(element);

        $('#approvePermission').modal('show');
    }

    $('#permission-approval').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serialize();
        var btn = $(this).find('[type="submit"]');
        var initial = btn.html();

         $.ajax({
            url: '/api/permission/approve-permission',
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

                        status_badge = el.closest('tr').find('#status-badge');
                        reliever_cell = el.closest('tr').find('#reliever-cell');

                        table.cell(status_badge).data('<span class="badge badge-success">Accepted</span>').draw();
                        table.cell(reliever_cell).data('<a href="javascript: void(0);"><img src="/storage/assets/images/guards/'+data.guard.photo +'" onerror="this.src=\'assets/images/avatar.jpg\'" class="rounded-circle thumb-sm" alt="friend"/><span class="ml-2">'+data.guard.firstname+' '+data.guard.lastname+'</span></a>').draw();
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
    });
</script>
@endsection