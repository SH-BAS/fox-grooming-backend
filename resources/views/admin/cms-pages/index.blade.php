@extends('layouts.admin')

@section('title', 'CMS Pages')

@section('content')
    <style>
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
                inset 0 0 40px rgba(255, 0, 0, 0.04);
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .user-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.18), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .user-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 0, 0, 0.45);
            box-shadow:
                0 16px 36px rgba(0, 0, 0, 0.5),
                0 0 24px rgba(255, 0, 0, 0.22);
        }

        .user-card-avatar {
            width: 72px;
            height: 72px;
            margin: 0 auto 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #2a2a2a, #171717);
            border: 1px solid rgba(255, 0, 0, 0.28);
            box-shadow: 0 0 16px rgba(255, 0, 0, 0.15);
            color: #ff4d5a;
            font-size: 26px;
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
            flex-shrink: 0;
        }

        .user-status-label.active {
            color: #34d399;
        }

        .user-status-label.inactive {
            color: #fbbf24;
        }

        .user-meta {
            list-style: none;
            padding: 10px 12px;
            margin: 0 0 16px;
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
            background: linear-gradient(145deg, #ff1f2f, #c62e3a);
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">CMS Pages</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($allowedKeys as $key => $label)
                            @php
                                $page = $existingPages->get($key);
                                $displayKey = $key === 'e_signature' ? 'Consent Form' : str_replace('_', ' ', $key);
                                $icons = [
                                    'terms_condition' => 'fa-file-contract',
                                    'privacy_policy' => 'fa-user-shield',
                                    'about_us' => 'fa-info-circle',
                                    'e_signature' => 'fa-signature',
                                ];
                                $icon = $icons[$key] ?? 'fa-file-alt';
                            @endphp
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="user-card">
                                    <div class="user-card-avatar">
                                        <i class="fas {{ $icon }}"></i>
                                    </div>
                                    <div class="user-card-header">
                                        <h5 class="user-card-name">{{ $page ? $page->title : $label }}</h5>
                                        <span class="user-status-label {{ $page ? 'active' : 'inactive' }}">
                                            {{ $page ? 'Created' : 'Empty' }}
                                        </span>
                                    </div>
                                    <ul class="user-meta">
                                        <li>
                                            <span class="label">Page Key</span>
                                            <span class="value">{{ ucwords($displayKey) }}</span>
                                        </li>
                                        <li>
                                            <span class="label">Updated</span>
                                            <span class="value">
                                                {{ $page ? $page->updated_at->format('M d, Y H:i') : 'Never' }}
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="user-card-actions">
                                        <a href="{{ route('admin.cms-pages.edit', $key) }}"
                                            class="btn btn-primary" title="Edit page">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
