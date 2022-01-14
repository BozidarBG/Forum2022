@extends('layouts.users')

@section('title')
register
@endsection

@section('content')
<div class="container">
    <h2 class="heading-2 my-4">Register</h2>
    <div class="card p-5">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Your Name</label>
                <x-form-errors name="name"/>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}">
            </div>
            <div class="mb-3">
                <label class="form-label">Your Username</label>
                <x-form-errors name="username"/>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{old('username')}}">
                <div class="form-text">You will be known by your username, so shoose wisely! It must be less than 20 characters</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <x-form-errors name="email"/>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <x-form-errors name="password"/>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" name="password">
                <div class="form-text">
                    Your password must be 8 or more characters long, contain letters, numbers and special character, and must not contain spaces or emoji.
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password Confirmation</label>
                <x-form-errors name="password_confirmation"/>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" >
                <div class="form-text">
                    Your password must match with this field
                </div>
            </div>
            <div class="mb-3 form-check">
                <x-form-errors name="agree"/>
                <input type="checkbox" class="form-check-input" name="agree">
                <label class="form-check-label">I have read and I agree with <a href="" data-bs-toggle="modal" data-bs-target="#termsAndConditionsModal">terms and conditions</a></label>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-info">Register</button>
            </div>
            <div class="mb-3">
                Already a a member? No problem. Just click <a href="{{route('login')}}">login </a>and have fun!
            </div>
                
      </form>


            
            <!-- Modal -->
            <div class="modal fade" id="termsAndConditionsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Terms And Conditions</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia facilis magnam suscipit, optio sequi quia ipsa possimus ullam maxime delectus cupiditate pariatur rerum adipisci? Quasi, a sit est illo deserunt ducimus, sint non excepturi numquam voluptates aspernatur laudantium sed expedita eos, omnis ab. Hic iste porro possimus vitae consequuntur doloremque facilis earum? Consequatur molestias ipsam delectus voluptates nemo vitae atque itaque maxime, est dolores distinctio architecto expedita, sed voluptatum ad sint voluptatibus dicta, aspernatur iusto. Consectetur, nihil repellat dolores, soluta amet praesentium illum debitis totam molestias voluptatem corrupti distinctio fuga. At nulla magnam officia quia ex nemo, possimus eius non laborum deleniti velit molestias earum error officiis quod iste esse dicta alias tempora perferendis? Ipsa culpa in odio provident vero, ea deserunt molestiae unde numquam nesciunt corporis labore voluptatibus iusto harum tenetur reiciendis deleniti consequuntur sunt optio, error dicta velit aperiam quibusdam totam? Reprehenderit vel eveniet animi sed quis, quia facere soluta provident ab assumenda dolores. Sapiente ipsam quisquam error deleniti in officiis temporibus mollitia eveniet, culpa expedita a itaque similique dicta perferendis repudiandae obcaecati corrupti, libero numquam, consequatur quis vero sit voluptatem possimus repellat! Perspiciatis ullam, ipsa explicabo quos nisi provident, fugiat quidem nulla animi rerum possimus sequi quis commodi odit vel sed minima repellat voluptatum a blanditiis libero? Adipisci voluptates, voluptatum ex consequatur ab quia libero ducimus quis eligendi facere nobis nulla dolor! Architecto pariatur illum repellat omnis consequuntur ipsa at, labore vero facere excepturi ad dolor enim voluptate reprehenderit? Blanditiis est iure deleniti voluptate fugiat ullam officiis, nemo quae ab doloribus quaerat, tempora quasi rerum dolorum non debitis! Odit, alias perferendis dolorum quis veritatis, omnis ducimus voluptate necessitatibus quidem beatae rerum tempora unde? Magni quis impedit iure sed cumque? Expedita blanditiis deleniti nisi odio sed explicabo veritatis quia. Aperiam libero repellat eum. Facilis similique expedita repellat sapiente?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Modal -->
    </div>
</div>

@endsection