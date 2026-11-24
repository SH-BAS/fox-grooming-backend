@extends('layouts.admin')

@section('title', 'Zones')

@section('content')
    <style>
        .users-filter-tabs {
            display: flex;
            gap: 28px;
            border-bottom: 1px solid rgba(255, 0, 0, 0.18);
            margin-bottom: 28px;
            padding: 0 4px;
            flex-wrap: wrap;
        }

        .users-filter-tabs a {
            position: relative;
            padding: 12px 0;
            color: rgb(59 55 55 / 55%);
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: color 0.25s ease;
        }

        .users-filter-tabs a:hover,
        .users-filter-tabs a.active {
            color: #bd0707;
        }

        .users-filter-tabs a.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 2px;
            background: linear-gradient(90deg, #ff0000, #E63946, transparent);
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.55);
        }

        .user-card {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(255, 0, 0, 0.14) 0%, rgba(0, 0, 0, 0.35) 42%, rgba(20, 20, 20, 0.95) 100%),
                linear-gradient(180deg, #1a1a1a 0%, #111111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22);
            border-left: 3px solid #ff0000;
            border-radius: 16px;
            padding: 24px 18px 18px;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow:
                0 10px 28px rgba(0, 0, 0, 0.45),
                inset 0 0 40px rgba(255, 0, 0, 0.04),
                0 0 0 1px rgba(255, 255, 255, 0.02);
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .user-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.18), transparent 55%);
            opacity: 0.75;
            pointer-events: none;
            z-index: -1;
        }

        .user-card::after {
            content: "";
            position: absolute;
            top: -40%;
            right: -30%;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255, 0, 0, 0.2), transparent 70%);
            filter: blur(8px);
            pointer-events: none;
            z-index: -1;
        }

        .user-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 0, 0, 0.45);
            box-shadow:
                0 16px 36px rgba(0, 0, 0, 0.5),
                0 0 24px rgba(255, 0, 0, 0.22),
                inset 0 0 50px rgba(255, 0, 0, 0.06);
        }

        .user-card-avatar {
            width: 92px;
            height: 92px;
            margin: 0 auto 18px;
            border-radius: 14px;
            overflow: hidden;
            background: linear-gradient(145deg, #2a2a2a, #171717);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 0, 0, 0.28);
            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.35),
                0 0 16px rgba(255, 0, 0, 0.15);
            color: #ff4d5a;
            font-size: 32px;
        }

        .user-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }

        .user-card-name {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }

        .user-status-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .user-status-label.active {
            color: #34d399;
        }

        .user-status-label.deleted {
            color: #f87171;
        }

        .user-meta {
            list-style: none;
            padding: 10px 12px;
            margin: 0 0 18px;
            flex: 1;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.18));
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .user-meta li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 13px;
        }

        .user-meta li:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .user-meta li:first-child {
            padding-top: 0;
        }

        .user-meta .label {
            color: rgba(255, 255, 255, 0.45);
            font-weight: 500;
        }

        .user-meta .value {
            color: #f3f3f3;
            text-align: right;
            word-break: break-word;
            font-weight: 500;
        }

        .user-card-actions {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding-top: 4px;
        }

        .user-card-actions .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.28);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .user-card-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(255, 0, 0, 0.25);
        }

        .user-card-actions .btn-primary {
            background: linear-gradient(145deg, #ff1f2f, #c62e3a);
        }

        .user-card-actions .btn-info {
            background: linear-gradient(145deg, #4b8dff, #2563eb);
        }

        .user-card-actions .btn-success {
            background: linear-gradient(145deg, #22c55e, #15803d);
        }

        .user-card-actions .btn-danger {
            background: linear-gradient(145deg, #ef4444, #b91c1c);
        }

        .users-empty {
            padding: 50px 20px;
            text-align: center;
            color: var(--text-secondary);
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Service Zones</h4>
                    <a href="{{ route('admin.zones.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-1"></i> Add Zone
                    </a>
                </div>
                <div class="card-body">
                    <div class="users-filter-tabs">
                        <a href="{{ route('admin.zones.index', ['status' => 'active']) }}"
                            class="{{ $status === 'active' ? 'active' : '' }}">
                            Active ({{ $activeCount }})
                        </a>
                        <a href="{{ route('admin.zones.index', ['status' => 'deleted']) }}"
                            class="{{ $status === 'deleted' ? 'active' : '' }}">
                            Deleted ({{ $deletedCount }})
                        </a>
                    </div>

                    @if ($zones->count())
                        <div class="row g-3">
                            @foreach ($zones as $zone)
                                @php
                                    $pointCount = null;
                                    try {
                                        if ($zone->area && $zone->area->getGeometries()->first()) {
                                            $pointCount = $zone->area->getGeometries()->first()->getGeometries()->count();
                                        }
                                    } catch (\Throwable $e) {
                                        $pointCount = null;
                                    }
                                @endphp
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="user-card">
                                        <div class="user-card-avatar">
                                            <i class="fas fa-draw-polygon"></i>
                                        </div>

                                        <div class="user-card-header">
                                            <h5 class="user-card-name">{{ $zone->name }}</h5>
                                            <span class="user-status-label {{ $status === 'deleted' ? 'deleted' : 'active' }}">
                                                {{ $status === 'deleted' ? 'Deleted' : 'Active' }}
                                            </span>
                                        </div>

                                        <ul class="user-meta">
                                            <li>
                                                <span class="label">Boundary Points</span>
                                                <span class="value">
                                                    {{ $pointCount !== null ? $pointCount . ' Points' : 'Invalid Area' }}
                                                </span>
                                            </li>
                                            <li>
                                                <span class="label">Franchises</span>
                                                <span class="value">{{ $zone->franchises_count ?? 0 }}</span>
                                            </li>
                                            <li>
                                                <span class="label">Created</span>
                                                <span class="value">{{ $zone->created_at->format('M d, Y') }}</span>
                                            </li>
                                        </ul>

                                        <div class="user-card-actions">
                                            @if ($status === 'deleted')
                                                <form action="{{ route('admin.zones.restore', $zone->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Restore zone">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.zones.show', $zone->id) }}"
                                                    class="btn btn-info" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.zones.edit', $zone->id) }}"
                                                    class="btn btn-primary" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('admin.zones.destroy', $zone->id) }}" method="POST"
                                                    data-confirm="Are you sure you want to delete this zone?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted small">
                                Showing {{ $zones->firstItem() }} to {{ $zones->lastItem() }} of {{ $zones->total() }} results
                            </div>
                            <div>
                                {{ $zones->links() }}
                            </div>
                        </div>
                    @else
                        <div class="users-empty">
                            <p class="mb-3">No {{ $status }} zones found.</p>
                            @if ($status === 'active')
                                <a href="{{ route('admin.zones.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus me-1"></i> Create Your First Zone
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
