@extends('layouts.admin')

@section('title', 'Tax & Fee Settings')

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
        .premium-hint {
            display: flex; align-items: flex-start; gap: 10px; margin: 0 0 18px; padding: 12px 14px;
            border-radius: 12px; background: rgba(230, 57, 70, 0.12); border: 1px solid rgba(255, 0, 0, 0.22);
            color: rgba(255, 255, 255, 0.75); font-size: 13px;
        }
        .premium-hint i { color: #ff4d5a; margin-top: 2px; }
        .premium-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .premium-actions .btn { border-radius: 10px; min-width: 140px; box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25); }
        .fee-card {
            padding: 14px; border-radius: 12px; background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.06); height: 100%;
        }
        .fee-card .icon {
            width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
            background: rgba(255, 0, 0, 0.12); border: 1px solid rgba(255, 0, 0, 0.25); color: #ff4d5a; margin-bottom: 12px;
        }
    </style>

    <div class="row">
        <div class="col-xl-8">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Tax & Fee Settings</h4>
                </div>
                <div class="premium-body">
                    <div class="premium-hint">
                        <i class="fas fa-info-circle"></i>
                        <span>These values apply across bookings for out-of-area travel, rescheduling, and tax calculation.</span>
                    </div>

                    <form action="{{ route('admin.tax.store') }}" method="POST">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="fee-card">
                                    <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <label class="form-label">Out of Area Fee ($)</label>
                                    <input type="number" step="0.01" name="out_of_area_fee" class="form-control"
                                        value="{{ old('out_of_area_fee', $tax->out_of_area_fee ?? 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fee-card">
                                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                    <label class="form-label">Reschedule Fee ($)</label>
                                    <input type="number" step="0.01" name="reschedule_fee" class="form-control"
                                        value="{{ old('reschedule_fee', $tax->reschedule_fee ?? 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fee-card">
                                    <div class="icon"><i class="fas fa-percent"></i></div>
                                    <label class="form-label">Tax Rate (%)</label>
                                    <input type="number" step="0.01" name="tax" class="form-control"
                                        value="{{ old('tax', $tax->tax ?? 0) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
