@extends('layouts.admin')

@section('title', 'Gallery')

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
            padding: 0;
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
            z-index: 0;
        }

        .user-card:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 0, 0, 0.45);
            box-shadow:
                0 16px 36px rgba(0, 0, 0, 0.5),
                0 0 24px rgba(255, 0, 0, 0.22);
        }

        .franchise-card-media,
        .franchise-card-body {
            position: relative;
            z-index: 1;
        }

        .gallery-card-media {
            position: relative;
            width: 100%;
            height: 180px;
            overflow: hidden;
            background: #1a1a1a;
            display: flex;
        }

        .gallery-card-media .half {
            position: relative;
            width: 50%;
            height: 100%;
            overflow: hidden;
        }

        .gallery-card-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.35s ease;
        }

        .user-card:hover .gallery-card-media img {
            transform: scale(1.04);
        }

        .gallery-card-media .half::after {
            content: attr(data-label);
            position: absolute;
            left: 8px;
            bottom: 8px;
            z-index: 2;
            padding: 3px 8px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .franchise-media-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 3;
            padding: 5px 12px;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            backdrop-filter: blur(6px);
        }

        .franchise-card-body {
            padding: 16px 16px 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .franchise-zone-label {
            margin: 0 0 6px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #E63946;
        }

        .user-card-name {
            margin: 0 0 14px;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
        }

        .user-card-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
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

        .user-card-actions .btn-primary {
            background: linear-gradient(145deg, #ff1f2f, #c62e3a);
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
                    <h4 class="card-title mb-0">Gallery</h4>
                    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus me-1"></i> Add Item
                    </a>
                </div>
                <div class="card-body">
                    @if ($galleries->count())
                        <div class="row g-3">
                            @foreach ($galleries as $gallery)
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="user-card">
                                        <div class="gallery-card-media">
                                            <span class="franchise-media-badge">
                                                {{ $gallery->category->name ?? 'Gallery' }}
                                            </span>
                                            <div class="half" data-label="Before">
                                                <img src="{{ $gallery->before_image }}" alt="Before">
                                            </div>
                                            <div class="half" data-label="After">
                                                <img src="{{ $gallery->after_image }}" alt="After">
                                            </div>
                                        </div>
                                        <div class="franchise-card-body">
                                            <p class="franchise-zone-label">Before & After</p>
                                            <h5 class="user-card-name">
                                                {{ $gallery->category->name ?? 'Gallery Item' }} Transformation
                                            </h5>
                                            <div class="user-card-actions">
                                                <a href="{{ route('admin.galleries.edit', $gallery->id) }}"
                                                    class="btn btn-primary" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('admin.galleries.destroy', $gallery->id) }}"
                                                    method="POST"
                                                    data-confirm="Are you sure you want to delete this gallery item?">
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

                        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted small">
                                Showing {{ $galleries->firstItem() }} to {{ $galleries->lastItem() }} of {{ $galleries->total() }} results
                            </div>
                            <div>{{ $galleries->links() }}</div>
                        </div>
                    @else
                        <div class="users-empty">
                            <p class="mb-0">No gallery items found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
