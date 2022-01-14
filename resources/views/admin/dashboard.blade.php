@extends('admin.partials.admin-layout')

@section('content')


    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card card-tasks">
                <div class="card-header ">
                    <h6 class="title d-inline">Complaints</h6>
                    <p class="card-category d-inline">Total complaints: {{$complaints_count}}</p>
                    
                </div>
                <div class="card-body ">
                    <div class="table-full-width table-responsive">
                        <table class="table">
                            <tbody>
                                @forelse($complaints as $complaint)
                            <tr>
                                <td>
                                    <p class="title">Complained By: {{$complaint->user->username}}</p>
                                    <p class="text-muted">Accused: {{$complaint->complaintable->user->name}}</p>
                                    <p class="text-muted">Created: {{$complaint->created_at->diffForHumans()}}</p>
                                </td>
                                <td class="td-actions text-right">
                                    <a target="_blank" href="{{$complaint->link}}"  class="btn btn-success" >
                                        Go to
                                    </a>
                                </td>
                            </tr>
                                @empty
                            <tr>
                                <td>There are no complaints</td>
                            </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card card-tasks">
                <div class="card-header ">
                    <h6 class="title d-inline">Topics</h6>
                    <p class="card-category d-inline">Total topics: {{$topics_count}}</p>
                    
                </div>
                <div class="card-body ">
                    <div class="table-full-width table-responsive">
                        <table class="table">
                            <tbody>
                                @forelse($topics as $topic)
                            <tr>
                                <td>
                                    <p class="title">Title: {{$topic->title}}</p>
                                    <p class="text-muted">Created by: {{$topic->user->username}}</p>
                                    <p class="text-muted">Created: {{$topic->created_at->diffForHumans()}}</p>
                                </td>
                                <td class="td-actions text-right">
                                    <a target="_blank" href="{{route('users.show.topic', ['slug'=>$topic->slug])}}"  class="btn btn-primary" >
                                        Go to
                                    </a>
                                </td>
                            </tr>
                                @empty
                            <tr>
                                <td>There are no topics</td>
                            </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
                <div class="card card-tasks">
                    <div class="card-header ">
                        <h6 class="title d-inline">Users</h6>
                        <p class="card-category d-inline">Total users: {{$users_count}}</p>
                        
                    </div>
                    <div class="card-body ">
                        <div class="table-full-width table-responsive">
                            <table class="table">
                                <tbody>
                                    @forelse($users as $user)
                                <tr>
                                    <td>
                                        <p class="title">Username: {{$user->username}}</p>
                                        <p class="text-muted">Name: {{$user->name}}</p>
                                        <p class="text-muted">Created: {{$user->created_at->diffForHumans()}}</p>
                                    </td>
                                    <td class="td-actions text-right">
                                        <a target="_blank" href="{{route('admin.show.users', $user)}}"  class="btn btn-warning">
                                            Go to
                                        </a>
                                    </td>
                                </tr>
                                    @empty
                                <tr>
                                    <td>There are no users</td>
                                </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card card-tasks">
                    <div class="card-header ">
                        <h6 class="title d-inline">Banned Users</h6>
                        <p class="card-category d-inline">Total: {{$banned_users_count}}</p>
                        
                    </div>
                    <div class="card-body ">
                        <div class="table-full-width table-responsive">
                            <table class="table">
                                <tbody>
                                @forelse($banneds as $banned)
                                <tr>
                                    <td>
                                        <p class="title">Username: {{$banned->user->username}}</p>
                                        <p class="text-muted">Name: {{$banned->user->name}}</p>
                                        <p class="text-muted">Created: {{$banned->created_at->diffForHumans()}}</p>
                                        <p class="text-muted">Reason: {{$banned->reason}}</p>
                                    </td>
                                    <td class="td-actions text-right">
                                        <a target="_blank" href="{{route('admin.show.users', $banned->user)}}"  class="btn btn-default">
                                            Go to
                                        </a>
                                    </td>
                                </tr>
                                    @empty
                                <tr>
                                    <td>There are no users</td>
                                </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
