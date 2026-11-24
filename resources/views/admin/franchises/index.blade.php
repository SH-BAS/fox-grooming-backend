@extends('layouts.admin')

@section('title', 'Franchises')

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

        .users-filter-tabs a:hover {
            color: #bd0707;
        }

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
            padding: 0;
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
            z-index: 0;
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
            z-index: 0;
        }

        .user-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 0, 0, 0.45);
            box-shadow:
                0 16px 36px rgba(0, 0, 0, 0.5),
                0 0 24px rgba(255, 0, 0, 0.22),
                inset 0 0 50px rgba(255, 0, 0, 0.06);
        }

        .franchise-card-media,
        .franchise-card-body {
            position: relative;
            z-index: 1;
        }

        .franchise-card-media {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: #1a1a1a;
        }

        .franchise-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
        }

        .user-card:hover .franchise-card-media img {
            transform: scale(1.04);
        }

        .franchise-card-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.05) 40%, rgba(20, 20, 20, 0.75) 100%);
            pointer-events: none;
        }

        .franchise-media-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            backdrop-filter: blur(6px);
        }

        .franchise-card-body {
            padding: 16px 16px 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
            background: transparent;
        }

        .user-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .franchise-zone-label {
            margin: 0;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #E63946;
        }

        .user-card-name {
            margin: 0 0 14px;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .user-status-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(52, 211, 153, 0.25);
        }

        .user-status-wrap.inactive {
            border-color: rgba(251, 191, 36, 0.25);
        }

        .user-status-wrap.deleted {
            border-color: rgba(248, 113, 113, 0.25);
        }

        .user-status-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .user-status-label.active {
            color: #34d399;
        }

        .user-status-label.inactive {
            color: #fbbf24;
        }

        .user-status-label.deleted {
            color: #f87171;
        }

        .form-switch .form-check-input {
            width: 2.4em;
            height: 1.25em;
            cursor: pointer;
            background-color: #555;
            border-color: #555;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--fox-red);
            border-color: var(--fox-red);
            box-shadow: 0 0 10px rgba(230, 57, 70, 0.45);
        }

        .user-meta {
            list-style: none;
            padding: 0;
            margin: 0 0 16px;
            flex: 1;
        }

        .user-meta li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
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
            justify-content: flex-start;
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
                    <h4 class="card-title mb-0">Franchises</h4>
                    <a href="{{ route('admin.franchises.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-1"></i> Add Franchise
                    </a>
                </div>
                <div class="card-body">
                    <div class="users-filter-tabs">
                        <a href="{{ route('admin.franchises.index', ['status' => 'active']) }}"
                            class="{{ $status === 'active' ? 'active' : '' }}">
                            Active ({{ $activeCount }})
                        </a>
                        <a href="{{ route('admin.franchises.index', ['status' => 'inactive']) }}"
                            class="{{ $status === 'inactive' ? 'active' : '' }}">
                            Inactive ({{ $inactiveCount }})
                        </a>
                        <a href="{{ route('admin.franchises.index', ['status' => 'deleted']) }}"
                            class="{{ $status === 'deleted' ? 'active' : '' }}">
                            Deleted ({{ $deletedCount }})
                        </a>
                    </div>

                    @if ($franchises->count())
                        <div class="row g-3">
                            @foreach ($franchises as $franchise)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="user-card">
                                        <div class="franchise-card-media">
                                            <img src="{{ $franchise->image }}" alt="{{ $franchise->name }}">
                                            @if($franchise->city || $franchise->state)
                                                <span class="franchise-media-badge">
                                                    {{ collect([$franchise->city, $franchise->state])->filter()->implode(', ') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="franchise-card-body">
                                            <div class="user-card-header">
                                                <p class="franchise-zone-label">{{ $franchise->zone->name ?? 'No Zone' }}</p>

                                                @if ($status === 'deleted')
                                                    <div class="user-status-wrap deleted">
                                                        <span class="user-status-label deleted">Deleted</span>
                                                    </div>
                                                @else
                                                    <div class="user-status-wrap {{ $franchise->is_active ? '' : 'inactive' }}">
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input user-status-toggle" type="checkbox"
                                                                role="switch"
                                                                data-url="{{ route('admin.franchises.status', $franchise->id) }}"
                                                                {{ $franchise->is_active ? 'checked' : '' }}>
                                                        </div>
                                                        <span class="user-status-label {{ $franchise->is_active ? 'active' : 'inactive' }}">
                                                            {{ $franchise->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>

                                            <h5 class="user-card-name">{{ $franchise->name }}</h5>

                                            <ul class="user-meta">
                                                <li>
                                                    <span class="label">Address</span>
                                                    <span class="value">{{ $franchise->address ?: '-' }}</span>
                                                </li>
                                                <li>
                                                    <span class="label">Bookings</span>
                                                    <span class="value">{{ $franchise->bookings_count ?? 0 }}</span>
                                                </li>
                                            </ul>

                                            <div class="user-card-actions">
                                                @if ($status === 'deleted')
                                                    <form action="{{ route('admin.franchises.restore', $franchise->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success" title="Restore franchise">
                                                            <i class="fa fa-undo"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.franchises.show', $franchise->id) }}"
                                                        class="btn btn-info" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.franchises.edit', $franchise->id) }}"
                                                        class="btn btn-primary" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <form action="{{ route('admin.franchises.destroy', $franchise->id) }}" method="POST"
                                                        data-confirm="Are you sure you want to delete this franchise?">
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
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted small">
                                Showing {{ $franchises->firstItem() }} to {{ $franchises->lastItem() }} of {{ $franchises->total() }} results
                            </div>
                            <div>
                                {{ $franchises->links() }}
                            </div>
                        </div>
                    @else
                        <div class="users-empty">
                            <p class="mb-0">No {{ $status }} franchises found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.user-status-toggle').on('change', function () {
                const $toggle = $(this);
                const $label = $toggle.closest('.user-status-wrap').find('.user-status-label');
                const url = $toggle.data('url');
                const wantedActive = $toggle.is(':checked');

                $toggle.prop('disabled', true);

                $.ajax({
                    url: url,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            $label
                                .text(response.is_active ? 'Active' : 'Inactive')
                                .toggleClass('active', response.is_active)
                                .toggleClass('inactive', !response.is_active);

                            if (typeof toastr !== 'undefined') {
                                toastr.success(response.message);
                            }

                            setTimeout(function () {
                                window.location.href = "{{ route('admin.franchises.index') }}?status=" +
                                    (response.is_active ? 'active' : 'inactive');
                            }, 400);
                        } else {
                            $toggle.prop('checked', !wantedActive);
                            if (typeof toastr !== 'undefined') {
                                toastr.error('Unable to update status.');
                            }
                        }
                    },
                    error: function () {
                        $toggle.prop('checked', !wantedActive);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Unable to update status.');
                        }
                    },
                    complete: function () {
                        $toggle.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
