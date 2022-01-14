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
            @include('admin.partials.confirmation-modal')
            @include('admin.partials.success_top_message')
            <div class="card">
                <div class="card-header">
                    Complaints
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
{{--                            <th>Subject</th>--}}
                            <th>Owner</th>
                            <th>Complained By</th>
                            <th>Reason</th>
                            <th>Created</th>
                            <th>Link</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($complaints as $complaint)
                            <tr>
                                <td class="text-center">{{$complaint->id}}</td>

                                <td><a target="_blank" href="{{route('admin.show.users', $complaint->complaintable->user)}}">{{$complaint->complaintable->user->name}}</a></td>
                                <td><a target="_blank" href="{{route('admin.show.users', $complaint->complaintable->user)}}">{{$complaint->user->name}}</a></td>
                                <td>{{$complaint->reason}}</td>
                                <td>{{$complaint->created_at->format('d.m.Y H:i')}}</td>
                                <td><a target="_blank" href="{{$complaint->link}}">Click</a></td>
                                <td class="td-actions text-center">
                                    <form method="post" action="{{route('admin.complaint.destroy', $complaint)}}" id="{{$complaint->id}}">
                                        @csrf
                                    <button type="submit" class="btn btn-danger delete_row" data-id="{{$complaint->id}}" data-toggle="modal" data-target="#confirmationModal">
                                        Delete
                                    </button>
                                    </form>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                    {{$complaints->links()}}
                </div>
            </div>
        </div>
    </div>

    
@endsection

@section('scripts')
    <script type="text/javascript">




        function deleteComplaint() {
            const deleteBtns=document.getElementsByClassName('delete_row');
            for(let i=0; i<deleteBtns.length; i++){
                deleteBtns[i].addEventListener('click', (e)=>{
                    e.preventDefault();
                    const msg="<p class='text-danger'>"+"Are you sure that you want to delete this complaint?"+"</p>";
                    document.getElementById('modal-confirmation-body').innerHTML=msg;
                    let form_id=e.target.getAttribute('data-id');
                    document.getElementById('submit_modal').addEventListener('click', function () {
                        document.getElementById(form_id).submit();
                    })
                });
            }


            
        }
        deleteComplaint();
    </script>
@endsection

