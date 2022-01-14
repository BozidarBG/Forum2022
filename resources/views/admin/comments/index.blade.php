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
            User
        </div>
        <div class="col-md-5">
            Comment
        </div>
        <div class="col-md-2 f_center">
            Created
        </div>
        <div class="col-md-2 f_center">
            Action
        </div>
    </div>

    <h2>Comments</h2>
    @include('admin.partials.success_top_message')
    @foreach($comments as $comment)
    <div class="row bg-default text-white">
    <div class="col-md-1 f_center">
            {{$comment->id}}
        </div>
        <div class="col-md-2 f_center">
        <a class="text-white" href="{{route('admin.show.users', $comment->user)}}">{{$comment->user->username}}</a>
        </div>
        <div class="col-md-5 py-2">
            <div class="d-flex ">
                <p>{{$comment->body}}</p>
            </div>
        </div>
        
        <div class="col-md-2 f_center">
        {{$comment->created_at->format('d.m.Y H:i')}}
        </div>
        <div class="col-md-2 d-flex f_center">
            <form action="{{route('admin.comment.destroy', $comment)}}" method="post" class="fc_delete_form">
                @csrf
                <button type="submit" data-toggle="modal" data-target="#confirmationModal" title="Delete" class="btn btn-danger btn-sm btn-icon fc_delete_btn"><i class="tim-icons icon-simple-remove"></i></button>
            </form>
        </div>
    </div>
    @endforeach
    <br>
    {{$comments->links()}}
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