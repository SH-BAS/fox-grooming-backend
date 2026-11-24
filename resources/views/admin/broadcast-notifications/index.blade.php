@extends('layouts.admin')

@section('page-title', 'Broadcast Notifications')

@section('content')
    <style>
        .bc-page { margin: 0 20px 24px; }

        .bc-hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .bc-hero h2 {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.02em;
        }

        .bc-hero p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .bc-panel {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .bc-table {
            width: 100%;
            margin: 0;
        }

        .bc-table thead th {
            background: linear-gradient(90deg, #e63946, #c62e3a) !important;
            color: #fff !important;
            border: none !important;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding: 12px 16px;
            white-space: nowrap;
        }

        .bc-table tbody td {
            background: #fff !important;
            color: #374151 !important;
            border-color: rgba(15, 23, 42, 0.06) !important;
            vertical-align: middle;
            padding: 14px 16px;
        }

        .bc-table tbody tr:hover td {
            background: #fef2f2 !important;
        }

        .bc-title {
            font-weight: 700;
            color: #111827 !important;
            margin-bottom: 2px;
        }

        .bc-msg {
            color: #6b7280 !important;
            font-size: 12px;
            max-width: 360px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bc-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .bc-badge.all {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }

        .bc-badge.specific {
            background: rgba(124, 58, 237, 0.12);
            color: #7c3aed;
        }

        .bc-stat {
            font-weight: 700;
            color: #111827 !important;
        }

        .bc-empty {
            text-align: center;
            padding: 48px 20px;
            color: #9ca3af;
        }

        .bc-pagination {
            padding: 12px 16px;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafafa;
        }
    </style>

    <div class="bc-page">
        <div class="bc-hero">
            <div>
                <h2>Broadcast Notifications</h2>
                <p>Push announcements to all users with FCM tokens, or a specific customer.</p>
            </div>
            <a href="{{ route('admin.broadcast-notifications.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Broadcast
            </a>
        </div>

        <div class="bc-panel">
            <div class="table-responsive">
                <table class="table bc-table mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Notification</th>
                            <th>Audience</th>
                            <th>Delivered</th>
                            <th>Failed</th>
                            <th>Sent By</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($broadcasts as $broadcast)
                            <tr>
                                <td><strong>#{{ $broadcast->id }}</strong></td>
                                <td>
                                    <div class="bc-title">{{ $broadcast->title }}</div>
                                    <div class="bc-msg">{{ $broadcast->body }}</div>
                                </td>
                                <td>
                                    @if($broadcast->target_type === 'all')
                                        <span class="bc-badge all"><i class="fas fa-users"></i> All users</span>
                                    @else
                                        <span class="bc-badge specific"><i class="fas fa-user-friends"></i> Specific</span>
                                        <div class="bc-msg mt-1">
                                            {{ number_format(is_array($broadcast->user_ids) ? count($broadcast->user_ids) : ($broadcast->user_id ? 1 : 0)) }}
                                            selected user(s)
                                        </div>
                                    @endif
                                    <div class="bc-msg">{{ number_format($broadcast->audience_count) }} recipients</div>
                                </td>
                                <td><span class="bc-stat text-success">{{ number_format($broadcast->sent_count) }}</span></td>
                                <td><span class="bc-stat text-danger">{{ number_format($broadcast->failed_count) }}</span></td>
                                <td>{{ $broadcast->admin?->name ?? '—' }}</td>
                                <td>{{ $broadcast->created_at?->format('M d, Y h:i A') }}</td>
                                <td>
                                    <a href="{{ route('admin.broadcast-notifications.show', $broadcast->id) }}"
                                        class="btn btn-primary btn-xs sharp" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="bc-empty">
                                        <i class="fas fa-bullhorn fa-2x mb-3 d-block"></i>
                                        No broadcasts yet. Create your first push notification.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($broadcasts->hasPages())
                <div class="bc-pagination">
                    {!! $broadcasts->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection
