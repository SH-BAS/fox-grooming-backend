@extends('layouts.admin')

@section('title', 'Edit Franchise')

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
            letter-spacing: -0.01em;
        }

        .premium-body {
            padding: 22px 24px 24px;
        }

        .premium-avatar-preview {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 0, 0, 0.35);
            box-shadow: 0 0 16px rgba(255, 0, 0, 0.18);
            background: #1f1f1f;
        }

        .premium-avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        .premium-panel .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .premium-panel select.form-control option {
            background: #1a1a1a;
            color: #fff;
        }

        .premium-panel .form-check-label {
            color: rgba(255, 255, 255, 0.75);
        }

        .premium-hint {
            color: rgba(255, 255, 255, 0.4);
            font-size: 12px;
            margin-top: 6px;
        }

        .premium-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 8px;
        }

        .premium-actions .btn {
            border-radius: 10px;
            min-width: 140px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Edit Franchise</h4>
                    <a href="{{ route('admin.franchises.show', $franchise->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="premium-body">
                    <form action="{{ route('admin.franchises.update', $franchise->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Select Zone <span class="text-danger">*</span></label>
                                <select name="zone_id" class="form-control @error('zone_id') is-invalid @enderror" required>
                                    <option value="" disabled>-- Choose Zone --</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}" {{ (old('zone_id', $franchise->zone_id) == $zone->id) ? 'selected' : '' }}>{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                                @error('zone_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Franchise Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Franchise Name"
                                    value="{{ old('name', $franchise->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Search Address (Google Autofill)</label>
                                <input type="text" id="autocomplete" class="form-control" placeholder="Start typing address...">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Full Address</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="123 Main St"
                                    value="{{ old('address', $franchise->address) }}" required>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="City"
                                    value="{{ old('city', $franchise->city) }}" required>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" id="state" class="form-control" placeholder="State"
                                    value="{{ old('state', $franchise->state) }}" required>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label class="form-label">Franchise Image</label>
                                <input type="file" name="image" class="form-control">
                                @if($franchise->getAttributes()['image'] ?? false)
                                    <div class="mt-3 d-flex align-items-center gap-3">
                                        <div class="premium-avatar-preview">
                                            <img src="{{ $franchise->image }}" alt="{{ $franchise->name }}">
                                        </div>
                                        <span class="premium-hint">Current franchise image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control"
                                    value="{{ old('latitude', $franchise->latitude) }}" readonly>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control"
                                    value="{{ old('longitude', $franchise->longitude) }}" readonly>
                            </div>
                        </div>
                        <div class="form-check custom-checkbox mb-3">
                            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_check"
                                {{ old('is_active', $franchise->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active_check">Active</label>
                        </div>
                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Franchise
                            </button>
                            <a href="{{ route('admin.franchises.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('partials.google-maps-script')
    <script>
        function initAutocomplete() {
            const autocompleteInput = document.getElementById('autocomplete');
            const autocomplete = new google.maps.places.Autocomplete(autocompleteInput, {
                types: ['address'],
                componentRestrictions: { country: "us" }
            });

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    return;
                }

                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();

                let address = '';
                let city = '';
                let state = '';

                for (const component of place.address_components) {
                    const componentType = component.types[0];

                    switch (componentType) {
                        case "street_number":
                            address = component.long_name + " " + address;
                            break;
                        case "route":
                            address += component.short_name;
                            break;
                        case "locality":
                            city = component.long_name;
                            break;
                        case "administrative_area_level_1":
                            state = component.short_name;
                            break;
                    }
                }

                document.getElementById('address').value = address;
                document.getElementById('city').value = city;
                document.getElementById('state').value = state;
            });
        }

        google.maps.event.addDomListener(window, 'load', initAutocomplete);
    </script>
@endpush
