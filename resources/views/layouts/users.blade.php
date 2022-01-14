<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{$settings['description']}}">
    <meta name="keywords" content="{{$settings['keywords']}}">
    <meta name="author" content="{{$settings['author']}}">
    <title>{{config('app.name')}} - @yield('title')</title>
    <link rel="icon" href="{{asset('images/defaults/favicon.ico')}}">
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/user_style.css')}}">
    @yield('styles')

</head>
<body class="bg_greyish">

@include('layouts.users_nav')
@yield('content')

<footer>
    <div class="container-fluid bg-dark p-4">
        <p class="text-center text-white">{{config('app.name')}} - all rights reserved</p>
    </div>
</footer>
    <!-- JavaScript Bundle with Popper -->
    <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/my-app.js')}}"></script>
@auth
<script>

    function updateUnreadMessagesCount(){
        let msg_count_placeholder=document.getElementById('navbar_messages_count');

        axios.get('/messages-count').then((data)=>{
            if(data.data.unread_messages_count){
                msg_count_placeholder.textContent=data.data.unread_messages_count;
                msg_count_placeholder.classList.remove('d-none');
            }
        });
    }

    updateUnreadMessagesCount();
    
</script>
@endauth

@yield('scripts')

</body>
</html>