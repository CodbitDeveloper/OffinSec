@extends('layouts.main-layout', ['page_title' => 'Home'])

@section('content')
    @if(Auth::user()->role === "admin")
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <h4 class="header-title mb-4">Business Overview</h4>

                <div class="row">
                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="card-box mb-0 widget-chart-two">
                            <div class="widget-chart-two-content">
                                <p class="text-muted mb-0 mt-2">Guard Count</p>
                                <h3 class="">{{ $guards }}</h3>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="card-box mb-0 widget-chart-two">
                            <div class="widget-chart-two-content">
                                <p class="text-muted mb-0 mt-2">Client Count</p>
                                <h3 class="">{{ $clients }}</h3>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-6 col-xl-3">
                        <div class="card-box mb-0 widget-chart-two">
                            <div class="widget-chart-two-content">
                                <p class="text-muted mb-0 mt-2">System User Count</p>
                                <h3 class="">{{ $users }}</h3>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-xl-8">
            <div class="card-box">
                <h4 class="header-title mb-3">MOST PUNCTUAL GUARDS</h4>

                <div class="table-responsive">
                    <table class="table table-hover table-centered m-0">

                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Name</th>
                                <th>Current Site</th>
                                <th>Number Of Days</th>
                                <th>Contact Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($best_guards as $guy)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/assets/images/guards/' . $guy->photo) }}" alt=""
                                            title="contact-img" class="rounded-circle thumb-sm" />
                                    </td>

                                    <td>
                                        {{ $guy->firstname . ' ' . $guy->lastname }}
                                    </td>

                                    <td>
                                        {{ $guy->current_site }}
                                    </td>

                                    <td>
                                        {{ $guy->attendances_count }}
                                    </td>

                                    <td>
                                        {{ $guy->phone_number }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($best_guards->count() < 1)
                        <p class="text-center text-muted mt-4">No attendance recorded this month</p>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-xl-4">
            <div>
                <h4 class="m-t-0 mb-4 header-title">Quick Links</h4>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="col-8">
                            <h5>
                                <a href="/attendance">View attendance</a>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="col-8">
                            <h5>
                                <a href="/view-deductions">View guard monthly offences</a>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="col-8">
                            <h5>
                                <a href="/view-reports">View reports</a>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <div class="col-8">
                            <h5>
                                <a href="/send-report">Send a client report</a>
                            </h5>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <!-- end row -->
    @else
    <div class="row">
        <div class="col-lg-10">
            <div class="jumbotron p-4">
                <h1 class="display-4">Hello {{Auth::user()->firstname}} </h1>
                <p class="lead">{{Illuminate\Foundation\Inspiring::quote()}}</p>
            </div>
        </div>
    </div>
    @endif
@endsection
