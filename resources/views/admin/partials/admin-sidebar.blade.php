<div class="sidebar">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
  -->
    <div class="sidebar-wrapper overflow-auto">
        <div class="logo  text-center">

            <a href="{{route('home')}}" class="simple-text logo-normal">
            {{config('app.name')}}
            </a>
        </div>
        <ul class="nav ">
            <li class="{{ request()->route()->named('admin.dashboard') ? 'active' : ''}}">
                <a href="{{route('admin.dashboard')}}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.categories') ? 'active' : ''}}">
                <a href="{{route('admin.categories')}}">
                    <i class="tim-icons icon-atom"></i>
                    <p>Categories</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.tags') ? 'active' : ''}}">
                <a href="{{route('admin.tags')}}">
                    <i class="tim-icons icon-pin"></i>
                    <p>Tags</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.banned.users') ? 'active' : ''}}">
                <a href="{{route('admin.banned.users')}}">
                    <i class="tim-icons icon-bell-55"></i>
                    <p>Banned Users</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.users') ? 'active' : ''}}">
                <a href="{{route('admin.users')}}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>Users</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.comments') ? 'active' : ''}}">
                <a href="{{route('admin.comments')}}">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>Comments</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.complaints') ? 'active' : ''}}">
                <a href="{{route('admin.complaints')}}">
                    <i class="tim-icons icon-align-center"></i>
                    <p>Complaints</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.topics') ? 'active' : ''}}">
                <a href="{{route('admin.topics')}}">
                    <i class="tim-icons icon-world"></i>
                    <p>Topics</p>
                </a>
            </li>
            <li class="{{ request()->route()->named('admin.topics.trashed') ? 'active' : ''}}">
                <a href="{{route('admin.topics.trashed')}}">
                    <i class="tim-icons icon-world"></i>
                    <p>Trashed Topics</p>
                </a>
            </li>

            <li class="{{ request()->route()->named('admin.app.settings') ? 'active' : ''}}  ">
                <a href="{{route('admin.app.settings')}}">
                    <i class="fa fa-cog" ></i>
                    <p>Settings</p>
                </a>
            </li>

        </ul>
    </div>
</div>
