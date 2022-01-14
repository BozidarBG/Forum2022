@extends('layouts.users')

@section('title')
profile
@endsection



@section('content')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="card" style="width: 18rem;">
            <img src="{{asset($user->getAvatar())}}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{$user->username}}</h5>
                    <p class="card-text">{{$user->profile->about}}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="card">
                <ul class="list-group">
                    <li class="list-group-item">Location: {{$user->profile->location ?? 'Unknown'}}</li>
                    <li class="list-group-item">Website: {{$user->profile->website ?? 'Unknown'}}</li>
                    <li class="list-group-item">Public email: {{$user->profile->public_email ?? 'Unknown'}}</li>
                    <li class="list-group-item">Member since: {{$user->created_at->format('d:m.Y')}}</li>
                    <li class="list-group-item">Total topics count: {{$topics->count()}}</li>
                    <li class="list-group-item">Total comments count: {{$user->comment_count}}</li>
                </ul>
            </div>
            </div>
    </div>
</div>
<div class="container">
    <h1 class="text-center fw-bold text-primary my-3">All Topics For User {{$user->username}}</h1>
    <div class="row my-4 ">
        <div class="col-md-2 col-sm-3 col-3 f_center">
            Avatar
        </div>
        <div class="col-md-5 col-sm-6 col-6">
            Topics
        </div>
        <div class="col-md-2 col-sm-3 col-3  f_center">
            Category
        </div>
        <div class="col-md-1 f_center  d_none_small">
            Likes
        </div>
        <div class="col-md-1 f_center d_none_small">
            Comments
        </div>
        <div class="col-md-1 f_center  d_none_small">
            Views
        </div>
    </div>

    @foreach($topics as $topic)
    <x-topic :topic="$topic"  />
    @endforeach
    <br>
    {{$topics->links()}}
    </div>
</div>

@endsection

@section('scripts')


@endsection