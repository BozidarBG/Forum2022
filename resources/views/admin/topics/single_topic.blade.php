@extends('admin.partials.admin-layout')
@section('styles')
    <style>
        .avatar_navbar{
            width:50px;
        }
    </style>
    @endsection

@section('content')
<div class="container text-white">
    <div class="row">
    <x-form-success key="comment_success" />
        <div class="card mb-2">
            <div class="card-header d-flex justify-content-between align-items-center my-1">
                <div class="topic_header_left">
                    <img src="{{asset($topic->user->getAvatar())}}" class="avatar_navbar" alt="">
                    <span>{{$topic->user->username}}</span>
                </div>
                <div class="topic_header_right">
                <i class="bi bi-alarm"></i> {{$topic->created_at->diffForHumans()}}
                </div>
            </div>
            <div class="card-body">
                <h1 class="my-4">{{$topic->title}}</h1>
                <div class="topic_body_header my-3">
                    <a href="{{route('users.topics.by.category', ['slug'=>$topic->category->slug])}}" ><span class="badge" style="background-color:{{$topic->category->color}}">{{$topic->category->name}}</span></a>&nbsp;&nbsp;
                    @foreach($topic->tags as $tag)
                    <a href="{{route('users.topics.by.tag', ['slug'=>$tag->slug])}}"><span class="badge bg-success">{{$tag->name}}</span></a>&nbsp;&nbsp;
                    @endforeach
                </div>
                <div class="topic_body_content mb-3">
                    {!! $topic->description !!}
                </div>
            </div>
            <div class="card-footer row">
                <div class="card_footer_items col-10"> 
                    <i class="bi bi-hand-thumbs-up-fill "></i>{{$likes_count}}
                    <i class="bi bi-hand-thumbs-down-fill"></i>{{$dislikes_count}}
                    <i class="bi bi-heart-fill"></i>{{$fav_count}}
                </div>

            </div>
        </div>

        @auth
            <div class="card bg-warning my-3 d-none" id="comment_form"> 
                <form method="POST" action="{{ route('users.comment.store') }}">
            @csrf
            <input type="hidden" value="{{$topic->id}}" name="topic_id">
            <div class="mb-3 my-4">
                    <label class="form-label">Your comment</label>
                    <x-form-errors name="body" />
                    <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="3">{{old('body')}}</textarea>
                </div>
           
            <div class="mb-3">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
               
      </form>

            </div>
        @endauth

        @forelse($topic->comments as $comment)
        <div class="w-75 px-5">

            <div class="card w-100 ml-5 mb-2">
                <div class="card-header d-flex justify-content-between align-items-center my-1">
                    <div class="topic_header_left">
                        <img src="{{asset($comment->user->getAvatar())}}" class="avatar_navbar" alt="">
                        <span>{{$comment->user->username}}</span>
                    </div>
                    <div class="topic_header_right">
                        <i class="bi bi-alarm"></i> {{$comment->created_at->diffForHumans()}}
                    </div>
                </div>
                <div class="card-body">
                    {!! $comment->body !!}
                </div>
                <div class="card-footer">
                    <div class="card_footer_items"> 
                        <i class="bi bi-hand-thumbs-up-fill "></i>
                        <i class="bi bi-hand-thumbs-down-fill"></i>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <p class="alert alert-warning">There are no comments for this topic. Be the first to write something...</p>
        </div>
        
        @endforelse
    </div>
</div>
@endsection