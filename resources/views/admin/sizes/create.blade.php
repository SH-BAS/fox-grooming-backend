@extends('layouts.admin')

@section('title', 'Add Size')

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
        .premium-panel .form-control {
            background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.08); color: #fff; border-radius: 10px; min-height: 44px;
        }
        .premium-panel .form-control:focus {
            background: rgba(0, 0, 0, 0.45); border-color: rgba(255, 0, 0, 0.55); box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15); color: #fff;
        }
        .premium-panel .form-control::placeholder { color: rgba(255, 255, 255, 0.35); }
        .premium-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .premium-actions .btn { border-radius: 10px; min-width: 140px; box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25); }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Add Size</h4>
                    <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body">
                    <form action="{{ route('admin.sizes.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Size Name (e.g. Small, Medium, Large)" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Weight From (kg)</label>
                                <input type="number" step="0.01" name="weight_from" class="form-control">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Weight To (kg)</label>
                                <input type="number" step="0.01" name="weight_to" class="form-control">
                            </div>
                        </div>
                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Size</button>
                            <a href="{{ route('admin.sizes.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
