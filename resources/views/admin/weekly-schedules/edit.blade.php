@extends('layouts.admin')

@section('title', 'Edit Weekly Schedule')

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
        .premium-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .premium-actions .btn { border-radius: 10px; min-width: 140px; box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25); }
    </style>

    <div class="row">
        <div class="col-xl-8">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Edit Weekly Schedule</h4>
                    <a href="{{ route('admin.weekly-schedules.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body">
                    <form action="{{ route('admin.weekly-schedules.update', $weeklySchedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Franchise <span class="text-danger">*</span></label>
                            <select name="franchise_id" class="form-control" required>
                                <option value="">Select Franchise</option>
                                @foreach($franchises as $franchise)
                                    <option value="{{ $franchise->id }}"
                                        {{ old('franchise_id', $weeklySchedule->franchise_id) == $franchise->id ? 'selected' : '' }}>
                                        {{ $franchise->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('franchise_id')
                                <span class="text-danger mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Day</label>
                            <select name="day_of_week" class="form-control" required>
                                @foreach($days as $key => $val)
                                    <option value="{{ $key }}" {{ $weeklySchedule->day_of_week == $key ? 'selected' : '' }}>
                                        {{ $val }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Opening Time</label>
                            <input type="time" name="opening_time" class="form-control"
                                value="{{ $weeklySchedule->opening_time }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Closing Time</label>
                            <input type="time" name="closing_time" class="form-control"
                                value="{{ $weeklySchedule->closing_time }}">
                        </div>
                        <div class="form-check custom-checkbox mb-3">
                            <input type="checkbox" name="is_open" value="1" class="form-check-input" id="is_open_check"
                                {{ $weeklySchedule->is_open ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_open_check">Open</label>
                        </div>
                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Schedule
                            </button>
                            <a href="{{ route('admin.weekly-schedules.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
