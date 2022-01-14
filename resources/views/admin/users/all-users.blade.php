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
                <div class="card-header">
                    Users
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Since</th>
                            <th class="text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="text-center">{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->username}}</td>
                            <td>{{$user->created_at->format('d.m.Y H:i')}}</td>
                            <td class="td-actions text-right">
                                <a role="button" href="{{route('admin.show.users', ['user'=>$user])}}" rel="tooltip" class="btn btn-info">
                                    View User
                                </a>

                            </td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>
    @endsection

@section('scripts')
    <script type="text/javascript">

    </script>
    @endsection
