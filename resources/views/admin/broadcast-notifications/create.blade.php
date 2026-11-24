@extends('layouts.admin')

@section('page-title', 'New Broadcast')

@section('content')
    <style>
        .bc-page { margin: 0 20px 24px;}

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

        .bc-panel {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-left: 3px solid #e63946;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .bc-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafafa;
        }

        .bc-panel-header h4 {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
        }

        .bc-panel-body { padding: 20px; }

        .bc-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 8px;
        }

        .bc-input,
        .bc-select,
        .bc-textarea {
            width: 100%;
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 10px;
            background: #fff;
            color: #111827;
            padding: 11px 14px;
            font-size: 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .bc-textarea { min-height: 130px; resize: vertical; }

        .bc-input:focus,
        .bc-select:focus,
        .bc-textarea:focus {
            outline: none;
            border-color: rgba(230, 57, 70, 0.55);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.12);
        }

        .bc-target-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        @media (max-width: 640px) {
            .bc-target-grid { grid-template-columns: 1fr; }
        }

        .bc-target-card {
            position: relative;
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            background: #f9fafb;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .bc-target-card input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .bc-target-card.active {
            border-color: rgba(230, 57, 70, 0.55);
            background: rgba(230, 57, 70, 0.06);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.08);
        }

        .bc-target-card h5 {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .bc-target-card p {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }

        .bc-hint {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 12px 14px;
            border-radius: 10px;
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.15);
            color: #1e40af;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .bc-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .bc-user-list {
            max-height: 280px;
            overflow-y: auto;
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 12px;
            background: #fff;
            padding: 8px;
        }

        .bc-user-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            margin-bottom: 4px;
            transition: background 0.15s ease;
        }

        .bc-user-row:last-child { margin-bottom: 0; }

        .bc-user-row:hover {
            background: #fef2f2;
        }

        .bc-user-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #e63946;
            flex-shrink: 0;
            cursor: pointer;
        }

        .bc-user-row .bc-user-meta {
            min-width: 0;
            flex: 1;
        }

        .bc-user-row .bc-user-name {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        .bc-user-row .bc-user-email {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .bc-user-row .bc-user-tag {
            font-size: 10px;
            font-weight: 700;
            color: #d97706;
            background: rgba(245, 158, 11, 0.12);
            padding: 3px 8px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .bc-user-toolbar {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }

        .bc-user-toolbar button {
            border: 1px solid rgba(15, 23, 42, 0.1);
            background: #f9fafb;
            color: #374151;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .bc-user-toolbar button:hover {
            background: #fef2f2;
            border-color: rgba(230, 57, 70, 0.35);
            color: #e63946;
        }

        #specificUserWrap { display: none; }
    </style>

    <div class="bc-page">
        <div class="bc-hero">
            <div>
                <h2>New Broadcast</h2>
                <p>Compose a push notification with type <strong>broadcast</strong>.</p>
            </div>
            <a href="{{ route('admin.broadcast-notifications.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> History
            </a>
        </div>

        <div class="bc-panel">
            <div class="bc-panel-header">
                <h4>Compose notification</h4>
            </div>
            <div class="bc-panel-body">
                <div class="bc-hint">
                    <i class="fas fa-info-circle mt-1"></i>
                    <div>
                        <strong>All users</strong> targets only customers with a non-empty FCM token
                        ({{ number_format($tokenUserCount) }} available). Notifications are stored per user and pushed via FCM.
                    </div>
                </div>

                <form action="{{ route('admin.broadcast-notifications.store') }}" method="POST" id="broadcastForm">
                    @csrf

                    <div class="mb-3">
                        <label class="bc-label" for="title">Title</label>
                        <input type="text" name="title" id="title" class="bc-input"
                            value="{{ old('title') }}" placeholder="e.g. Weekend grooming special" required maxlength="255">
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="bc-label" for="body">Message</label>
                        <textarea name="body" id="body" class="bc-textarea" required maxlength="2000"
                            placeholder="Write the notification message...">{{ old('body') }}</textarea>
                        @error('body') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="bc-label">Target audience</label>
                        <div class="bc-target-grid">
                            <label class="bc-target-card {{ old('target_type', 'all') === 'all' ? 'active' : '' }}" id="cardAll">
                                <input type="radio" name="target_type" value="all"
                                    {{ old('target_type', 'all') === 'all' ? 'checked' : '' }}>
                                <h5><i class="fas fa-users text-danger me-1"></i> All users</h5>
                                <p>Everyone with an FCM device token</p>
                            </label>
                            <label class="bc-target-card {{ old('target_type') === 'specific' ? 'active' : '' }}" id="cardSpecific">
                                <input type="radio" name="target_type" value="specific"
                                    {{ old('target_type') === 'specific' ? 'checked' : '' }}>
                                <h5><i class="fas fa-user-friends text-danger me-1"></i> Specific users</h5>
                                <p>Select one or more customers</p>
                            </label>
                        </div>
                        @error('target_type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3" id="specificUserWrap">
                        <label class="bc-label">Select users</label>
                        <div class="bc-user-toolbar">
                            <button type="button" id="selectAllUsers">Select all</button>
                            <button type="button" id="clearAllUsers">Clear all</button>
                        </div>
                        <div class="bc-user-list" id="userCheckboxList">
                            @php $oldUserIds = collect(old('user_ids', []))->map(fn ($id) => (string) $id); @endphp
                            @forelse($users as $user)
                                <label class="bc-user-row">
                                    <input type="checkbox"
                                        name="user_ids[]"
                                        value="{{ $user->id }}"
                                        class="user-checkbox"
                                        {{ $oldUserIds->contains((string) $user->id) ? 'checked' : '' }}>
                                    <span class="bc-user-meta">
                                        <span class="bc-user-name">{{ $user->name }}</span>
                                        <span class="bc-user-email d-block">{{ $user->email }}</span>
                                    </span>
                                    @unless($user->fcm_token)
                                        <span class="bc-user-tag">No FCM</span>
                                    @endunless
                                </label>
                            @empty
                                <div class="p-3 text-muted">No active users found.</div>
                            @endforelse
                        </div>
                        <small class="text-muted d-block mt-1">Tick one or more users to send this broadcast.</small>
                        @error('user_ids') <small class="text-danger">{{ $message }}</small> @enderror
                        @error('user_ids.*') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="bc-actions">
                        <a href="{{ route('admin.broadcast-notifications.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Send Broadcast
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const cardAll = document.getElementById('cardAll');
            const cardSpecific = document.getElementById('cardSpecific');
            const specificWrap = document.getElementById('specificUserWrap');
            const form = document.getElementById('broadcastForm');
            const selectAllBtn = document.getElementById('selectAllUsers');
            const clearAllBtn = document.getElementById('clearAllUsers');

            function syncTarget() {
                const selected = document.querySelector('input[name="target_type"]:checked')?.value || 'all';
                cardAll.classList.toggle('active', selected === 'all');
                cardSpecific.classList.toggle('active', selected === 'specific');
                specificWrap.style.display = selected === 'specific' ? 'block' : 'none';
            }

            document.querySelectorAll('input[name="target_type"]').forEach(function (el) {
                el.addEventListener('change', syncTarget);
            });

            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function () {
                    document.querySelectorAll('.user-checkbox').forEach(function (cb) {
                        cb.checked = true;
                    });
                });
            }

            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', function () {
                    document.querySelectorAll('.user-checkbox').forEach(function (cb) {
                        cb.checked = false;
                    });
                });
            }

            if (form) {
                form.addEventListener('submit', function (e) {
                    const selected = document.querySelector('input[name="target_type"]:checked')?.value || 'all';
                    if (selected === 'specific') {
                        const checked = document.querySelectorAll('.user-checkbox:checked').length;
                        if (checked < 1) {
                            e.preventDefault();
                            alert('Please select at least one user.');
                        }
                    }
                });
            }

            syncTarget();
        })();
    </script>
@endpush
