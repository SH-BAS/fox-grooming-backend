@extends('layouts.staff')

@section('page-title', 'Change Password')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary py-3">
                    <h4 class="card-title mb-0 ">Change Password</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('staff.profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label font-weight-bold">Current Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    id="current_password" name="current_password" required
                                    placeholder="Enter current password">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label font-weight-bold">New Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                    id="new_password" name="new_password" required placeholder="Enter new password">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye" id="new_password_icon"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label font-weight-bold">Confirm New Password
                                <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-check-double"></i></span>
                                <input type="password" class="form-control" id="new_password_confirmation"
                                    name="new_password_confirmation" required placeholder="Confirm new password">
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-info py-2 px-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <span class="small font-weight-bold">Password Requirements:</span>
                            </div>
                            <ul class="mb-0 mt-1 small">
                                <li>Minimum 8 characters</li>
                                <li>Must match confirmation password</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endpush