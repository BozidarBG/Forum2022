@extends('layouts.users')

@section('title')
{{$page_name}}
@endsection

@section('styles')
<style>
.content{
    min-height:80vh;
}
</style>
@endsection

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">{{session()->get('success')}}</div>
@endif
<div class="container content">
    <h1 class="text-center fw-bold text-primary my-3">{{$page_name}}</h1>
    <div class="row my-4 ">
        @if($errors->any())
        <p class="alert alert-danger">{{$errors->first()}}</p>
        @endif
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

@if(isset($pinned))
    @foreach($pinned as $topic)
    <x-topic :topic="$topic"  />
    @endforeach
@endif
    @forelse($topics as $topic)
    <x-topic :topic="$topic"  />
    @empty
    <div class="row">
        <p class="alert alert-danger">There are no topics for your query</p>
    </div>
    @endforelse
    <br>
        {{$topics->links()}}
</div>

@endsection
