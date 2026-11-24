@extends('layouts.admin')

@section('title', 'Breeds')

@section('content')
    <style>
        .user-card {
            position: relative; isolation: isolate; overflow: hidden;
            background: linear-gradient(145deg, rgba(255, 0, 0, 0.14) 0%, rgba(0, 0, 0, 0.35) 42%, rgba(20, 20, 20, 0.95) 100%), linear-gradient(180deg, #1a1a1a 0%, #111111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22); border-left: 3px solid #ff0000; border-radius: 16px;
            padding: 0; height: 100%; display: flex; flex-direction: column;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.45), inset 0 0 40px rgba(255, 0, 0, 0.04);
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .user-card::before {
            content: ""; position: absolute; inset: 0; z-index: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.18), transparent 55%);
            pointer-events: none;
        }
        .user-card:hover {
            transform: translateY(-4px); border-color: rgba(255, 0, 0, 0.45);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.5), 0 0 24px rgba(255, 0, 0, 0.22);
        }
        .franchise-card-media, .franchise-card-body { position: relative; z-index: 1; }
        .franchise-card-media { position: relative; width: 100%; height: 160px; overflow: hidden; background: #1a1a1a; }
        .franchise-card-media img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.35s ease; }
        .user-card:hover .franchise-card-media img { transform: scale(1.04); }
        .franchise-card-media::after {
            content: ""; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.05) 40%, rgba(20,20,20,0.8) 100%);
            pointer-events: none;
        }
        .franchise-media-badge {
            position: absolute; top: 12px; left: 12px; z-index: 2; padding: 5px 12px; border-radius: 999px;
            background: rgba(0,0,0,0.55); border: 1px solid rgba(255,255,255,0.12); color: #fff;
            font-size: 11px; font-weight: 700;
        }
        .franchise-card-body { padding: 16px; display: flex; flex-direction: column; flex: 1; }
        .user-card-header { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 8px; }
        .franchise-zone-label { margin: 0; font-size: 11px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #E63946; }
        .user-card-name { margin: 0 0 14px; font-size: 18px; font-weight: 700; color: #fff; }
        .user-status-label {
            font-size: 11px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;
            padding: 4px 10px; border-radius: 999px; background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.06);
        }
        .user-status-label.active { color: #34d399; }
        .user-status-label.inactive { color: #fbbf24; }
        .user-card-actions { display: flex; gap: 8px; margin-top: auto; }
        .user-card-actions .btn {
            width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center;
            border-radius: 10px; border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 6px 14px rgba(0,0,0,0.28);
        }
        .user-card-actions .btn-primary { background: linear-gradient(145deg, #ff1f2f, #c62e3a); }
        .user-card-actions .btn-danger { background: linear-gradient(145deg, #ef4444, #b91c1c); }
        .users-empty { padding: 50px 20px; text-align: center; color: var(--text-secondary); }
        .premium-filter {
            background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.08); color: #fff;
            border-radius: 10px; min-height: 38px; padding: 6px 12px;
        }
        .premium-filter option { background: #1a1a1a; color: #fff; }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <h4 class="card-title mb-0">Breeds</h4>
                        <form action="{{ route('admin.category-types.index') }}" method="GET">
                            <select name="category_id" class="premium-filter" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <a href="{{ route('admin.category-types.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-1"></i> Add Breed
                    </a>
                </div>
                <div class="card-body">
                    @if($categoryTypes->count())
                        <div class="row g-3">
                            @foreach($categoryTypes as $ct)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="user-card">
                                        <div class="franchise-card-media">
                                            <img src="{{ $ct->image }}" alt="{{ $ct->name }}">
                                            <span class="franchise-media-badge">{{ $ct->category->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="franchise-card-body">
                                            <div class="user-card-header">
                                                <p class="franchise-zone-label">Breed</p>
                                                <span class="user-status-label {{ $ct->is_active ? 'active' : 'inactive' }}">
                                                    {{ $ct->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <h5 class="user-card-name">{{ $ct->name }}</h5>
                                            <div class="user-card-actions">
                                                <a href="{{ route('admin.category-types.edit', $ct->id) }}" class="btn btn-primary" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('admin.category-types.destroy', $ct->id) }}" method="POST"
                                                    data-confirm="Are you sure you want to delete this breed?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">{{ $categoryTypes->appends(request()->input())->links() }}</div>
                    @else
                        <div class="users-empty"><p class="mb-0">No breeds found.</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
