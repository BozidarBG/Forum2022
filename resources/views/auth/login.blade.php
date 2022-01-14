@extends('layouts.users')

@section('title')
login
@endsection
@section('styles')
    <style>
        .content{
            min-height:80vh;
        }
    </style>
@endsection
@section('content')
<div class="container content">
    <h2 class="heading-2 my-4">Login</h2>
    <div class="card p-5">
        <form method="POST" action="{{ route('login') }}">
            @csrf


            <div class="mb-3">

                <label class="form-label">Email address</label>
                <x-form-errors name="email"/>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" aria-describedby="emailHelp">

            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <x-form-errors name="password"/>
                <input type="password" class="form-control @error('password') is-invalid @enderror"  name="password">

            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label">Remember me</label>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-success">Login</button>
            </div>
            <div class="mb-3">
                Not a member yet? No problem. Just click <a href="{{route('register')}}">register </a>and create you profile!
            </div>

      </form>

    </div>
</div>

@endsection
