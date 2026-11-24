@extends('layouts.admin')

@section('page-title', 'Business Hours')

@section('content')
    <style>
        .ws-page { margin: 0 20px 24px; }

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
            letter-spacing: -0.02em;
        }

        .ws-hero p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .ws-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 16px;
        }

        .ws-card {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .ws-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px;
            background: #fafafa;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }

        .ws-card-header h4 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            color: #111827;
        }

        .ws-card-header .ws-sub {
            margin-top: 3px;
            font-size: 12px;
            color: #6b7280;
        }

        .ws-card-actions {
            display: flex;
            gap: 6px;
            flex-shrink: 0;
        }

        .ws-days {
            padding: 8px 10px 12px;
            flex: 1;
        }

        .ws-day-row {
            display: grid;
            grid-template-columns: 72px 1fr auto;
            gap: 10px;
            align-items: center;
            padding: 10px 10px;
            border-radius: 10px;
        }

        .ws-day-row:nth-child(odd) {
            background: #f9fafb;
        }

        .ws-day-name {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
        }

        .ws-day-hours {
            font-size: 13px;
            color: #4b5563;
            font-weight: 500;
        }

        .ws-day-hours.closed {
            color: #9ca3af;
        }

        .ws-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .ws-badge.open {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .ws-badge.closed {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        .ws-empty {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            text-align: center;
            padding: 56px 20px;
            color: #9ca3af;
        }
    </style>

    <div class="ws-page">
        <div class="ws-hero">
            <div>
                <h2>Business Hours</h2>
                <p>Weekly schedules grouped by franchise — no repeated franchise names.</p>
            </div>
            <a href="{{ route('admin.weekly-schedules.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus me-1"></i> Add Schedule
            </a>
        </div>

        @if ($schedulesByFranchise->count())
            <div class="ws-grid">
                @foreach($schedulesByFranchise as $franchiseId => $daySchedules)
                    @php
                        $franchise = $daySchedules->first()->franchise;
                        $openDays = $daySchedules->where('is_open', true)->count();
                    @endphp
                    <div class="ws-card">
                        <div class="ws-card-header">
                            <div>
                                <h4>{{ $franchise->name ?? 'Unknown Franchise' }}</h4>
                                <div class="ws-sub">{{ $openDays }} open day{{ $openDays === 1 ? '' : 's' }} this week</div>
                            </div>
                            <div class="ws-card-actions">
                                <a href="{{ route('admin.weekly-schedules.editWeek', $franchiseId) }}"
                                    class="btn btn-primary shadow btn-xs sharp" title="Edit week">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('admin.weekly-schedules.destroyWeek', $franchiseId) }}"
                                    method="POST"
                                    data-confirm="Delete the full weekly schedule for this franchise?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Delete week">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="ws-days">
                            @foreach($daySchedules as $schedule)
                                <div class="ws-day-row">
                                    <div class="ws-day-name">
                                        {{ $days[$schedule->day_of_week] ?? strtoupper($schedule->day_of_week) }}
                                    </div>
                                    <div class="ws-day-hours {{ $schedule->is_open ? '' : 'closed' }}">
                                        @if($schedule->is_open)
                                            {{ $schedule->opening_time ? date('h:i A', strtotime($schedule->opening_time)) : '—' }}
                                            –
                                            {{ $schedule->closing_time ? date('h:i A', strtotime($schedule->closing_time)) : '—' }}
                                        @else
                                            Closed
                                        @endif
                                    </div>
                                    <div>
                                        @if($schedule->is_open)
                                            <span class="ws-badge open">Open</span>
                                        @else
                                            <span class="ws-badge closed">Closed</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="ws-empty">
                <i class="fas fa-clock fa-2x mb-3 d-block"></i>
                No weekly schedules found. Add a franchise schedule to get started.
            </div>
        @endif
    </div>
@endsection
