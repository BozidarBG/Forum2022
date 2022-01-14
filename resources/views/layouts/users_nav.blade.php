<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container ">
        <a class="navbar-brand " href="{{route('home')}}"><img src="{{asset('images/defaults/logo_sm.png')}}" class="logo_sm"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
              @auth
              <li class="nav-item">
              <a class="nav-link" href="{{route('users.create-new-topic')}}">Create New Topic</a>
              </li>
              @endauth
          </ul>
          <form class="d-flex mob_margin" method="get" action="{{route('search')}}">
            
              <input class="form-control me-2 input_w" type="search" name="title" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
          </form>
        @guest
          <a type="button" href="{{route('login')}}" class="btn btn-success m-3">Log in</a>
          <a type="button" href="{{route('register')}}" class="btn btn-info m-2">Register</a>
        @endguest
        @auth
        <div class="d-flex justify-content-center">
          <a href="{{route('show.messenger')}}" class="navbar_messages mx-4"><i class="bi bi-chat-square-dots"></i><span class="d-none" id="navbar_messages_count" ></span></a>
          <div class="dropdown">
              <img src="{{asset(auth()->user()->getAvatar())}}" class="avatar_navbar" alt="">
              <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                {{auth()->user()->name}}
              </button>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                <li><a class="dropdown-item" href="{{route('users.my-profile')}}">Go to profile</a></li>
                @if(auth()->user()->isAdmin())
                <li><a class="dropdown-item" href="{{route('admin.dashboard')}}">Admin Dashboard</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                              {{ __('Logout') }}
                  </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

              </ul>
            </div>
            @endauth
          </div>
      </div>
    </div>
</nav>