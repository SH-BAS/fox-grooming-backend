@extends('layouts.admin')

@section('title', 'Sizes')

@section('content')
    <style>
        .user-card {
            position: relative; isolation: isolate; overflow: hidden;
            background: linear-gradient(145deg, rgba(255, 0, 0, 0.14) 0%, rgba(0, 0, 0, 0.35) 42%, rgba(20, 20, 20, 0.95) 100%), linear-gradient(180deg, #1a1a1a 0%, #111111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22); border-left: 3px solid #ff0000; border-radius: 16px;
            padding: 24px 18px 18px; height: 100%; display: flex; flex-direction: column;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.45), inset 0 0 40px rgba(255, 0, 0, 0.04);
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .user-card::before {
            content: ""; position: absolute; inset: 0; z-index: -1;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.18), transparent 55%);
            pointer-events: none;
        }
        .user-card:hover {
            transform: translateY(-4px); border-color: rgba(255, 0, 0, 0.45);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.5), 0 0 24px rgba(255, 0, 0, 0.22);
        }
        .user-card-avatar {
            width: 72px; height: 72px; margin: 0 auto 16px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(145deg, #2a2a2a, #171717);
            border: 1px solid rgba(255, 0, 0, 0.28); color: #ff4d5a; font-size: 26px;
            box-shadow: 0 0 16px rgba(255, 0, 0, 0.15);
        }
        .user-card-name { margin: 0 0 14px; font-size: 18px; font-weight: 700; color: #fff; text-align: center; }
        .user-meta {
            list-style: none; padding: 10px 12px; margin: 0 0 16px; flex: 1; border-radius: 12px;
            background: linear-gradient(180deg, rgba(0,0,0,0.35), rgba(0,0,0,0.18));
            border: 1px solid rgba(255,255,255,0.05);
        }
        .user-meta li {
            display: flex; justify-content: space-between; gap: 12px; padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 13px;
        }
        .user-meta li:last-child { border-bottom: none; padding-bottom: 0; }
        .user-meta li:first-child { padding-top: 0; }
        .user-meta .label { color: rgba(255,255,255,0.45); }
        .user-meta .value { color: #f3f3f3; font-weight: 500; }
        .user-card-actions { display: flex; justify-content: center; gap: 8px; }
        .user-card-actions .btn {
            width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;
            border-radius: 10px; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 6px 14px rgba(0,0,0,0.28);
        }
        .user-card-actions .btn-primary { background: linear-gradient(145deg, #ff1f2f, #c62e3a); }
        .user-card-actions .btn-danger { background: linear-gradient(145deg, #ef4444, #b91c1c); }
        .users-empty { padding: 50px 20px; text-align: center; color: var(--text-secondary); }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Sizes</h4>
                    <a href="{{ route('admin.sizes.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-1"></i> Add Size
                    </a>
                </div>
                <div class="card-body">
                    @if($sizes->count())
                        <div class="row g-3">
                            @foreach($sizes as $size)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="user-card">
                                        <div class="user-card-avatar"><i class="fas fa-ruler-combined"></i></div>
                                        <h5 class="user-card-name">{{ $size->name }}</h5>
                                        <ul class="user-meta">
                                            <li>
                                                <span class="label">Weight From</span>
                                                <span class="value">{{ $size->weight_from }} kg</span>
                                            </li>
                                            <li>
                                                <span class="label">Weight To</span>
                                                <span class="value">{{ $size->weight_to }} kg</span>
                                            </li>
                                        </ul>
                                        <div class="user-card-actions">
                                            <a href="{{ route('admin.sizes.edit', $size->id) }}" class="btn btn-primary" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('admin.sizes.destroy', $size->id) }}" method="POST"
                                                data-confirm="Are you sure you want to delete this size?">
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
                    @else
                        <div class="users-empty"><p class="mb-0">No sizes found.</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
