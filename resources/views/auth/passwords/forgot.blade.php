@extends('layouts.web')

@section('title', 'Forgot Password | Fox Grooming')

@section('content')
<section class="inner-header divider parallax layer-overlay overlay-dark-5" data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80" style="background-image: url(&quot;https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80&quot;);">
    <div class="container pt-70 pb-20">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-white">Forgot Password</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="border-1px p-30 mb-0">
                    <h3 class="text-theme-colored mt-0 pt-5">Reset Your Password</h3>
                    <hr>
                    <p>Enter your email address and we will send a 6-digit code to help you reset your password.</p>
                    <form action="{{ route('web.password.email') }}" method="POST" class="mt-20">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="email">Email Address</label>
                                <input id="email" name="email" class="form-control" type="email" required value="{{ old('email') }}" autofocus>
                            </div>
                        </div>
                        <div class="form-group mt-10">
                            <button type="submit" class="btn btn-dark btn-theme-colored btn-block mt-10">Send 6-Digit Code</button>
                        </div>
                        <div class="text-center pt-10">
                            <p>Remember your password? <a href="{{ route('web.login') }}" class="text-theme-colored">Back to Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
