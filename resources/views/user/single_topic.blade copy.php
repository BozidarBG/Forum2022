@extends('layouts.users')

@section('title')
single topic
@endsection

<style>
    .success_bg{
        fill:#68c193 !important;
    }
    .success_text{
        color:#68c193 !important;
    }
    .bi{
        font-size:25px;
        
    }
</style>

@section('content')
<div class="container">
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
                    @auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="thumbs_btn bi bi-hand-thumbs-up-fill @if($topic->likedByAuthUser()) success_text @else text-black @endif" data-id="{{$topic->id}}" data-model="topic" data-type="like"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($topic->likedByAuthUser()) success_text @else text-black @endif">{{$topic->likes->count()}}</span>
                    
                    @auth
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="thumbs_btn bi bi-hand-thumbs-down-fill @if($topic->dislikedByAuthUser()) success_text @else text-black @endif" data-id="{{$topic->id}}" data-model="topic" data-type="dislike"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($topic->dislikedByAuthUser()) success_text @else text-black @endif">{{$topic->dislikes->count()}}</span>
                    
                    @auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="bi bi-heart-fill @if($topic->favouritedByAuthUser()) success_text @else text-black @endif" id="fav_btn" data-id="{{$topic->id}}"></i>
                    @auth 
                    </a>
                    @endauth 
                    <span id="fav_count" class="@if($topic->favouritedByAuthUser()) success_text @else text-black @endif">{{$topic->favourites->count()}}</span>

                </div>
                <div class="col-2">
                    @auth
                        <a href="#" id="toggle_comment_form" class="float-end btn btn-info">Post a Comment</a>
                    @endauth
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
                    @auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="thumbs_btn bi bi-hand-thumbs-up-fill @if($comment->likedByAuthUser()) success_text @else text-black @endif" data-id="{{$comment->id}}" data-model="comment" data-type="like"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($comment->likedByAuthUser()) success_text @else text-black @endif">{{$comment->likes->count()}}</span>
                    
                    @auth
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="thumbs_btn bi bi-hand-thumbs-down-fill @if($comment->dislikedByAuthUser()) success_text @else text-black @endif" data-id="{{$comment->id}}" data-model="comment" data-type="dislike"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($comment->dislikedByAuthUser()) success_text @else text-black @endif">{{$comment->dislikes->count()}}</span>
                    
                    @auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="fav_btn bi bi-heart-fill @if($comment->favouritedByAuthUser()) success_text @else text-black @endif" data-id="{{$comment->id}}"></i>
                    @auth 
                    </a>
                    @endauth 
                    <span id="fav_count" class="@if($comment->favouritedByAuthUser()) success_text @else text-black @endif">{{$comment->favourites->count()}}</span>
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

@section('scripts')
<script>
const commentBox=document.getElementById('comment_form');
const commentToggleBtn=document.getElementById('toggle_comment_form');
commentToggleBtn.addEventListener('click', (e)=>{
    e.preventDefault();
    commentBox.classList.toggle('d-none');
    if(commentToggleBtn.innerText==="Post a Comment"){
        commentToggleBtn.innerText="Hide Comment Form";
    }else{
        commentToggleBtn.innerText="Post a Comment";

    }
});

    function fav(){
        document.getElementById('fav_btn').addEventListener('click', function () {
                let dataSet={};
                let id=this.getAttribute('data-id');
                if(id){
                    //dataSet._token=csrf_token;
                    dataSet.id=id;
                    axios.post('/topic/fav', dataSet).then((data)=>{
                        if(data.data.success){
                            document.getElementById('fav_count').innerHTML=data.data.success.fav_count
                        }
                        if(this.classList.contains('success_text')){
                            this.classList.remove('success_text')
                            this.classList.add('text-black')
                            this.parentElement.nextElementSibling.classList.remove('success_text')
                            this.parentElement.nextElementSibling.classList.add('text-black')

                        }else{
                            this.classList.add('success_text')
                            this.classList.remove('text-black')
                            this.parentElement.nextElementSibling.classList.add('success_text')
                            this.parentElement.nextElementSibling.classList.remove('text-black')
                        }
                    });
                }
            });

    }
    fav()
</script>
@endsection