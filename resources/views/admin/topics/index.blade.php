@extends('admin.partials.admin-layout')
@section('styles')
    <style>
        .f_center{
            display:flex;
            justify-content:center;
            align-items:center;
        }
    </style>
    @endsection

@section('content')



    <!-- Modal -->
@include('admin.partials.confirmation-modal')
<div class="container">
    <div class="row my-4 ">
        <div class="col-md-1 f_center">
            ID
        </div>
        <div class="col-md-2 f_center">
            Avatar
        </div>
        <div class="col-md-3">
            Topics
        </div>
        <div class="col-md-1 f_center">
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
        <div class="col-md-2 f_center">
            Action
        </div>
    </div>

    <h2>{{$title}}</h2>
    @include('admin.partials.success_top_message')
    @foreach($topics as $topic)
    <div class="row bg-default text-white">
    <div class="col-md-1 f_center">
            {{$topic->id}}
        </div>
        <div class="col-md-2 f_center">
        <a class="text-white" href="{{route('admin.show.users', $topic->user)}}">{{$topic->user->username}}</a>
        </div>
        <div class="col-md-3 py-2">
            <div class="d-flex ">
                @if($topic->status === 0)
                <i class="bi bi-lock-fill"></i>&nbsp;
                @endif
                <a class="topic_link" href="{{route('admin.show.topic', $topic)}}">
                    <p>{{$topic->title}}</p>
                </a>
            </div>
            <div class="d-flex">
            @foreach($topic->tags as $tag)
                <a href="{{route('users.topics.by.tag', ['slug'=>$tag->slug])}}"><span class="badge bg-success">{{$tag->name}}</span></a>&nbsp;&nbsp;
                @endforeach
            </div>
        </div>
        <div class="col-md-1 f_center">
            <a href="{{route('users.topics.by.category', ['slug'=>$topic->category->slug])}}" ><span class="badge" style="background-color:{{$topic->category->color}}">{{$topic->category->name}}</span></a>&nbsp;&nbsp;
        </div>
        <div class="col-md-1 f_center">
        {{$topic->likes->count()}}
        </div>
        <div class="col-md-1 f_center">
            {{$topic->comments->count()}}
        </div>
        <div class="col-md-1 f_center">
        {{$topic->views}}

        </div>
        <div class="col-md-2 d-flex f_center">
            @if($title=="Active Topics")
            <form action="{{route('admin.topic.update', $topic)}}" method="post" >
                @csrf
                <button type="submit" data-toggle="tooltip" data-placement="left" title="Pin or Unpin"  class="btn btn-success btn-sm btn-icon">{{$topic->pinned ==0 ? 'Pin' : 'Unpin'}}</button>
            </form>
            <form action="{{route('admin.topic.destroy', $topic)}}" method="post" class="fc_delete_form">
                @csrf
                <button type="submit" data-toggle="modal" data-target="#confirmationModal" title="Delete" class="btn btn-danger btn-sm btn-icon fc_delete_btn"><i class="tim-icons icon-simple-remove"></i></button>
            </form>
            @elseif($title=="Deleted Topics")
            <form action="{{route('admin.topic.restore', ['id'=>$topic->id])}}" method="post" >
                @csrf
                <button type="submit" data-toggle="tooltip" data-placement="left" title="restore"  class="btn btn-success btn-sm ">Restore</button>
            </form>
            <form action="{{route('admin.topic.destroy.trashed', ['id'=>$topic->id])}}" method="post" class="fc_delete_form">
                @csrf
                <button type="submit" data-toggle="modal" data-target="#confirmationModal" title="Delete" class="btn btn-danger btn-sm btn-icon fc_delete_btn"><i class="tim-icons icon-simple-remove"></i></button>
            </form>
            @else
                there is some error
            @endif
        </div>
    </div>
    @endforeach
    <br>
    {{$topics->links()}}
</div>

        

@endsection
@section('scripts')
<script>
    let delete_forms=document.getElementsByClassName('fc_delete_form');
    let form_to_be_deleted;
    let delete_buttons=document.getElementsByClassName('fc_delete_btn')
    for(let i=0; i<delete_forms.length; i++){
        delete_forms[i].addEventListener('submit', (e)=>{
            e.preventDefault();
           

        });
    }
    for(let i=0; i<delete_buttons.length; i++){
        delete_buttons[i].addEventListener('click', (e)=>{
            e.preventDefault();
           form_to_be_deleted=e.target.closest('form');
           document.getElementById('submit_modal').addEventListener('click', ()=>{
               console.log(form_to_be_deleted)
                form_to_be_deleted.submit();
            });

        });
    }

    
</script>
@endsection