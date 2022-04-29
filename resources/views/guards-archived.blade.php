@extends('layouts.main-layout', ['page_title' => 'Archived Guards'])
@section('content')
            <div class="row">
                <div class="col-sm-4">
                   
                </div>
                <div class="col-sm-8">
                    <div class="project-sort float-right">
                        <div class="project-sort-item">
                            <form class="form-inline" action="">
                                <div class="input-group">
                                    <input type="text" class="form-control required" id="dob" name="q" autocomplete="false" required>
                                    <div class="input-group-append">
                                        <button class="input-group-text btn-custom" type="submit" style="cursor:pointer"><i class="mdi mdi-magnify"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end col-->
            </div>

            <div class="row">
                @if($guards->count() < 1)
                    <div class="jumbotron p-4">
                        <h1 class="display-4">{{$searching == false ? 'No archived guards' : 'No results found'}} :( </h1>
                        <p class="lead">{{$searching == false ? 'There aren\'t any guards in your archive yet' : 'Your search query did not produce any results. Try another search.'}}</p>
                        <hr class="my-4">
                        <p>{{$searching == false ? 'To add a guard to the archive, navigate to that guard\'s details page and select the "Add To Archive" button.' : 'Enter a new search query in the search box'}}</p>
                    </div>
                @endif
                @foreach($guards as $guard)
                <div class="col-lg-4">
                    <div class="text-center card-box">

                        <div class="member-card pt-2 pb-2">
                            <div class="thumb-lg member-thumb m-b-10 mx-auto">
                                <img src="{{asset('storage/assets/images/guards/'.$guard->photo)}}" onerror="this.src='{{asset('assets/images/avatar.jpg')}}'" class="rounded-circle img-thumbnail" alt="profile-image" style="width:78px; height:78px; object-fit: cover;">
                            </div>

                            <div class="">
                                <h4 class="m-b-5">{{ucwords($guard->firstname).' '.ucwords($guard->lastname)}}</h4>
                                <p class="text-muted"> 
                                    Archived on {{date('jS F, Y', strtotime($guard->deleted_at))}}
                                    <span> | </span> <span> <span class="text-pink">{{$guard->phone_number}}</span>
                                    </span></p>
                            </div>
                            <a href="/guard/{{$guard->id}}" class="btn btn-primary m-t-20 btn-rounded btn-bordered waves-effect w-md waves-light">
                                View Details
                            </a>
                        </div>

                    </div>

                </div>
                @endforeach 
                {{$guards->links()}}
            </div>
@endsection