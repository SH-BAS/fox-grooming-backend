@extends('layouts.web')

@section('title', 'Verify OTP | Fox Grooming')

@section('content')
<section class="inner-header divider parallax layer-overlay overlay-dark-5" data-bg-img="https://images.unsplash.com/photo-1544568100-847a948585b9?auto=format&fit=crop&w=1920&q=80">
    <div class="container pt-70 pb-20">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-white">Email Verification</h2>
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
                    <h3 class="text-theme-colored mt-0 pt-5">OTP Verification</h3>
                    <hr>
                    <p>An OTP has been sent to <strong>{{ $email }}</strong>. Please enter it below to verify your account.</p>
                    <form action="{{ route('web.verify.email') }}" method="POST" class="mt-20">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="token">Enter 6-Digit OTP</label>
                                <input id="token" name="token" class="form-control text-center font-24 letter-space-5" type="text" maxlength="6" required autofocus>
                            </div>
                        </div>
                        <div class="form-group mt-10">
                            <button type="submit" class="btn btn-dark btn-theme-colored btn-block mt-10">Verify OTP</button>
                        </div>
                    </form>
                    
                    <form action="{{ route('web.resend.otp') }}" method="POST" class="mt-10">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <p class="text-center">Didn't receive the OTP? <button type="submit" class="btn btn-link p-0 text-theme-colored" style="vertical-align: baseline;">Resend OTP</button></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
