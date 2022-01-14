@if(session()->has($key))
<div class="alert alert-success">
   {{session()->get($key, "Success")}}
</div>
@endif