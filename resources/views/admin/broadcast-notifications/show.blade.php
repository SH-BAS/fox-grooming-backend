@extends('layouts.admin')

@section('page-title', 'Broadcast Detail')

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
        }

        .bc-hero p {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .bc-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 14px;
            margin-bottom: 14px;
        }

        @media (max-width: 992px) {
            .bc-grid { grid-template-columns: 1fr; }
        }

        .bc-panel {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            height: 100%;
        }

        .bc-panel-header {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .bc-panel-header h4 {
            margin: 0;
            font-size: 0.98rem;
            font-weight: 700;
            color: #111827;
        }

        .bc-panel-body { padding: 18px; }

        .bc-kpis {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 14px;
        }

        @media (max-width: 640px) {
            .bc-kpis { grid-template-columns: 1fr; }
        }

        .bc-kpi {
            background: #f9fafb;
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 12px;
            padding: 14px;
        }

        .bc-kpi .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            font-weight: 700;
        }

        .bc-kpi .value {
            margin-top: 4px;
            font-size: 1.4rem;
            font-weight: 800;
            color: #111827;
        }

        .bc-meta-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            font-size: 13px;
        }

        .bc-meta-row:last-child { border-bottom: none; }

        .bc-meta-row .k { color: #6b7280; font-weight: 600; }
        .bc-meta-row .v { color: #111827; font-weight: 600; text-align: right; }

        .bc-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #111827;
            margin: 0 0 8px;
        }

        .bc-body {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            white-space: pre-wrap;
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
        }

        .bc-badge.type {
            background: rgba(230, 57, 70, 0.1);
            color: #e63946;
        }

        .bc-badge.all {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }

        .bc-badge.specific {
            background: rgba(124, 58, 237, 0.12);
            color: #7c3aed;
        }

        .bc-badge.read {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .bc-badge.unread {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
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
            padding: 12px 14px;
        }

        .bc-table tbody td {
            background: #fff !important;
            color: #374151 !important;
            border-color: rgba(15, 23, 42, 0.06) !important;
            vertical-align: middle;
            padding: 12px 14px;
        }

        .bc-table tbody tr:hover td { background: #fef2f2 !important; }

        .bc-pagination {
            padding: 12px 14px;
            border-top: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafafa;
        }

        .user-avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff4d5a, #c62e3a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 11px;
            flex-shrink: 0;
        }
    </style>

    <div class="bc-page">
        <div class="bc-hero">
            <div>
                <h2>Broadcast #{{ $broadcast->id }}</h2>
                <p>Notification detail and delivery audience</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.broadcast-notifications.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> New
                </a>
                <a href="{{ route('admin.broadcast-notifications.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="bc-grid">
            <div class="bc-panel">
                <div class="bc-panel-header">
                    <h4>Notification detail</h4>
                    <span class="bc-badge type">{{ $broadcast->type }}</span>
                </div>
                <div class="bc-panel-body">
                    <h3 class="bc-title">{{ $broadcast->title }}</h3>
                    <p class="bc-body">{{ $broadcast->body }}</p>
                </div>
            </div>

            <div class="bc-panel">
                <div class="bc-panel-header">
                    <h4>Send summary</h4>
                </div>
                <div class="bc-panel-body">
                    <div class="bc-kpis">
                        <div class="bc-kpi">
                            <div class="label">Audience</div>
                            <div class="value">{{ number_format($broadcast->audience_count) }}</div>
                        </div>
                        <div class="bc-kpi">
                            <div class="label">Delivered</div>
                            <div class="value" style="color:#059669;">{{ number_format($broadcast->sent_count) }}</div>
                        </div>
                        <div class="bc-kpi">
                            <div class="label">Failed</div>
                            <div class="value" style="color:#dc2626;">{{ number_format($broadcast->failed_count) }}</div>
                        </div>
                    </div>

                    <div class="bc-meta-row">
                        <span class="k">Target</span>
                        <span class="v">
                            @if($broadcast->target_type === 'all')
                                <span class="bc-badge all">All users</span>
                            @else
                                <span class="bc-badge specific">Specific</span>
                            @endif
                        </span>
                    </div>
                    @if($broadcast->target_type === 'specific')
                        <div class="bc-meta-row">
                            <span class="k">Selected users</span>
                            <span class="v" style="text-align:left;max-width:70%;">
                                @forelse($targetUsers as $targetUser)
                                    <div style="margin-bottom:4px;">
                                        {{ $targetUser->name }}
                                        <br><small style="color:#9ca3af;font-weight:500;">{{ $targetUser->email }}</small>
                                    </div>
                                @empty
                                    —
                                @endforelse
                            </span>
                        </div>
                    @endif
                    <div class="bc-meta-row">
                        <span class="k">Sent by</span>
                        <span class="v">{{ $broadcast->admin?->name ?? '—' }}</span>
                    </div>
                    <div class="bc-meta-row">
                        <span class="k">Sent at</span>
                        <span class="v">{{ $broadcast->created_at?->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bc-panel">
            <div class="bc-panel-header">
                <h4>Send audience</h4>
                <span style="color:#6b7280;font-size:12px;">{{ number_format($audience->total()) }} stored notifications</span>
            </div>
            <div class="table-responsive">
                <table class="table bc-table mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Read status</th>
                            <th>Delivered at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($audience as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar-sm">
                                            {{ strtoupper(substr($item->user?->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <strong style="color:#111827;">{{ $item->user?->name ?? 'N/A' }}</strong>
                                    </div>
                                </td>
                                <td>{{ $item->user?->email ?? '—' }}</td>
                                <td><span class="bc-badge type">{{ $item->type }}</span></td>
                                <td>
                                    @if($item->is_read)
                                        <span class="bc-badge read">Read</span>
                                    @else
                                        <span class="bc-badge unread">Unread</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at?->format('M d, Y h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4" style="color:#9ca3af;">
                                    No audience records found for this broadcast.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($audience->hasPages())
                <div class="bc-pagination">
                    {!! $audience->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection
