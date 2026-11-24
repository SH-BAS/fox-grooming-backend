@extends('layouts.admin')

@section('title', 'Add Admin')

@section('content')
    <style>
        .premium-panel {
            position: relative; isolation: isolate; overflow: hidden;
            background: linear-gradient(145deg, rgba(255, 0, 0, 0.12) 0%, rgba(0, 0, 0, 0.3) 40%, rgba(18, 18, 18, 0.98) 100%), linear-gradient(180deg, #1a1a1a 0%, #111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22); border-left: 3px solid #ff0000; border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), inset 0 0 40px rgba(255, 0, 0, 0.04);
        }
        .premium-panel::before {
            content: ""; position: absolute; inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.16), transparent 55%);
            pointer-events: none; z-index: -1;
        }
        .premium-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 22px 24px 0; }
        .premium-header h4 { margin: 0; color: #fff; font-weight: 700; }
        .premium-body { padding: 22px 24px 24px; }
        .premium-panel .form-label { color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 13px; margin-bottom: 6px; }
        .premium-panel .form-control, .premium-panel select.form-control {
            background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.08); color: #fff; border-radius: 10px; min-height: 44px;
        }
        .premium-panel .form-control:focus, .premium-panel select.form-control:focus {
            background: rgba(0, 0, 0, 0.45); border-color: rgba(255, 0, 0, 0.55); box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15); color: #fff;
        }
        .premium-panel select.form-control option { background: #1a1a1a; color: #fff; }
        .premium-panel .form-check-label { color: rgba(255, 255, 255, 0.75); }
        .premium-hint { color: rgba(255, 255, 255, 0.4); font-size: 12px; margin-top: 6px; }
        .premium-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .premium-actions .btn { border-radius: 10px; min-width: 140px; box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25); }
    </style>

    <div class="row">
        <div class="col-xl-8">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Add Admin</h4>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body">
                    <form action="{{ route('admin.admins.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-control" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Franchise (Optional)</label>
                            <select name="franchise_id" class="form-control">
                                <option value="">-- No Franchise (Super Admin) --</option>
                                @foreach($franchises as $franchise)
                                    <option value="{{ $franchise->id }}" @selected(old('franchise_id') == $franchise->id)>{{ $franchise->name }}</option>
                                @endforeach
                            </select>
                            <div class="premium-hint">Required for Branch Managers.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Admin Name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@gmail.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="min 6 chars" required>
                        </div>
                        <div class="form-check custom-checkbox mb-3">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_check" checked>
                            <label class="form-check-label" for="is_active_check">Active</label>
                        </div>
                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Admin</button>
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
