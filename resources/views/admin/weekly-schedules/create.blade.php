@extends('layouts.admin')

@section('page-title', $isEditWeek ? 'Edit Weekly Schedule' : 'Set Weekly Schedule')

@section('content')
    @php
        $isEditWeek = $isEditWeek ?? false;
        $existingSchedules = $existingSchedules ?? collect();
        $selectedFranchiseId = $selectedFranchiseId ?? null;
        $scheduledFranchiseIds = $scheduledFranchiseIds ?? collect();
    @endphp
    <style>
        .ws-page { margin: 0 20px 24px;}

        .ws-hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .ws-hero h2 {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
            color: #111827;
        }

        .ws-hero p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .ws-panel {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .ws-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafafa;
        }

        .ws-panel-header h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
        }

        .ws-panel-body { padding: 20px; }

        .ws-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 8px;
        }

        .ws-input {
            width: 100%;
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 10px;
            background: #fff;
            color: #111827;
            padding: 11px 14px;
            font-size: 14px;
            min-height: 44px;
        }

        .ws-input:focus {
            outline: none;
            border-color: rgba(230, 57, 70, 0.55);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.12);
        }

        .ws-table {
            width: 100%;
            margin: 0;
        }

        .ws-table thead th {
            background: linear-gradient(90deg, #e63946, #c62e3a) !important;
            color: #fff !important;
            border: none !important;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding: 12px 14px;
        }

        .ws-table tbody td {
            background: #fff !important;
            color: #374151 !important;
            border-color: rgba(15, 23, 42, 0.06) !important;
            vertical-align: middle;
            padding: 12px 14px;
        }

        .ws-table tbody tr:nth-child(odd) td {
            background: #f9fafb !important;
        }

        .ws-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .form-switch .form-check-input:checked {
            background-color: #e63946;
            border-color: #e63946;
        }
    </style>

    <div class="ws-page">
        <div class="ws-hero">
            <div>
                <h2>{{ $isEditWeek ? 'Edit Weekly Schedule' : 'Set Weekly Schedule' }}</h2>
                <p>{{ $isEditWeek ? 'Update all 7 days for this franchise in one place.' : 'Create business hours for a franchise — Mon to Sun.' }}</p>
            </div>
            <a href="{{ route('admin.weekly-schedules.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="ws-panel">
            <div class="ws-panel-header">
                <h4>{{ $isEditWeek ? 'Franchise week editor' : 'New franchise schedule' }}</h4>
            </div>
            <div class="ws-panel-body">
                <form action="{{ route('admin.weekly-schedules.store') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="ws-label" for="franchise_id">Franchise</label>
                            @if($isEditWeek)
                                <input type="hidden" name="franchise_id" value="{{ $selectedFranchiseId }}">
                                <input type="text" class="ws-input" value="{{ $franchises->firstWhere('id', $selectedFranchiseId)?->name }}" readonly>
                            @else
                                <select name="franchise_id" id="franchise_id" class="ws-input" required>
                                    <option value="">Select Franchise</option>
                                    @foreach($franchises as $franchise)
                                        @php
                                            $alreadyHas = $scheduledFranchiseIds->contains($franchise->id);
                                        @endphp
                                        <option value="{{ $franchise->id }}"
                                            {{ (string) old('franchise_id', $selectedFranchiseId) === (string) $franchise->id ? 'selected' : '' }}
                                            {{ $alreadyHas ? 'disabled' : '' }}>
                                            {{ $franchise->name }}{{ $alreadyHas ? ' (already set — use Edit)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('franchise_id')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table ws-table mb-0">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Status</th>
                                    <th>Opening</th>
                                    <th>Closing</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($days as $key => $val)
                                    @php
                                        $existing = $existingSchedules->get($key);
                                        if (old()) {
                                            $isOpen = old("weekly_schedules.$key.is_open") == '1';
                                        } elseif ($existing) {
                                            $isOpen = (bool) $existing->is_open;
                                        } else {
                                            $isOpen = true;
                                        }
                                        $openVal = $existing && $existing->opening_time
                                            ? date('H:i', strtotime($existing->opening_time))
                                            : '09:00';
                                        $closeVal = $existing && $existing->closing_time
                                            ? date('H:i', strtotime($existing->closing_time))
                                            : '18:00';
                                    @endphp
                                    <tr>
                                        <td><strong style="color:#111827;">{{ $val }}</strong></td>
                                        <td>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input day-toggle" type="checkbox"
                                                    name="weekly_schedules[{{ $key }}][is_open]"
                                                    value="1" id="open_{{ $key }}"
                                                    {{ $isOpen ? 'checked' : '' }}>
                                                <label class="form-check-label" for="open_{{ $key }}" style="color:#374151;">Open</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="time" name="weekly_schedules[{{ $key }}][opening_time]"
                                                class="ws-input time-input-{{ $key }}"
                                                value="{{ old("weekly_schedules.$key.opening_time", $openVal) }}"
                                                {{ $isOpen ? '' : 'disabled' }}
                                                style="{{ $isOpen ? '' : 'opacity:0.5' }}">
                                        </td>
                                        <td>
                                            <input type="time" name="weekly_schedules[{{ $key }}][closing_time]"
                                                class="ws-input time-input-{{ $key }}"
                                                value="{{ old("weekly_schedules.$key.closing_time", $closeVal) }}"
                                                {{ $isOpen ? '' : 'disabled' }}
                                                style="{{ $isOpen ? '' : 'opacity:0.5' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="ws-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            {{ $isEditWeek ? 'Update Weekly Schedule' : 'Save Weekly Schedule' }}
                        </button>
                        <a href="{{ route('admin.weekly-schedules.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.day-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const day = this.name.match(/\[(.*?)\]/)[1];
            document.querySelectorAll(`.time-input-${day}`).forEach(input => {
                input.disabled = !this.checked;
                input.style.opacity = this.checked ? '1' : '0.5';
            });
        });
    });
});
</script>
@endpush
