@extends('layouts.web')

@section('title', 'Login | Fox Grooming')

@section('content')
<section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5" data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80" style="background-image: url(&quot;https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80&quot;);">
    <div class="container pt-70 pb-20">
        <!-- <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-white">Login</h2>
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
                    <h3 class="text-theme-black mt-0 pt-5">Login</h3>
                    <hr>
                    <p>Enter your credentials to access your account.</p>
                    <form action="{{ route('web.login') }}" method="POST" class="mt-20">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="email">Email</label>
                                <input id="email" name="email" class="form-control" type="email" required value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="password">Password</label>
                                <input id="password" name="password" class="form-control" type="password" required>
                            </div>
                        </div>
                        <div class="checkbox pull-left mt-15">
                        <a href="{{ route('web.password.request') }}" class="text-theme-colored">Forgot Password?</a>
                        </div>
                        <div class="form-group pull-right mt-10">
                            <button type="submit" class="btn btn-dark btn-theme-colored btn-sm mt-10">Login</button>
                        </div>
                        <div class="text-center pt-10">
                            <p>Don't have an account? <a href="{{ route('web.register') }}" class="text-theme-colored">Register Now</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
