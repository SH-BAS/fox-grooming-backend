@extends('layouts.web')

@section('title', 'My Profile | Fox Grooming')

@section('content')
    <section class="inner-header divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white">My Profile</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="{{ route('web.profile.index') }}" class="list-group-item active">My Profile</a>
                        <a href="{{ route('web.pets.index') }}" class="list-group-item">Pet Management</a>
                        <a href="{{ route('web.user.bookings.index') }}" class="list-group-item">My Bookings</a>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="list-group-item text-danger">Logout</a>
                    </div>
                    <form id="logout-form" action="{{ route('web.logout') }}" method="POST" style="display: none;">@csrf
                    </form>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="border-1px p-30 mb-30">
                                <h3 class="text-theme-colored mt-0">Update Profile</h3>
                                <hr>
                                <form action="{{ route('web.profile.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <img src="{{ $user->image }}" alt="{{ $user->name }}"
                                                class="img-thumbnail mb-10" style="max-height: 150px; border-radius: 10px">
                                            <div class="form-group">
                                                <label for="image">Change Image</label>
                                                <input type="file" name="image" id="image" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="name">Full Name</label>
                                                <input type="text" name="name" id="name" class="form-control"
                                                    value="{{ $user->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email (Cannot be changed)</label>
                                                <input type="email" class="form-control" value="{{ $user->email }}"
                                                    disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Phone Number</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ $user->phone }}">
                                            </div>
                                            <button type="submit" class="btn btn-theme-colored btn-sm mt-10">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="border-1px p-30">
                                <h3 class="text-theme-colored mt-0">Update Password</h3>
                                <hr>
                                <form action="{{ route('web.profile.password') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="current_password">Current Password</label>
                                            <input type="password" name="current_password" id="current_password"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="password">New Password</label>
                                            <input type="password" name="password" id="password" class="form-control"
                                                required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-sm mt-10">Change Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection