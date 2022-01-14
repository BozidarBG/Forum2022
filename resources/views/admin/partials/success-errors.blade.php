@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if($msg=session()->get('success'))
@php session()->forget('success') @endphp
<div class="bg-success p-2 text-dark">
    {{$msg}}
</div>
@endif
