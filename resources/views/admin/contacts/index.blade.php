@extends('layouts.admin')

@section('title', 'Contact Enquiries')

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
            opacity: 0.75;
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

        .user-card-name {
            margin: 0 0 14px;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            text-align: center;
            line-height: 1.3;
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
        }

        .user-card-actions .btn-info {
            background: linear-gradient(145deg, #4b8dff, #2563eb);
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
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">Contact Enquiries</h4>
                </div>
                <div class="card-body">
                    @if ($contacts->count())
                        <div class="row g-3">
                            @foreach ($contacts as $contact)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="user-card">
                                        <div class="user-card-avatar">
                                            <i class="fas fa-envelope-open-text"></i>
                                        </div>
                                        <h5 class="user-card-name">{{ $contact->name }}</h5>
                                        <ul class="user-meta">
                                            <li>
                                                <span class="label">Email</span>
                                                <span class="value">{{ $contact->email }}</span>
                                            </li>
                                            <li>
                                                <span class="label">Phone</span>
                                                <span class="value">{{ $contact->phone ?: '-' }}</span>
                                            </li>
                                            <li>
                                                <span class="label">Subject</span>
                                                <span class="value">{{ \Illuminate\Support\Str::limit($contact->subject ?: 'N/A', 28) }}</span>
                                            </li>
                                            <li>
                                                <span class="label">Date</span>
                                                <span class="value">{{ $contact->created_at->format('M d, Y') }}</span>
                                            </li>
                                        </ul>
                                        <div class="user-card-actions">
                                            <a href="{{ route('admin.contacts.show', $contact->id) }}"
                                                class="btn btn-info" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
                                                data-confirm="Are you sure you want to delete this enquiry?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted small">
                                Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} results
                            </div>
                            <div>{{ $contacts->links() }}</div>
                        </div>
                    @else
                        <div class="users-empty">
                            <p class="mb-0">No contact enquiries found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
