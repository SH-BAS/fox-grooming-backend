@extends('layouts.web')

@section('title', 'Reset Password | Fox Grooming')

@section('content')
<section class="inner-header divider parallax layer-overlay overlay-dark-5" data-bg-img="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1920&q=80" style="background-image: url(&quot;https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=1920&q=80&quot;);">
    <div class="container pt-70 pb-20">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-white">Reset Password</h2>
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
                    <h3 class="text-theme-colored mt-0 pt-5">Choose a New Password</h3>
                    <hr>
                    <p>Set a new password for <strong>{{ $email }}</strong>.</p>
                    <form action="{{ route('web.password.reset') }}" method="POST" class="mt-20">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="password">New Password</label>
                                <input id="password" name="password" class="form-control" type="password" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" class="form-control" type="password" required>
                            </div>
                        </div>
                        <div class="form-group mt-10">
                            <button type="submit" class="btn btn-dark btn-theme-colored btn-block mt-10">Reset Password</button>
                        </div>
                        <div class="text-center pt-10">
                            <p>Need a new code? <a href="{{ route('web.password.verify.form', ['email' => $email]) }}" class="text-theme-colored">Go back to verification</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
