@extends('layouts.main-layout', ['page_title' => 'Clients'])

@section('styles')
    <link href="{{asset('plugins/custombox/css/custombox.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet"/>
    <style>
        .text-small{
            font-size: 12px;
        }
    </style>
@endsection

@section('content')
            <div class="row mb-4">
                <div class="col-sm-4">
                    <button href="#custom-modal" class="btn btn-custom waves-effect w-md mr-2 mb-2" data-animation="contentscale"
                        data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a">
                        New Client</button>
                </div>
                <div class="col-sm-8">
                    <div class="project-sort float-right">
                        <div class="project-sort-item">
                            <form class="form-inline">
                                <div class="form-group">
                                    <label for="phase-select">Phase :</label>
                                    <select class="form-control ml-2 form-control-sm" id="phase-select">
                                        <option>All Clients (6)</option>
                                        <option>Ongoing</option>
                                        <option>Completed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sort-select">Sort :</label>
                                    <select class="form-control ml-2 form-control-sm" id="sort-select">
                                        <option>Date</option>
                                        <option>Name</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end col-->
            </div>
            <!-- end row -->


            <div class="row">
                @if($clients->count() < 1)
                    <div class="jumbotron p-4">
                        <h1 class="display-4">No clients yet :( </h1>
                        <p class="lead">You haven't registered any clients yet. Add a new client to view and manage.</p>
                        <hr class="my-4">
                        <p>Simply use the "Add New" button to begin registering a new client.</p>
                    </div>
                @endif
                @foreach($clients as $client)
                <div class="col-xl-4">
                    <div class="card-box project-box">
                        <div class="dropdown float-right">
                            <a href="#" class="dropdown-toggle card-drop arrow-none" data-toggle="dropdown"
                                aria-expanded="false">
                                <h3 class="m-0 text-muted"><i class="mdi mdi-dots-horizontal"></i></h3>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="edit({{$client}})">Edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="deleteClient('{{$client->id}}')">Delete</a>
                                <a class="dropdown-item" href="/send-report?client={{$client->id}}">Send Report</a>
                            </div>
                        </div>
                        <p class="text-muted text-uppercase mb-0 font-13">{{ $client->email }}</p>
                        <h4 class="mt-0 mb-3"><a href="/client/{{$client->id}}" class="text-dark">{{ $client->name }}</a></h4>
                        <p class="text-muted font-13" style="min-height: 60px;">{{ $client->description }}
                        </p>

                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <h3 class="mb-0">{{$client->guards->count()}}</h3>
                                <p class="text-muted">Guards</p>
                            </li>
                            <li class="list-inline-item">
                                <h3 class="mb-0">{{$client->sites->count()}}</h3>
                                <p class="text-muted">Sites guarded</p>
                            </li>
                        </ul>

                        <div class="project-members mb-4">
                            <label class="mr-3">Guards :</label>
                            <?php $count = 1; ?>
                            @foreach($client->guards as $guard)
                                @if($count > 5)
                                    @break;
                                @endif
                                <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{$guard->firstname.' '.$guard->lastname}}">
                                    <img src="{{$guard->photo == '' || $guard->photo == null ? asset('assets/images/avatar.jpg') : asset('assets/images/guards/'.$guard->photo)}}" onerror="this.src={{asset('assets/images/avatar.jpg')}}" class="rounded-circle thumb-sm" alt="friend" />
                                </a> 

                                <?php $count++; ?>    
                            @endforeach
                            @if($client->guards->count() > 5)
                            <span href="#" class="text-muted">and {{$client->guards->count() - 5}} other
                                @if($client->guards->count() > 6)<span>s</span>@endif
                            </span>
                            @endif
                            @if($client->guards->count() < 1)
                            <span href="#" class="text-muted">
                                No guards have been assigned to this client yet. Add a site to assign guards.
                            </span>
                            @endif
                        </div>
                        <?php 
                            $date = Carbon\Carbon::parse($client->start_date);
                            $complete = Carbon\Carbon::parse($client->end_date);
                            $isPast = $date->isPast();
                            if($isPast && $complete->diffInDays($data) > 0){
                                $percentage = ($date->diffInDays(Carbon\Carbon::now())/$complete->diffInDays($date))*100;
                            }else{
                                $percentage = 0;
                            }
                            
                        ?>
                        <label class="">Completion : <span class="text-custom">{{$isPast ? $date->diffInDays(Carbon\Carbon::now()) : 0}}/{{$complete->diffInDays($date)}}</span></label>
                        <div class="progress mb-1" style="height: 7px;">
                            <div class="progress-bar" role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0"
                                aria-valuemax="100" style="width: {{$percentage}}%;">
                            </div><!-- /.progress-bar .progress-bar-danger -->
                        </div><!-- /.progress .no-rounded -->

                    </div>
                </div><!-- end col-->
                @endforeach
            </div>
            
<div id="custom-modal" class="modal-demo">
    <button type="button" class="close" onclick="Custombox.close();">
        <span>&times;</span><span class="sr-only">Close</span>
    </button>
    <h4 class="custom-modal-title">New Client</h4>
    <div class="custom-modal-text">
        <form class="form-horizontal" action="#" id="new_client">
            @csrf
            <div class="form-row mb-4">
                <div class="col-md-4 col-sm-12">
                    <label for="name">Client Name</label>
                    <input class="form-control resetable" type="text" id="name" placeholder="Codbit Ghana Ltd" name="name">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label for="email">Email address</label>
                    <input class="form-control resetable" type="text" id="email" placeholder="info@codbitgh.com" name="email">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label for="email">Phone</label>
                    <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control resetable" name="phone_number">
                </div>
            </div>

            <div class="form-row mb-4">
                <div class="col-md-4 col-sm-12">
                    <label for="contact_person">Contact Person Name</label>
                    <input class="form-control resetable" type="text" id="email" placeholder="Abraham Attah" name="contact_person_name">
                </div>
                <div class="form-group col-md-8">
                    <label>Contract Duration</label>
                    <div>
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" name="start" placeholder="Start" />
                            <input type="text" class="form-control" name="end" placeholder="End" />
                        </div>
                        <span class="text-danger text-small" id="date_error"></span>
                    </div>
                </div>
            </div>
            <div class="form-row mb-4">
                <div class="col-md-4 col-sm-12">
                    <label for="no_of_guards">No. of Guards</label>
                    <input class="form-control resetable" type="number" min="1" id="no_of_guards" name="number_of_guards">
                </div>
                <div class="col-md-8 col-sm-12">
                    <label for="description">Job Description</label>
                    <input class="form-control resetable" type="text" id="description" name="description">
                </div>
            </div>
            <div class="form-group account-btn text-center m-t-10">
                <div class="col-12">
                    <button class="btn w-lg btn-custom waves-effect waves-light" type="submit">Add
                        Client</button>
                </div>
            </div>

        </form>
    </div>
</div>
            <!-- end row -->
@endsection
@section('modals')
<div class="modal fade" id="edit-client">
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
                        <input class="form-control resetable" type="text" id="name" placeholder="Codbit Ghana Ltd" name="name">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="email">Email address</label>
                        <input class="form-control resetable" type="text" id="email" placeholder="info@codbitgh.com" name="email">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="email">Phone</label>
                        <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control resetable" name="phone_number">
                    </div>
                    <input type="hidden" name="id"/>
                </div>

                <div class="form-row mb-4">
                    <div class="col-md-4 col-sm-12">
                        <label for="contact_person">Contact Person Name</label>
                        <input class="form-control resetable" type="text" id="email" placeholder="Abraham Attah" name="contact_person_name">
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <label for="no_of_guards">No. of Guards</label>
                        <input class="form-control resetable" type="number" min="1" id="no_of_guards" name="number_of_guards">
                    </div>
                </div>
                <div class="form-row mb-4">
                    <div class="col-md-12 col-sm-12">
                        <label for="description">Job Description</label>
                        <input class="form-control resetable" type="text" id="description" name="description">
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
<div id="deleteClientModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure?</h4>	
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete this client? This process cannot be undone.</p>
                <input type="hidden" id="delete-client-id"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="btn-delete-client">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

    
    <!--Animations-->
    <script src="{{asset('plugins/custombox/js/custombox.min.js')}}"></script>
    <script src="{{asset('plugins/custombox/js/legacy.min.js')}}"></script>


    <!--Telephone Mask-->
    <script src="{{asset('/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js')}}" type="text/javascript"></script>
    
    <!--Bootstrap Datepicker-->
    <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    
    <!--JQUERY toast-->

    <script>
        jQuery('#date-range, #date-range-2').datepicker({
            toggleActive: true
        });


        $('#new_client').on('submit', function(e){
            e.preventDefault();
            var error = false;
            
            $(this).find('.resetable').each(function(){
                if($(this).val()=='' || $(this).val()==null){
                    error = true;
                    $(this).closest('div').append('<span class="text-danger text-small">This field is required</span>')
                }
            });

            $('.input-group').find('input').each(function(){
                if($(this).val()=='' || $(this).val() == null){
                    error = true;
                    $('#date_error').html('All date fields are required');
                } 
            })

            if(!error){
                $('.text-danger').css('display', 'none');

                data = $(this).serialize();
                btn = $(this).find('[type="submit"]');

                applyLoading(btn);

                $.ajax({
                    url: 'api/client/add',
                    method: 'POST',
                    data: data,
                    success: function(data){
                        removeLoading(btn, 'Add Client');
                            if(data.error){
                                removeLoading(btn, 'Add Client');
                                Custombox.close();

                                $.toast({
                                    text : data.message,
                                    heading : 'Error',
                                    position: 'top-right',
                                    showHideTransition : 'slide', 
                                    bgColor: '#d9534f'
                                });
                            }else{
                                Custombox.close();

                                $('#new_client').trigger('reset');
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
                        removeLoading(btn, 'Add Client');
                        Custombox.close();

                        $.toast({
                            text : 'Network error',
                            heading : 'Error',
                            position: 'top-right',
                            showHideTransition : 'slide', 
                            bgColor: '#d9534f'
                        });
                    }
                })
            }
            
        });

        function edit(client){
            $('#edit-client').find('[name="name"]').val(client.name);
            $('#edit-client').find('[name="email"]').val(client.email);
            $('#edit-client').find('[name="contact_person_name"]').val(client.contact_person_name);
            $('#edit-client').find('[name="phone_number"]').val(client.phone_number);
            $('#edit-client').find('[name="number_of_guards"]').val(client.number_of_guards);
            $('#edit-client').find('[name="description"]').val(client.description);
            $('#edit-client').find('[name="id"]').val(client.id);

            $('#edit-client').modal('show');
        }

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

            $('#date-range-2').find('input').each(function(){
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
                })
            }
        });

        function deleteClient(client)
        {
            $('#delete-client-id').val(client);

            $('#deleteClientModal').modal('show');
        }

        $('#btn-delete-client').on('click', function(e){
            e.preventDefault();
            var btn = $(this);

            applyLoading(btn);
            
            $.ajax({
                url: '/api/client/delete/'+ $("#delete-client-id").val(),
                method: 'DELETE',
                success: function(data){
                    removeLoading(btn, 'Delete');
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
                                location.reload();
                            }, 500);
                        }
                },
                error: function(err){
                    removeLoading(btn, 'Delete');
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

        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();

    </script>
@endsection