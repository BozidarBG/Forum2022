@extends('admin.partials.admin-layout')
@section('styles')
    <style>
        /*.custom-select{*/
        /*    background-color: #525f7f !important;*/
        /*}*/
        /*    #DataTables_Table_0_info{*/
        /*        color: #9de0f6;*/
        /*    }*/
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                @include('admin.partials.success-errors')
                <div class="card-header">
                    Banned Users
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Reason</th>
                            <th>Banned By</th>
                            <th>Banned Since</th>
                            <th>Banned Until</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banned_users as $banned_user)
                            <tr>
                                <td class="text-center">{{$banned_user->id}}</td>
                                <td>{{$banned_user->user->name}}</td>
                                <td>{{$banned_user->user->username}}</td>
                                <td>{{$banned_user->reason}}</td>
                                <td>{{$banned_user->bannedBy->name}}</td>
                                <td>{{$banned_user->created_at->format('d.m.Y H:i')}}</td>
                                <td>{{$banned_user->until->format('d.m.Y H:i')}}</td>
                                <td class="td-actions text-right">
                                    <a role="button" href="{{route('admin.show.users', ['user'=>$banned_user->user->id])}}" rel="tooltip"
                                       class="btn btn-info btn-sm btn-icon" data-toggle="tooltip" data-placement="top" title="User's profile">
                                        <i class="tim-icons icon-single-02"></i>
                                    </a>
                                    <form method="post" action="{{route('admin.banned.destroy', ['user'=>$banned_user->user->id])}}" style="display: inline-block;" id="{{$banned_user->user->id}}-unban-user-form">
                                        @csrf
                                    <button type="submit" rel="tooltip" class="btn btn-danger btn-sm btn-icon unban"
                                            data-toggle="modal" data-placement="top" title="Unban this user"  data-target="#confirmationModal" data-id="{{$banned_user->user->id}}">
                                        <i class="tim-icons icon-simple-remove"></i>
                                    </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    {{$banned_users->links()}}
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.confirmation-modal')
@endsection

@section('scripts')
    <script type="text/javascript">
        function unban() {
            const unban_btns=document.getElementsByClassName('unban');
            for(let i=0; i<unban_btns.length; i++){
                unban_btns[i].addEventListener('click', function (event) {
                    event.preventDefault();
                    const id=this.getAttribute('data-id');
                    const msg="<p class='text-danger'>"+"Are you sure that you want to unban this user?"+"</p>";
                    document.getElementById('modal-confirmation-body').innerHTML=msg;
                    document.getElementById('submit_modal').addEventListener('click', function () {
                        document.getElementById(id+'-unban-user-form').submit();
                    })
                })
            }

        }

        unban();
    </script>
@endsection
