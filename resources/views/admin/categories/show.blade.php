@extends('layouts.admin')

@section('title', ($category->name ?? 'Category') . ' | Category')

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

        .premium-hero { padding: 28px; }

        .premium-avatar {
            width: 120px;
            height: 120px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 0, 0, 0.35);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35), 0 0 18px rgba(255, 0, 0, 0.2);
            background: #1f1f1f;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .premium-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .premium-avatar i { color: rgba(255, 255, 255, 0.35); font-size: 2.5rem; }

        .premium-title {
            margin: 0 0 8px;
            color: #fff;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .premium-meta {
            margin: 0;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .premium-stat-label {
            display: block;
            color: rgba(255, 255, 255, 0.45);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
        }

        .premium-stat-value { color: #fff; font-weight: 700; }

        .premium-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .premium-badge.active { color: #34d399; }
        .premium-badge.inactive { color: #fbbf24; }

        .premium-actions .btn {
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        .premium-tabs {
            border-bottom: 1px solid rgba(255, 0, 0, 0.18);
            padding: 0 8px;
            gap: 8px;
        }

        .premium-tabs .nav-link {
            background: transparent !important;
            border: none !important;
            color: rgba(255, 255, 255, 0.55) !important;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 14px 4px;
            margin-right: 22px;
            position: relative;
        }

        .premium-tabs .nav-link:hover { color: #fff !important; }
        .premium-tabs .nav-link.active { color: #fff !important; }

        .premium-tabs .nav-link.active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 2px;
            background: linear-gradient(90deg, #ff0000, #E63946, transparent);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }

        .premium-panel .form-label {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .premium-panel .form-control,
        .premium-panel select.form-control {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
            border-radius: 10px;
            min-height: 44px;
        }

        .premium-panel .form-control:focus,
        .premium-panel select.form-control:focus {
            background: rgba(0, 0, 0, 0.45);
            border-color: rgba(255, 0, 0, 0.55);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15);
            color: #fff;
        }

        .premium-panel select.form-control option { background: #1a1a1a; color: #fff; }
        .premium-hint { color: rgba(255, 255, 255, 0.4); font-size: 12px; margin-top: 6px; }

        .premium-table { color: #ffffff !important; }

        .premium-table thead th {
            background: rgba(0, 0, 0, 0.35) !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            color: #ffffff !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .premium-table tbody td,
        .premium-table tbody td strong,
        .premium-table tbody td small {
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
            vertical-align: middle;
            background: transparent !important;
        }

        .premium-table tbody tr:hover td {
            background: rgba(255, 0, 0, 0.06) !important;
            color: #ffffff !important;
        }
    </style>

    <div class="row g-4">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-hero">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <div class="premium-avatar">
                                @if($category->image)
                                    <img src="{{ $category->image }}" alt="{{ $category->name }}">
                                @else
                                    <i class="fas fa-paw"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h2 class="premium-title mb-0">{{ $category->name }}</h2>
                                <span class="premium-badge {{ $category->is_active ? 'active' : 'inactive' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="premium-meta mb-3">{{ $category->description ?? 'No description available.' }}</p>
                            <div class="d-flex flex-wrap align-items-center gap-4">
                                <div>
                                    <span class="premium-stat-label">Pricing Type</span>
                                    <span class="premium-stat-value">{{ ucfirst($category->pricing_type) }}</span>
                                </div>
                                <div>
                                    <span class="premium-stat-label">Related Services</span>
                                    <span class="premium-stat-value">{{ $category->services->count() }}</span>
                                </div>
                                <div>
                                    <span class="premium-stat-label">Related Pets</span>
                                    <span class="premium-stat-value">{{ $category->pets->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto premium-actions d-flex gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-pencil-alt me-1"></i> Edit Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="card-header border-0 pb-0 bg-transparent">
                    <ul class="nav nav-tabs premium-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#standards" role="tab">Standards &amp;
                                Pricing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#breeds" role="tab">Breeds
                                ({{ $category->types->count() }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#services" role="tab">Services
                                ({{ $category->services->count() }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pets" role="tab">Pets
                                ({{ $category->pets->count() }})</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Standards Tab -->
                        <div class="tab-pane fade show active" id="standards" role="tabpanel">
                            @php
                                $standard = $category->standards->first();
                                $standardTitle = old('title', $standard->title ?? '');
                                $fixedPrice = old('price', $category->pricing_type == 'fixable' ? ($standard->price ?? 0) : 0);
                                $sizePrices = $category->pricing_type == 'sizeable'
                                    ? $category->standards->pluck('price', 'size_id')->toArray()
                                    : [];
                                $currentPricingType = old('pricing_type', $category->pricing_type);
                            @endphp

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.categories.standards.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label class="form-label">Pricing Type</label>
                                        <select name="pricing_type" id="pricing_type" class="form-control" required>
                                            <option value="fixable" {{ $currentPricingType == 'fixable' ? 'selected' : '' }}>
                                                Fixed Price
                                            </option>
                                            <option value="sizeable" {{ $currentPricingType == 'sizeable' ? 'selected' : '' }}>
                                                Size Based
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label class="form-label">Standard Title</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ $standardTitle }}" required>
                                    </div>
                                    <div id="fixed_price_div" class="col-lg-4 col-md-6 mb-3"
                                        style="{{ $currentPricingType == 'fixable' ? '' : 'display: none;' }}">
                                        <label class="form-label">Base Price ($)</label>
                                        <input type="number" step="0.01" min="0" name="price" class="form-control"
                                            value="{{ $fixedPrice }}" placeholder="0.00"
                                            {{ $currentPricingType == 'fixable' ? 'required' : '' }}>
                                        <div class="premium-hint">Minimum 0. Null is not allowed.</div>
                                    </div>
                                </div>

                                <div id="sizeable_price_div" class="mb-3"
                                    style="{{ $currentPricingType == 'sizeable' ? '' : 'display: none;' }}">
                                    <label class="form-label">Base Prices by Size</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered premium-table table-responsive-md mb-0">
                                            <thead>
                                                <tr>
                                                    <th><strong>Size</strong></th>
                                                    <th><strong>Price ($)</strong></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sizes as $size)
                                                    <tr>
                                                        <td>
                                                            {{ $size->name }}
                                                            ({{ $size->weight_from }}-{{ $size->weight_to }}kg)
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" min="0"
                                                                name="size_prices[{{ $size->id }}]"
                                                                class="form-control size-price-input"
                                                                value="{{ old('size_prices.'.$size->id, $sizePrices[$size->id] ?? 0) }}"
                                                                placeholder="0.00"
                                                                {{ $currentPricingType == 'sizeable' ? 'required' : '' }}>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="premium-hint">Each size price is required. Minimum allowed value is 0.</div>
                                </div>

                                <div class="premium-actions">
                                    <button type="submit" class="btn btn-primary">Update Standards &amp; Pricing</button>
                                </div>
                            </form>
                        </div>

                        <!-- Breeds Tab -->
                        <div class="tab-pane fade" id="breeds" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table premium-table table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th><strong>Image</strong></th>
                                            <th><strong>Name</strong></th>
                                            <th><strong>Status</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->types as $type)
                                            <tr>
                                                <td>
                                                    @if($type->image)
                                                        <img src="{{ $type->image }}" class="rounded" width="40" height="40">
                                                    @endif
                                                </td>
                                                <td>{{ $type->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge light badge-{{ $type->is_active ? 'success' : 'danger' }}">
                                                        {{ $type->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Services Tab -->
                        <div class="tab-pane fade" id="services" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table premium-table table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th><strong>Name</strong></th>
                                            <th><strong>Pricing Type</strong></th>
                                            <th><strong>Status</strong></th>
                                            <th><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->services as $service)
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td><span
                                                        class="badge light badge-info">{{ ucfirst($service->pricing_type) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge light badge-{{ $service->is_active ? 'success' : 'danger' }}">
                                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.services.edit', $service->id) }}"
                                                        class="btn btn-primary btn-xs sharp me-1"><i
                                                            class="fas fa-pencil-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pets Tab -->
                        <div class="tab-pane fade" id="pets" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table premium-table table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th><strong>Pet Name</strong></th>
                                            <th><strong>Owner</strong></th>
                                            <th><strong>Breed</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->pets as $pet)
                                            <tr>
                                                <td>{{ $pet->name }}</td>
                                                <td>{{ $pet->user->name ?? 'Guest' }}</td>
                                                <td>{{ $pet->type->name ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            function togglePricingFields() {
                var type = $('#pricing_type').val();
                if (type === 'fixable') {
                    $('#fixed_price_div').show();
                    $('#sizeable_price_div').hide();
                    $('#fixed_price_div input[name="price"]').prop('required', true);
                    $('.size-price-input').prop('required', false);
                } else {
                    $('#fixed_price_div').hide();
                    $('#sizeable_price_div').show();
                    $('#fixed_price_div input[name="price"]').prop('required', false);
                    $('.size-price-input').prop('required', true);
                }
            }

            $('#pricing_type').on('change', togglePricingFields);
            togglePricingFields();
        });
    </script>
@endpush
