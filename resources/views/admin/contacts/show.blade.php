@extends('layouts.admin')

@section('title', 'Enquiry Details')

@section('content')
    <style>
        .premium-panel {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(145deg, rgba(255, 0, 0, 0.12) 0%, rgba(0, 0, 0, 0.3) 40%, rgba(18, 18, 18, 0.98) 100%),
                linear-gradient(180deg, #1a1a1a 0%, #111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22);
            border-left: 3px solid #ff0000;
            border-radius: 16px;
            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.4),
                inset 0 0 40px rgba(255, 0, 0, 0.04);
        }

        .premium-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.16), transparent 55%);
            pointer-events: none;
            z-index: -1;
        }

        .premium-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 22px 24px 0;
        }

        .premium-header h4 {
            margin: 0;
            color: #fff;
            font-weight: 700;
        }

        .premium-body {
            padding: 20px 24px 24px;
        }

        .premium-avatar {
            width: 72px;
            height: 72px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #2a2a2a, #171717);
            border: 1px solid rgba(255, 0, 0, 0.35);
            box-shadow: 0 0 16px rgba(255, 0, 0, 0.18);
            color: #ff4d5a;
            font-size: 28px;
        }

        .premium-title {
            margin: 0 0 4px;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .premium-meta {
            margin: 0;
            color: rgba(255, 255, 255, 0.65);
            font-size: 13px;
        }

        .meta-list {
            list-style: none;
            padding: 0;
            margin: 18px 0 0;
        }

        .meta-list li {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 14px;
        }

        .meta-list li:last-child {
            border-bottom: none;
        }

        .meta-list .label {
            color: rgba(255, 255, 255, 0.45);
            font-weight: 500;
        }

        .meta-list .value {
            color: #fff;
            text-align: right;
            font-weight: 500;
            word-break: break-word;
        }

        .message-box {
            margin-top: 8px;
            padding: 16px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .premium-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .premium-actions .btn {
            border-radius: 10px;
            min-width: 140px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }
    </style>

    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Enquiry Details</h4>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="premium-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h2 class="premium-title">{{ $contact->name }}</h2>
                            <p class="premium-meta">
                                Received {{ $contact->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>

                    <ul class="meta-list">
                        <li>
                            <span class="label">Email</span>
                            <span class="value">
                                <a href="mailto:{{ $contact->email }}" style="color:#fff;">{{ $contact->email }}</a>
                            </span>
                        </li>
                        <li>
                            <span class="label">Phone</span>
                            <span class="value">
                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}" style="color:#fff;">{{ $contact->phone }}</a>
                                @else
                                    -
                                @endif
                            </span>
                        </li>
                        <li>
                            <span class="label">Subject</span>
                            <span class="value">{{ $contact->subject ?: 'N/A' }}</span>
                        </li>
                        <li class="flex-column align-items-start" style="display:block;">
                            <span class="label d-block mb-1">Message</span>
                            <div class="message-box">{{ $contact->message }}</div>
                        </li>
                    </ul>

                    <div class="premium-actions">
                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject ?: 'Your enquiry') }}"
                            class="btn btn-primary">
                            <i class="fas fa-reply me-1"></i> Reply via Email
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
                            data-confirm="Are you sure you want to delete this enquiry?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Delete Enquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
