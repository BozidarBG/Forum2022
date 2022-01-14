@extends('layouts.users')

@section('title')
profile
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    <style type="text/css">
        .nounderline, .violet{
            color: #7c4dff !important;
        }
        .btn-dark {
            background-color: #7c4dff !important;
            border-color: #7c4dff !important;
        }
        .btn-dark .file-upload {
            /*width: 100%;*/
            padding: 10px 0px;
            position: absolute;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        .profile-img{
            width: 200px;
            height: 200px;

        }
    </style>
@endsection

@section('content')

<div class="container">
@include('user.partials.confirmation-modal')
    <div class="card my-5">
        <div class=" row p-2">
            <x-form-success key="profile_updated" />
            <div class="col-md-4 col-xs-12">
                <div class="text-center" >
                        <img src="{{asset(auth()->user()->getAvatar())}}" class="profile-img" alt="...">
                        <div class="carda-body">
                            <x-form-errors name="avatar"/>
                            <h5 class="card-title text-center">Profile Photo</h5>
                            <div class="text-center">
                                <div class="btn btn-dark">
                                    <input type="file" class="file-upload" id="file-upload"
                                        name="image" accept="image/*">
                                    Upload New Photo
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <form action="{{route('users.delete.profile.avatar')}}" method="post" id="deleteAvatarForm">
                                    @csrf
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationModal" id="deleteAvatarBtn">Delete profile photo</button>
                                </form>

                            </div>
                        </div>
                    
                </div>
                <!-- The Modal -->
                <div class="modal" id="croopModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Crop Image And Upload</h4>
                                <button type="button" class="close" id="closeModal" data-dismiss="modal">&times;</button>
                            </div>
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div id="resizer"></div>
                                <button class="btn rotate float-lef" data-deg="90" >
                                    <i class="fas fa-undo"></i></button>
                                <button class="btn rotate float-right" data-deg="-90" >
                                    <i class="fas fa-redo"></i></button>
                                <hr>
                                <button class="btn btn-block btn-dark" id="upload" >
                                    Crop And Upload</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End  Modal -->

            </div>
            <div class="col-md-4 col-xs-12">
                <div class="">
                    <div class="card-header">
                        <h3>Change your password</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('users.update.password')}}" method="post">
                            @csrf
                        <div class="mb-3">
                            <label class="form-label">Old Password</label>
                            <x-form-errors name="old_password"/>
                            <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Old password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password (must contain at least one number, one capital letter, one lower case letter and one special character, minimum 8 characters</label>
                            <x-form-errors name="password"/>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Your new password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Repeat Password</label>
                            <x-form-errors name="password_confirmation"/>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Repeat new password">
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-info">Change Password</button>
                        </div>
                        </form>
                    </div>
                    
                </div>
            </div>       
            <div class="col-md-4 col-xs-12">
                <div class="">
                        <div class="card-header">
                            <h3>Change About Info</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('users.update.about')}}" method="post">
                                @csrf
                            <div class="mb-3">
                                <label class="form-label">Contact Email</label>
                                <x-form-errors name="contact_email"/>
                                <input type="email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" placeholder="Type your contact email here or leave it blank" value="{{$profile->public_email}}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Your Website</label>
                                <x-form-errors name="website"/>
                                <input type="text" name="website" class="form-control @error('website') is-invalid @enderror" placeholder="Your website" value="{{$profile->website}}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <x-form-errors name="location"/>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" placeholder="City or country you live in" value="{{$profile->location}}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Something about you</label>
                                <x-form-errors name="about"/>
                                <textarea class="form-control @error('about') is-invalid @enderror" name="about" rows="3">{{$profile->about}}</textarea>
                            </div>
                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-warning">Update Profile</button>
                            </div>
                            </form>
                        </div>
                        
                    </div>
                </div>       
            </div>                 
        </div>
        <div class="row">
            <div class="card p-4">
            <h5>Need a break? Delete your profile. (Please, note that your topics and comments will stay on our website for other people to see)</h5>
            <form action="{{route('users.delete.profile')}}" method="post">
                @csrf
                <div class=" mt-3">
                    <button type="submit" class="btn btn-danger">Delete Profile</button>
                </div>
            </form>
            </div>
           
        </div>
    </div>
</div>
<div class="container">
    <h1 class="text-center fw-bold text-primary my-3">{{$page_name}}</h1>
    <div class="row my-4 ">
        <div class="col-md-2 col-sm-3 col-3 f_center">
            Avatar
        </div>
        <div class="col-md-5 col-sm-6 col-6">
            Topics
        </div>
        <div class="col-md-2 col-sm-3 col-3  f_center">
            Category
        </div>
        <div class="col-md-1 f_center  d_none_small">
            Likes
        </div>
        <div class="col-md-1 f_center d_none_small">
            Comments
        </div>
        <div class="col-md-1 f_center  d_none_small">
            Views
        </div>
    </div>

    @foreach($topics as $topic)
    <x-topic :topic="$topic"  />
    
    @endforeach
    <br>
    {{$topics->links()}}
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<!--
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script>
    let url="{{ url('/update-profile-avatar') }}";
    let set={
                width: 200,
                height: 200,
                type: 'square'
            };
        const updateAvatar=()=>{
            location.reload();
        }            
new ImageUploader(url,set, updateAvatar);
/*
const confirmDelete=()=>{
    let form_to_be_deleted=e.target.closest('form');
           document.getElementById('submit_modal').addEventListener('click', ()=>{
               console.log(form_to_be_deleted)
                form_to_be_deleted.submit();
            });
}


new Listener('click', 'submit_modal', 'id', confirmDelete)
*/
function deleteProfileAvatar() {
    const delBtn=document.getElementById('deleteAvatarBtn');
    if(delBtn){
        delBtn.addEventListener('click',  (e)=> {
            e.preventDefault();
            const msg="<p class='text-danger'>"+"Are you sure that you want to delete profile photo?"+"</p>";
            document.getElementById('modal-confirmation-body').innerHTML=msg;
            document.getElementById('submit_modal').addEventListener('click', function () {
                document.getElementById('deleteAvatarForm').submit();
            })
        })
    }
}

deleteProfileAvatar();


</script>
@endsection