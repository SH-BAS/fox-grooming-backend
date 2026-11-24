@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
    <style>
        .premium-panel {
            position: relative; isolation: isolate; overflow: hidden;
            background: linear-gradient(145deg, rgba(255, 0, 0, 0.12) 0%, rgba(0, 0, 0, 0.3) 40%, rgba(18, 18, 18, 0.98) 100%), linear-gradient(180deg, #1a1a1a 0%, #111 100%);
            border: 1px solid rgba(255, 0, 0, 0.22); border-left: 3px solid #ff0000; border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), inset 0 0 40px rgba(255, 0, 0, 0.04);
        }
        .premium-panel::before {
            content: ""; position: absolute; inset: 0;
            background: radial-gradient(circle at top right, rgba(255, 0, 0, 0.16), transparent 55%);
            pointer-events: none; z-index: -1;
        }
        .premium-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 22px 24px 0; }
        .premium-header h4 { margin: 0; color: #fff; font-weight: 700; }
        .premium-body { padding: 22px 24px 24px; }
        .premium-panel .form-label { color: rgba(255, 255, 255, 0.7); font-weight: 600; font-size: 13px; margin-bottom: 6px; }
        .premium-panel .form-control, .premium-panel select.form-control, .premium-panel textarea.form-control {
            background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.08); color: #fff; border-radius: 10px; min-height: 44px;
        }
        .premium-panel .form-control:focus, .premium-panel select.form-control:focus, .premium-panel textarea.form-control:focus {
            background: rgba(0, 0, 0, 0.45); border-color: rgba(255, 0, 0, 0.55); box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15); color: #fff;
        }
        .premium-panel .form-control::placeholder { color: rgba(255, 255, 255, 0.35); }
        .premium-panel select.form-control option { background: #1a1a1a; color: #fff; }
        .premium-panel .form-check-label { color: rgba(255, 255, 255, 0.75); }
        .premium-hint { color: rgba(255, 255, 255, 0.4); font-size: 12px; margin-top: 6px; }
        .premium-preview {
            max-height: 100px; object-fit: cover; border-radius: 12px;
            border: 1px solid rgba(255, 0, 0, 0.3); margin-top: 10px;
        }
        .premium-preview.icon { max-height: 50px; object-fit: contain; background: rgba(0, 0, 0, 0.35); padding: 6px; }
        .premium-table { color: #fff !important; }
        .premium-table thead th {
            background: rgba(0, 0, 0, 0.4) !important; border-color: rgba(255, 255, 255, 0.08) !important;
            color: #fff !important; font-size: 12px; text-transform: uppercase;
        }
        .premium-table tbody td, .premium-table tbody td strong {
            color: #fff !important; border-color: rgba(255, 255, 255, 0.08) !important;
            background: transparent !important; vertical-align: middle;
        }
        .premium-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .premium-actions .btn { border-radius: 10px; min-width: 140px; box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25); }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Edit Category</h4>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @if($category->image)
                                    <div>
                                        <img src="{{ $category->image }}" alt="Category" class="premium-preview">
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Icon</label>
                                <input type="file" name="icon" class="form-control">
                                @if($category->icon)
                                    <div>
                                        <img src="{{ asset('storage/' . $category->icon) }}" alt="Icon"
                                            class="premium-preview icon">
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                    rows="3">{{ $category->description ?? '' }}</textarea>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Pricing Type</label>
                                <select name="pricing_type" id="pricing_type" class="form-control" required>
                                    <option value="fixable" {{ $category->pricing_type == 'fixable' ? 'selected' : '' }}>Fixed
                                        Price</option>
                                    <option value="sizeable" {{ $category->pricing_type == 'sizeable' ? 'selected' : '' }}>
                                        Size Based</option>
                                </select>
                            </div>

                            @php
                                $standard = $category->standards->first();
                                $standardTitle = $standard->title ?? '';
                                $fixedPrice = $category->pricing_type == 'fixable' ? ($standard->price ?? '') : '';
                                $sizePrices = $category->pricing_type == 'sizeable' ? $category->standards->pluck('price', 'size_id')->toArray() : [];
                            @endphp

                            <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Standard Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Standard Title"
                                    value="{{ $standardTitle }}" required>
                            </div>

                            <div id="fixed_price_div" class="col-lg-4 col-md-6 col-sm-12 mb-3"
                                style="{{ $category->pricing_type == 'fixable' ? '' : 'display: none;' }}">
                                <label class="form-label">Category Standard Price ($)</label>
                                <input type="number" step="0.01" min="0" name="price" class="form-control"
                                    value="{{ $fixedPrice !== '' ? $fixedPrice : 0 }}" placeholder="0.00"
                                    {{ $category->pricing_type == 'fixable' ? 'required' : '' }}>
                                <div class="premium-hint">Minimum 0. Null is not allowed.</div>
                            </div>

                            <div id="sizeable_price_div" class="col-12 mb-3"
                                style="{{ $category->pricing_type == 'sizeable' ? '' : 'display: none;' }}">
                                <label class="form-label">Prices by Size</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered premium-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Size</th>
                                                <th>Price ($)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sizes as $size)
                                                <tr>
                                                    <td>{{ $size->name }} ({{ $size->weight_from }}-{{ $size->weight_to }}kg)
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0"
                                                            name="size_prices[{{ $size->id }}]"
                                                            class="form-control size-price-input"
                                                            value="{{ $sizePrices[$size->id] ?? 0 }}" placeholder="0.00"
                                                            {{ $category->pricing_type == 'sizeable' ? 'required' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="premium-hint">Each size price is required. Minimum allowed value is 0.</div>
                            </div>
                        </div>

                        <div class="form-check custom-checkbox mb-3">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_check"
                                {{ $category->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_check">Active</label>
                        </div>
                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Category</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                function togglePricingFields() {
                    if ($('#pricing_type').val() === 'fixable') {
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
@endsection
