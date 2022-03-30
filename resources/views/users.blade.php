@extends('layouts.main-layout', ['page_title'=>'Users'])
@section('styles')
    <link href="{{ asset('plugins/custombox/css/custombox.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <style>
        .subtext {
            color: #949494;
            font-weight: normal;
            font-size: 12px;
        }

        .text-small {
            font-size: 12px;
        }

    </style>
@endsection
@section('content')
    <div class="row mb-4">
        <div class="col-sm-4">
            <button href="#custom-modal" class="btn btn-custom waves-effect w-md mr-2 mb-2" data-animation="contentscale"
                data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a">
                New User</button>
        </div>
    </div>
    <div class="row" id="users-list">
        @foreach ($users as $user)
            <div class="col-md-4">
                <div class="card border-secondary m-b-30">
                    <div class="card-body text-secondary">
                        <h5 class="card-title text-custom">{{ ucwords($user->firstname . ' ' . $user->lastname) }}<br /><span
                                class="subtext">@<i>{{ $user->username }}</i></span></h5>
                        <p class="card-text"><b>Phone number: </b> {{ $user->phone_number }}</p>
                    </div>
                    @if (strtolower(Auth::user()->role) == 'admin')
                        <div class="card-footer text-right">
                            <button class="btn btn-outline btn-primary waves-effect"
                                onclick="edit({{ $user }})">Edit</button>
                            <button class="btn btn-outline btn-danger waves-effect"
                                onclick="toggleActive('{{ $user->id }}', this)">{{ $user->active == 1 ? 'Deactivate' : 'Activate' }}</button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('modals')
    <div class="modal fade" id="edit-user">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title mt-2">Edit User</h4>
                </div>
                <div class="modal-body p-4">
                    <form role="form" id="edit_user_form">
                        @csrf
                        <div class="form-row mb-4">
                            <div class="col-md-4 col-sm-12">
                                <label for="name">Firstname</label>
                                <input class="form-control resetable" type="text" id="edit_fname" placeholder="Kwasi"
                                    name="firstname">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="name">Lastname</label>
                                <input class="form-control resetable" type="text" id="edit_lname" placeholder="Koomson"
                                    name="lastname">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="email">Phone</label>
                                <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control resetable"
                                    name="edit_phone_number">
                                <input type="hidden" name="id" />
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="col-md-4 col-sm-12">
                                <label for="password">Password</label>
                                <input class="form-control resetable" type="password" id="edit_password" name="password">
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <label for="password">Confirm Password</label>
                                <input class="form-control resetable" type="password" id="edit_password_confirm"
                                    name="password_confirmation">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="role" class="col-form-label">Role</label>
                                <select class="selectpicker show-tick form-control" data-style="btn-primary" title="Role"
                                    id="edit_role" name="role">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    <option value="operations-manager">Operations Manager</option>
                                    <option value="zone-supervisor">Zone Supervisor</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                            <button type="submit"
                                class="btn btn-custom ml-1 waves-effect waves-light save-category">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="custom-modal" class="modal-demo">
        <button type="button" class="close" onclick="Custombox.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <h4 class="custom-modal-title">New User</h4>
        <div class="custom-modal-text">
            <form class="form-horizontal" action="#" id="new_user">
                @csrf
                <div class="form-row mb-4">
                    <div class="col-md-4 col-sm-12">
                        <label for="name">First Name</label>
                        <input class="form-control resetable" type="text" id="firstname" placeholder="Kwasi"
                            name="firstname">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="email">Last Name</label>
                        <input class="form-control resetable" type="text" id="lastname" placeholder="Koomson"
                            name="lastname">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="email">Phone</label>
                        <input type="tel" placeholder="" data-mask="(999) 999-999999" class="form-control resetable"
                            name="phone_number">
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-4 col-sm-12">
                        <label for="no_of_guards">Username</label>
                        <input class="form-control resetable" type="text" min="1" id="username" name="username">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="description">Password</label>
                        <input class="form-control resetable" type="password" id="password" name="password">
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="description">Confirm Password</label>
                        <input class="form-control resetable" type="password" id="password_confirm"
                            name="password_confirmation">
                    </div>
                </div>
                <div class="form-row mb-4">
                    <div class="form-group col-md-12">
                        <label for="role" class="col-form-label">Role</label>
                        <select class="selectpicker show-tick form-control" data-style="btn-primary" title="Role" id="role"
                            name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="operations-manager">Operations Manager</option>
                            <option value="zone-supervisor">Zone Supervisor</option>
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
    <!--Animations-->
    <script src="{{ asset('plugins/custombox/js/custombox.min.js') }}"></script>
    <script src="{{ asset('plugins/custombox/js/legacy.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

    <!--Telephone Mask-->
    <script src="{{ asset('/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js') }}" type="text/javascript"></script>


    <!--JQUERY toast-->
    <script>
        jQuery.browser = {};
        (function() {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();

        $('#new_user').on('submit', function(e) {
            e.preventDefault();
            $('.text-danger').css('display', 'none');

            var error = false;

            $(this).find('.resetable').each(function() {
                if ($(this).val() == '' || $(this).val() == null) {
                    error = true;
                    $(this).closest('div').append(
                        '<span class="text-danger text-small">This field is required</span>')
                }
            });

            if ($('#password').val() != $('#password_confirm').val()) {
                error = true;
                $('#password_confirm').closest('div').append(
                    '<span class="text-danger text-small">This field must match the specified password</span>')
            }

            if (!error) {
                $('.text-danger').css('display', 'none');

                data = $(this).serialize();
                btn = $(this).find('[type="submit"]');

                applyLoading(btn);

                $.ajax({
                    url: 'api/users/add',
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        removeLoading(btn, 'Add User');
                        if (data.error) {
                            Custombox.close();

                            $.toast({
                                text: data.message,
                                heading: 'Error',
                                position: 'top-right',
                                showHideTransition: 'slide',
                                bgColor: '#d9534f'
                            });
                        } else {
                            Custombox.close();

                            $('#new_client').trigger('reset');
                            $.toast({
                                text: data.message,
                                heading: 'Done',
                                position: 'top-right',
                                bgColor: '#5cb85c',
                                showHideTransition: 'slide'
                            });

                            $('#users-list').append(
                                '<div class="col-md-4"><div class="card border-secondary m-b-30">' +
                                '<div class="card-body text-secondary">' +
                                '<h5 class="card-title text-custom">' + data.data.firstname + ' ' +
                                data.data.lastname + '<br/><span class="subtext">@<i>' + data.data
                                .username + '</i></span></h5>' +
                                '<p class="card-text"><b>Phone number: </b> {{ $user->phone_number }}</p></div><div class="card-footer text-right">' +
                                '<button class="btn btn-outline btn-primary waves-effect mr-1" onclick="edit({\'id\' : \'' +
                                data.data.id + '\', \'firstname\' : \'' + data.data.firstname +
                                '\', \'lastname\' : \'' + data.data.lastname +
                                '\', \'phone_number\' : \'' + data.data.phone_number +
                                '\', \'role\' : \'' + data.data.role + '\'})">Edit</button>' +
                                '<button class="btn btn-outline btn-danger waves-effect" onclick="toggleActive(\'' +
                                data.data.id + '\', this)">Deactivate</button></div></div></div>');

                            $('#new_user').trigger('reset');
                        }
                    },
                    error: function(err) {
                        removeLoading(btn, 'Add User');
                        Custombox.close();

                        $.toast({
                            text: 'Network error',
                            heading: 'Error',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            bgColor: '#d9534f'
                        });
                    }
                })
            }
        });

        function toggleActive(user, element) {
            el = $(element);

            var initial = el.html();
            applyLoading(el);

            $.ajax({
                url: '/api/user/toggle-active',
                method: 'post',
                data: 'user_id=' + user,
                success: function(data) {
                    removeLoading(el, initial);
                    if (data.error) {
                        $.toast({
                            text: data.message,
                            heading: 'Error',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            bgColor: '#d9534f'
                        });
                    } else {
                        if (data.data.active == 0) {
                            removeLoading(el, 'Activate');
                        } else {
                            removeLoading(el, 'Deactivate');
                        }

                        $.toast({
                            text: data.message,
                            heading: 'Done',
                            position: 'top-right',
                            bgColor: '#5cb85c',
                            showHideTransition: 'slide'
                        });
                    }
                },

                error: function(error) {
                    removeLoading(el, initial);
                    $.toast({
                        text: 'There was a network error',
                        heading: 'Error',
                        position: 'top-right',
                        showHideTransition: 'slide',
                        bgColor: '#d9534f'
                    });
                }
            })
        }

        function edit(user) {
            $('#edit-user').find('[name="firstname"]').val(user.firstname);
            $('#edit-user').find('[name="lastname"]').val(user.lastname);
            $('#edit-user').find('[name="edit_phone_number"]').val(user.phone_number);
            $('#edit-user').find('[name="id"]').val(user.id);

            $('#edit_role').find('option').each(function() {
                if ($(this).val() == user.role) {
                    $(this).prop('selected', true);
                    $('#edit_role').val(user.role);
                    $('.selectpicker').selectpicker('refresh');
                }
            })

            $('#edit-user').modal('show');
        }

        $('#edit_user_form').on('submit', function(e) {
            e.preventDefault();
            var btn = $(this).find('[type="submit"]');

            data = $(this).serialize();

            var error = false;

            if (!error) {
                $(this).find('.text-danger').css('display', 'none');

                applyLoading(btn);

                $.ajax({
                    url: '/api/user/update',
                    method: 'PUT',
                    data: data,
                    success: function(data) {
                        removeLoading(btn, 'Update');
                        if (data.error) {
                            $.toast({
                                text: data.message,
                                heading: 'Error',
                                position: 'top-right',
                                showHideTransition: 'slide',
                                bgColor: '#d9534f'
                            });
                        } else {
                            $.toast({
                                text: data.message,
                                heading: 'Done',
                                position: 'top-right',
                                bgColor: '#5cb85c',
                                showHideTransition: 'slide'
                            });

                            setTimeout(function() {
                                location.replace('/users');
                            }, 500);
                        }
                    },
                    error: function(err) {
                        removeLoading(btn, 'Update');
                        $.toast({
                            text: 'Network error',
                            heading: 'Error',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            bgColor: '#d9534f'
                        });
                    }
                })
            }
        });
    </script>
@endsection
