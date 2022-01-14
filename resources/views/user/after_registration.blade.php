@extends('layouts.users')

@section('title')
please, check your email
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
    <div class="row my-5">
        <div class="col-12">
            <p class="text-primary">Confirmation Email has been sent to your email addres. Please, confirm in order to continue.</p>
        </div>
    </div>
</div>

@endsection
