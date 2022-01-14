<div>
@if($errors->has($name)) 
    @foreach($errors->get($name) as $message) 
        <p class="text-danger">{{$message}}</p>
    @endforeach
@endif
</div>