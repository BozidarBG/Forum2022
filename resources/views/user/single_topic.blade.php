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
    <x-form-success key="topic_success" />
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
                <div class="card_footer_items col-7"> 

                    @auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="interact_btn bi bi-hand-thumbs-up-fill @if($topic->likedByAuthUser($topic->likesDislikes)) success_text @else text-black @endif" data-id="{{$topic->id}}" data-model="topic" data-type="l"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($topic->likedByAuthUser($topic->likesDislikes)) success_text @else text-black @endif">{{$topic->likes->count()}}</span>
                    
                    @auth
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="interact_btn bi bi-hand-thumbs-down-fill @if($topic->dislikedByAuthUser($topic->likesDislikes)) success_text @else text-black @endif" data-id="{{$topic->id}}" data-model="topic" data-type="d"></i>
                    @auth
                    </a>
                    @endauth
                    <span class="@if($topic->dislikedByAuthUser($topic->likesDislikes)) success_text @else text-black @endif">{{$topic->dislikes->count()}}</span>
                    
                    @auth 
                    <a href="javascript:void(0)" class="text-decoration-none">
                    @endauth
                        <i class="interact_btn bi bi-heart-fill @if($topic->favouritedByAuthUser($topic->favourites)) success_text @else text-black @endif" data-id="{{$topic->id}}" data-model="favourite_topic" data-type="fav"></i>
                    @auth 
                    </a>
                    @endauth 
                    <span class="@if($topic->favouritedByAuthUser($topic->favourites)) success_text @else text-black @endif">{{$topic->favourites->count()}}</span>

                </div>
                <div class="col-5 d-flex justify-content-end">
                    @auth
                        @if($topic->status===1)
                        <a href="#"  class="toggle_comment_form btn btn-info" data-form="comment_topic_{{$topic->id}}">Post a Comment</a>&nbsp;
                        @else
                        <button class="btn btn-warning disabled">This topic is closed for comments</button>&nbsp;
                        @endif
                        <a href="#" class="toggle_report_form btn btn-danger" data-form="report_topic_{{$topic->id}}">Report</a>
                    @endauth
                </div>
            </div>
            @if($topic->user_id===auth()->id())
            <div class="card_footer_row p-3">
                <form action="{{route('users.topic.update', $topic)}}" method="post">
                    @csrf 
                    <button type="submit" class="btn btn-warning btn-lg">
                        @if($topic->status===1) Close this topic @else Reopen this topic @endif</button>
                </form>
            </div>
            @endif
        </div>

        @auth
            <div class="card border border-warning my-3 d-none comment_form" id="comment_topic_{{$topic->id}}"> 
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
            <div class="card border border-danger my-3 d-none report_form" id="report_topic_{{$topic->id}}"> 
                <form method="POST" action="{{ route('users.complain.store') }}">
                    @csrf
                    <input type="hidden" value="{{$topic->id}}" name="id">
                    <input type="hidden" value="topic" name="type">

                    <div class="mb-3 my-4">
                            <label class="form-label">State a reason</label>
                            <x-form-errors name="body" />
                            <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="3">{{old('body')}}</textarea>
                        </div>
                
                    <div class="mb-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
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
                        <i class="bi bi-alarm"></i>{{$comment->created_at->diffForHumans()}}
                    </div>
                </div>
                <div class="card-body">
                    {!! $comment->body !!} aj di {{$comment->id}} user je {{auth()->id()}}
                </div>
                <div class="card-footer row g-0">
                    <div class="card_footer_items col-7"> 
                        @auth 
                        <a href="javascript:void(0)" class="text-decoration-none">
                        @endauth
                            <i class="interact_btn bi bi-hand-thumbs-up-fill @if($comment->likedByAuthUser($comment->likesDislikes)) success_text @else text-black @endif" data-id="{{$comment->id}}" data-model="comment" data-type="l"></i>
                        @auth
                        </a>
                        @endauth
                        <span class="@if($comment->likedByAuthUser($comment->likesDislikes)) success_text @else text-black @endif">{{$comment->likes->count()}}</span>
                        
                        @auth
                        <a href="javascript:void(0)" class="text-decoration-none">
                        @endauth
                            <i class="interact_btn bi bi-hand-thumbs-down-fill @if($comment->dislikedByAuthUser($comment->likesDislikes)) success_text @else text-black @endif" data-id="{{$comment->id}}" data-model="comment" data-type="d"></i>
                        @auth
                        </a>
                        @endauth
                        <span class="@if($comment->dislikedByAuthUser($comment->likesDislikes)) success_text @else text-black @endif">{{$comment->dislikes->count()}}</span>
                        
                        @auth 
                        <a href="javascript:void(0)" class="text-decoration-none">
                        @endauth
                            <i class="interact_btn bi bi-heart-fill @if($comment->favouritedByAuthUser($comment->favourites)) success_text @else text-black @endif" data-id="{{$comment->id}}" data-model="favourite_comment" data-type="fav"></i>
                        @auth 
                        </a>
                        @endauth 
                        <span class="@if($comment->favouritedByAuthUser($comment->favourites)) success_text @else text-black @endif">{{$comment->favourites->count()}}</span>
                    </div>
                    <div class="col-5 d-flex justify-content-end">
                    @auth

                        <a href="#" class="toggle_report_form btn btn-danger" data-form="report_comment_{{$comment->id}}">Report</a>
                    @endauth
                    </div>
                </div>
            </div>

            @auth

            <div class="px-5 card border border-danger my-3 d-none report_form" id="report_comment_{{$comment->id}}"> 
                <form method="POST" action="{{ route('users.complain.store') }}">
                    @csrf
                    <input type="hidden" value="{{$comment->id}}" name="id">
                    <input type="hidden" value="comment" name="type">
                    <div class="mb-3 my-4">
                            <label class="form-label">State a reason</label>
                            <x-form-errors name="body" />
                            <textarea class="form-control @error('body') is-invalid @enderror" name="body" rows="3">{{old('body')}}</textarea>
                        </div>
                
                    <div class="mb-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </form>
            </div>
        @endauth
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
    /*
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
*/
    function comment(){
        const openCommentBtns=document.getElementsByClassName('toggle_comment_form');
        for(let i=0; i<openCommentBtns.length; i++){
            openCommentBtns[i].addEventListener('click', (e)=>{
                e.preventDefault();
                //take data-form value and open form with that ID
                let form_id=e.target.hasAttribute('data-form') ? e.target.getAttribute('data-form') : null;
                if(form_id){
                    let form_div=document.getElementById(form_id);
                    form_div.classList.toggle('d-none');
                    if(e.target.textContent==="Post a Comment"){
                        e.target.textContent="Hide Comment Form";
                    }else{
                        e.target.textContent="Post a Comment";
                    }
                }
            });
        }
    }

    function report(){
        const openReportBtns=document.getElementsByClassName('toggle_report_form');
        for(let i=0; i<openReportBtns.length; i++){
            openReportBtns[i].addEventListener('click', (e)=>{
                e.preventDefault();
                //take data-form value and open form with that ID
                let form_id=e.target.hasAttribute('data-form') ? e.target.getAttribute('data-form') : null;
                if(form_id){
                    let form_div=document.getElementById(form_id);
                    form_div.classList.toggle('d-none');
                    if(e.target.textContent==="Report"){
                        e.target.textContent="Hide Report Form";
                    }else{
                        e.target.textContent="Report";
                    }
                }
            });
        }
    }


    function interact(){
        let i_btns=document.getElementsByClassName('interact_btn');
        for(let i=0; i<i_btns.length; i++){
            i_btns[i].addEventListener('click', (e)=>{
                //what is clicked
                if(e.target.hasAttribute('data-model') && e.target.hasAttribute('data-type') && e.target.hasAttribute('data-id')){

                    let model=e.target.getAttribute('data-model');
                    let id=e.target.getAttribute('data-id');
                    let type=e.target.getAttribute('data-type');
                    let dataSet={};
                    dataSet.id=id;
                    dataSet.model=model;
                    dataSet.type=type;
                    dataSet._token="{{@csrf_token()}}";

                    if(model==="topic" || model==="comment"){
                        sendAjax('/like', dataSet, e.target);
                    }else if(model==="favourite_topic" || model==="favourite_comment"){
                        sendAjax('/fav', dataSet, e.target);
                    }else{
                        console.log('Model does not exist!')
                    }
                }

            });
        }
    }
    function sendAjax(route, dataSet, e_target){
        axios.post(route, dataSet).then((data)=>{
            //console.log(data.data);//success: {likes: 0, dislikes: 3, id: '141', model: 'topic'}
            if(data.data.success.hasOwnProperty('new_count')){//it is a fav
                let new_count=data.data.success.new_count;
                toggleClasses(e_target);
                e_target.parentElement.nextElementSibling.textContent=new_count;
            }else if(data.data.success.hasOwnProperty('model')){//it is like or dislike

                let parent=e_target.parentElement.parentElement;
                let like_target;
                let dislike_target;
                let old_likes;
                let old_dislikes;
                if(e_target.hasAttribute('data-type') && e_target.getAttribute('data-type')==="l"){
                    //it is like
                    like_target=e_target;
                    dislike_target=parent.getElementsByTagName('i')[1];
                }else if(e_target.hasAttribute('data-type') && e_target.getAttribute('data-type')==="d"){
                    //it is dislike
                    like_target=parent.getElementsByTagName('i')[0];
                    dislike_target=e_target;
                }
                old_likes=like_target.parentElement.nextElementSibling.textContent;
                old_dislikes=dislike_target.parentElement.nextElementSibling.textContent;
                updateLikeDislikeCount(like_target, dislike_target, data.data.success.likes, data.data.success.dislikes);

                //check to see which buttons w/count needs to change color
                if(old_likes != data.data.success.likes){
                    toggleClasses(like_target);
                }
                if(old_dislikes != data.data.success.dislikes){
                    toggleClasses(dislike_target)
                }
            }
        });
       
    }
    function updateLikeDislikeCount(like_target, dislike_target, like_count, dislike_count){
        like_target.parentElement.nextElementSibling.textContent=like_count;
        dislike_target.parentElement.nextElementSibling.textContent=dislike_count;
    }


//turns i tag (which is event target) and following span into green  or black color
    function toggleClasses(e_target){
        if(e_target.classList.contains('success_text')){
            e_target.classList.remove('success_text')
            e_target.classList.add('text-black')
            e_target.parentElement.nextElementSibling.classList.remove('success_text')
            e_target.parentElement.nextElementSibling.classList.add('text-black')
        }else{
            e_target.classList.add('success_text')
            e_target.classList.remove('text-black')
            e_target.parentElement.nextElementSibling.classList.add('success_text')
            e_target.parentElement.nextElementSibling.classList.remove('text-black')
        }
    }


    interact();
    comment();
    report();
</script>
@endsection




