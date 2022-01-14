@extends('layouts.users')

@section('title')
you are banned
@endsection



@section('content')
@if(auth()->user()->isBanned())
<div class="container mt-5 " style="height:100vh;">
    <div class="row">
        <div class="col-12">
           <h1 class="text-danger">You are banned!!!</h1>
           <p class="text-primary">You can use this application again after: {{$banned->until->format('d.m.Y H:i:d')}}</p>
        </div>

    </div>
</div>
@else
    <script>
        window.location.href = '{{route("home")}}';
    </script>
@endif

@endsection


