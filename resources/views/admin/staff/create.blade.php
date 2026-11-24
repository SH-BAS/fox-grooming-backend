@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add Staff Member</h4>
                    <a href="{{ route('admin.staff.index') }}" class="ms-auto btn btn-primary btn-xs sharp shadow mb-3 "><i
                            class="fas fa-arrow-left"></i></a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
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
                                <input type="text" name="name" class="form-control" placeholder="Staff Name" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="staff@gmail.com"
                                    required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="min 6 chars"
                                    required>
                            </div>
                        </div>
                        <div class="form-check custom-checkbox mb-3">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_check"
                                checked>
                            <label class="form-check-label" for="is_active_check">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Staff</button>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection