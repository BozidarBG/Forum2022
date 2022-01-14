@extends('admin.partials.admin-layout')
@section('styles')
    <style>
        .bg-def{
            background-color:#1e1e2f;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">User Info</h5>
                </div>
                @include('admin.partials.success-errors')
                <div class="card-body">
                    <form method="post" action="{{route('admin.update.user')}}" id="edit-user-form">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="row">
                            <div class="col-md-4 pr-md-1">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" disabled class="text-white form-control" value="{{$user->name}}">
                                </div>
                            </div>
                            <div class="col-md-4 px-md-1">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" disabled class="text-white form-control"  value="{{$user->username}}">
                                </div>
                            </div>
                            <div class="col-md-4 pl-md-1">
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" disabled class="text-white form-control" value="{{$user->email}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-md-1">
                                <div class="form-group">
                                    <label>Joined</label>
                                    <input type="text" disabled class="text-white form-control" value="{{$user->created_at->format('d.m.Y H:i:s')}}">
                                </div>
                            </div>
                            <div class="col-md-4 px-md-1">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" disabled class="text-white form-control"  value="{{$user->profile->country}}">
                                </div>
                            </div>
                            <div class="col-md-4 pl-md-1">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="email" disabled class="text-white form-control" value="{{$user->profile->city}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pr-md-1">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" disabled class="text-white form-control" value={{$user->profile->website}}>
                                </div>
                            </div>
                            <div class="col-md-4 px-md-1">
                                <div class="form-group">
                                    <label>Public email</label>
                                    <input type="text" disabled class="text-white form-control"  value="{{$user->profile->public_email}}">
                                </div>
                            </div>
                            <div class="col-md-4 pl-md-1">
                                <div class="form-group">
                                    <label for="status">Change Role</label>
                                    <select class="form-control bg-dark" id="role" name="role" data-name="{{$user->name}}" data-username="{{$user->username}}">
                                        <option value="0" {{$user->role==0 ?'selected' :''}}>Regular User</option>
                                        <option value="1" {{$user->role==1 ?'selected':''}}>Administrator</option>
                                        <option value="2" {{$user->role==2 ?'selected':''}}>Moderator</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal" id="save-changes">
                        Change permission
                    </button>
                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-user">
                <div class="card-body">
                    <p class="card-text">
                    </p><div class="author">
                        <div class="block block-one"></div>
                        <div class="block block-two"></div>
                        <div class="block block-three"></div>
                        <div class="block block-four"></div>
                        <img src="{{asset($user->getAvatar())}}" width="100px">

                        <h5 class="title">{{$user->name}}</h5>
                        <p class="description">
                            {{$user->getRole()}}
                        </p>
                    </div>
                    <p></p>
                    <div class="card-description">
                        <p>{{$user->profile->about}}</p>
                        <p>Status: {{$user->isBanned() ? 'banned' : 'active'}}</p>
                        <p>No. of bans: {{$user->numberOfBans()}}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @if(!$user->isBanned())
            <div class="card bg-dark">
                <div class="card-header">
                    <h4>Ban this user</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.banned.store')}}" method="post" id="ban-user-form">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Select a reason</label>
                                    <select class="form-control bg-dark" name="reason_select" id="exampleFormControlSelect1">
                                        <option></option>
                                        <option value="Spam">Spam</option>
                                        <option value="Advertising">Advertising</option>
                                        <option value="Insulting">Insulting</option>
                                        <option value="False information">False information</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Ban for</label>
                                    <select class="form-control bg-dark" name="ban_until" id="">
                                        <option value="1">48 hrs</option>
                                        <option value="2">1 week </option>
                                        <option value="3">2 weeks</option>
                                        <option value="4">month</option>
                                        <option value="5">forever</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Or write a reason</label>
                            <textarea class="form-control" name="reason_textarea" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger" id="ban-user" data-toggle="modal" data-target="#confirmationModal">Ban this user</button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="card bg-dark">
                    <div class="card-header">
                        <h4>Unban this user</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.banned.destroy',['user'=>$user->id])}}" method="post" id="unban-user-form">
                            @csrf

                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmationModal" id="unban-user">
                                Remove Ban
                            </button>

                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('admin.partials.confirmation-modal')
@endsection

@section('scripts')
    <script type="text/javascript">
        function updateRole() {
            document.getElementById('save-changes').addEventListener('click', function (event) {
                event.preventDefault();
                const select=document.getElementById('role');
                const select_value=select.value;
                const new_role = select.options[select.selectedIndex].text;
                const name=select.getAttribute('data-name');
                const username=select.getAttribute('data-username');
                const msg="<p class='text-danger'>"+"Are you sure that you want to change status of "+name+"/"+username+" to "+new_role+"?"+"</p>";
                //alert(msg)
                document.getElementById('modal-confirmation-body').innerHTML=msg;
                document.getElementById('submit_modal').addEventListener('click', function () {
                    document.getElementById('edit-user-form').submit();
                })
            })
        }

        function banUser() {
            const ban=document.getElementById('ban-user');
            if(ban){
                ban.addEventListener('click', function (event) {
                    event.preventDefault();
                    const msg="<p class='text-danger'>"+"Are you sure that you want to ban this user?"+"</p>";
                    document.getElementById('modal-confirmation-body').innerHTML=msg;
                    document.getElementById('submit_modal').addEventListener('click', function () {
                        document.getElementById('ban-user-form').submit();
                    })
                })
            }
        }

        function unbanUser() {
            const unban=document.getElementById('unban-user');
            if(unban){
                unban.addEventListener('click', function (event) {
                    event.preventDefault();
                    const msg="<p class='text-danger'>"+"Are you sure that you want to unban this user?"+"</p>";
                    document.getElementById('modal-confirmation-body').innerHTML=msg;
                    document.getElementById('submit_modal').addEventListener('click', function () {
                        document.getElementById('unban-user-form').submit();
                    })
                })
            }
        }
        updateRole();
        banUser();
        unbanUser();
    </script>
@endsection
