@extends('layouts.admin')

@section('page-title', 'Notifications')

@section('content')
    <style>
        .an-page { margin: 0 20px 24px; }

        .an-hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .an-hero h2 {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
            color: #111827;
        }

        .an-hero p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .an-panel {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .an-item {
            display: flex;
            gap: 14px;
            padding: 16px 18px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            transition: background 0.2s ease;
        }

        .an-item:last-child { border-bottom: none; }
        .an-item:hover { background: #fef2f2; }

        .an-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(230, 57, 70, 0.1);
            color: #e63946;
            flex-shrink: 0;
        }

        .an-content { flex: 1; min-width: 0; }

        .an-title {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 700;
            color: #111827;
        }

        .an-body {
            margin: 0;
            color: #4b5563;
            font-size: 13px;
            line-height: 1.5;
        }

        .an-meta {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            font-size: 12px;
            color: #9ca3af;
        }

        .an-badge {
            display: inline-flex;
            padding: 3px 8px;
            border-radius: 999px;
            background: rgba(230, 57, 70, 0.1);
            color: #e63946;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .an-empty {
            text-align: center;
            padding: 52px 20px;
            color: #9ca3af;
        }

        .an-pagination {
            padding: 12px 16px;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafafa;
        }
    </style>

    <div class="an-page">
        <div class="an-hero">
            <div>
                <h2>Notifications</h2>
                <p>All admin alerts. Opening this page marks them as read.</p>
            </div>
        </div>

        <div class="an-panel">
            @forelse($notifications as $notification)
                <div class="an-item">
                    <div class="an-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="an-content">
                        <h5 class="an-title">{{ $notification->title }}</h5>
                        <p class="an-body">{{ $notification->body }}</p>
                        <div class="an-meta">
                            <span class="an-badge">{{ $notification->type ?? 'notice' }}</span>
                            @if($notification->booking_id)
                                <a href="{{ route('admin.bookings.show', $notification->booking_id) }}">
                                    Booking #{{ $notification->booking_id }}
                                </a>
                            @endif
                            <span>{{ $notification->created_at?->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="an-empty">
                    <i class="fas fa-bell-slash fa-2x mb-3 d-block"></i>
                    No notifications yet.
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="an-pagination">
                    {!! $notifications->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection
