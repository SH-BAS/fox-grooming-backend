@extends('layouts.web')

@section('title', 'Register | Fox Grooming')

@section('content')
<section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5" data-bg-img="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1920&q=80">
    <div class="container pt-70 pb-20">
        <!-- <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-white">Register</h2>
                </div>
            </div>
        </div> -->
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="border-1px p-30 mb-0">
                    <h3 class="text-black mt-0 pt-5">Create an Account</h3>
                    <hr>
                    <p>Register to manage your pets and bookings.</p>
                    <form action="{{ route('web.register') }}" method="POST" class="mt-20">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="name">Full Name</label>
                                <input id="name" name="name" class="form-control" type="text" required value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="email">Email Address</label>
                                <input id="email" name="email" class="form-control" type="email" required value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="phone">Phone Number</label>
                                <input id="phone" name="phone" class="form-control" type="text" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="password">Password</label>
                                <input id="password" name="password" class="form-control" type="password" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" class="form-control" type="password" required>
                            </div>
                        </div>
                        <div class="form-group mt-10">
                            <button type="submit" class="btn btn-dark btn-theme-colored btn-block mt-10">Register</button>
                        </div>
                        <div class="clear text-center pt-10">
                            <p>Already have an account? <a href="{{ route('web.login') }}" class="text-theme-colored">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
