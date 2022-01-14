@extends('layouts.users')

@section('title')
home
@endsection

@section('content')

<div class="container">
    <h1 class="text-center fw-bold text-primary my-3">{{$page_name}}</h1>
    <div class="row my-4 ">
        <div class="col-md-2 f_center">
            Avatar
        </div>
        <div class="col-md-5">
            Topics
        </div>
        <div class="col-md-2 f_center">
            Category
        </div>
        <div class="col-md-1 f_center">
            Likes
        </div>
        <div class="col-md-1 f_center">
            Comments
        </div>
        <div class="col-md-1 f_center">
            Views
        </div>
    </div>

    @if(isset($pinned))
    @foreach($pinned as $topic)
    <div class="row  border border-danger bg_light_red my-3 rounded shadow_m">
        <div class="col-md-2 f_center flex-column">
        <img src="{{asset($topic->user->getAvatar())}}" class="avatar_sm" alt="">
            <a class="my-2 user_link" href="{{route('users.see.user', ['slug'=>$topic->user->slug])}}">{{$topic->user->username}}</a>

        </div>
        <div class="col-md-5 py-2">
            <div class="d-flex ">
                <i class="bi bi-pin-angle-fill"></i>&nbsp;
                @if($topic->status === 0)
                <i class="bi bi-lock-fill"></i>&nbsp;
                @endif
                <a class="topic_link" href="{{route('users.show.topic', ['slug'=>$topic->slug])}}">
                    <p>{{$topic->title}}</p>
                </a>
            </div>
            <div class="d-flex">
            @if(count($topic->tags))
            Tags:&nbsp; &nbsp;    
            @foreach($topic->tags as $tag)
                <a href="{{route('users.topics.by.tag', ['slug'=>$tag->slug])}}"><span class="badge bg-info">{{$tag->name}}</span></a>&nbsp;&nbsp;
            @endforeach
            @endif
            </div>
        </div>
        <div class="col-md-2 f_center">
        <a href="{{route('users.topics.by.category', ['slug'=>$topic->category->slug])}}" ><span class="badge" style="background-color:{{$topic->category->color}}">{{$topic->category->name}}</span></a>&nbsp;&nbsp;
        </div>
        <div class="col-md-1 f_center">
        {{$topic->likes_count}}
        </div>
        <div class="col-md-1 f_center">
            {{$topic->comments_count}}
        </div>
        <div class="col-md-1 f_center">
            {{$topic->views}}
        </div>
    </div>
    @endforeach
    @endif
    @foreach($topics as $topic)
    <div class="row border border-light {{$topic->status ===0 ? 'bg_light_blue' : 'bg_primary' }} my-3 rounded shadow_m">
        <div class="col-md-2 f_center flex-column">
            <img src="{{asset($topic->user->getAvatar())}}" class="avatar_sm" alt="">
            <a class="my-2 user_link" href="{{route('users.see.user', ['slug'=>$topic->user->slug])}}">{{$topic->user->username}}</a>

        </div>
        <div class="col-md-5 py-2">
            <div class="d-flex ">
                @if($topic->status === 0)
                <i class="bi bi-lock-fill"></i>&nbsp;
                @endif
                <a class="topic_link" href="{{route('users.show.topic', ['slug'=>$topic->slug])}}">
                    <p>{{$topic->title}}</p>
                </a>
            </div>
            <div class="d-flex">
            @if(count($topic->tags))
            Tags:&nbsp; &nbsp;    
            @foreach($topic->tags as $tag)
                <a href="{{route('users.topics.by.tag', ['slug'=>$tag->slug])}}"><span class="badge bg-info">{{$tag->name}}</span></a>&nbsp;&nbsp;
            @endforeach
            @endif
            </div>
        </div>
        <div class="col-md-2 f_center">
            <a href="{{route('users.topics.by.category', ['slug'=>$topic->category->slug])}}" ><span class="badge" style="background-color:{{$topic->category->color}}">{{$topic->category->name}}</span></a>&nbsp;&nbsp;
        </div>
        <div class="col-md-1 f_center">
        {{$topic->likes_count}}
        </div>
        <div class="col-md-1 f_center">
            {{$topic->comments_count}}
        </div>
        <div class="col-md-1 f_center">
        {{$topic->views}}

        </div>
    </div>
    @endforeach
    <br>
    {{$topics->links()}}
</div>

@endsection