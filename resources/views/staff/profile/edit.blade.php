@extends('layouts.staff')

@section('page-title', 'Edit Profile')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary py-3">
                    <h4 class="card-title mb-0 ">Edit Profile</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-4 text-center">
                                <div class="profile-image-container position-relative mb-3">
                                    <img src="{{ $staff->image }}" alt="{{ $staff->name }}" id="profile-preview"
                                        class="rounded-circle shadow"
                                        style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff;">
                                    <label for="image"
                                        class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle"
                                        style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; transform: translate(-20%, -20%);">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="image" id="image" class="d-none" onchange="previewImage(this)">
                                </div>
                                <h5 class="mb-0">{{ $staff->name }}</h5>
                                <small class="text-muted">{{ $staff->role->name ?? 'Staff' }}</small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label font-weight-bold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $staff->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label font-weight-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email', $staff->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="phone" class="form-label font-weight-bold">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                    name="phone" value="{{ old('phone', $staff->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label font-weight-bold text-muted">Role</label>
                                <input type="text" class="form-control bg-light" value="{{ $staff->role->name ?? 'Staff' }}"
                                    disabled>
                                <small class="text-muted">Role cannot be changed from profile settings.</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#profile-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection