@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Staff Member</h4>
                    <a href="{{ route('admin.staff.index') }}" class="ms-auto btn btn-primary btn-xs sharp shadow"><i
                            class="fas fa-arrow-left"></i></a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-control" disabled>
                                    <option value="{{ $staffRole->id }}" selected>{{ $staffRole->name }}</option>
                                </select>
                                <input type="hidden" name="role_id" value="{{ $staffRole->id }}">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" class="form-control">
                                @if($staff->getAttributes()['image'] ?? false)
                                    <div class="mt-2">
                                        <img src="{{ $staff->image }}" alt="Staff" style="max-height: 50px;" class="rounded">
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-8 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Password (leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-check custom-checkbox mb-3">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_check"
                                {{ $staff->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_check">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Staff</button>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection