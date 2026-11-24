@extends('layouts.admin')

@section('title', 'Create Zone')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
@endpush

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

        .premium-panel .form-label {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .premium-panel .form-control {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
            border-radius: 10px;
            min-height: 44px;
        }

        .premium-panel .form-control:focus {
            background: rgba(0, 0, 0, 0.45);
            border-color: rgba(255, 0, 0, 0.55);
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.15);
            color: #fff;
        }

        .premium-panel .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .premium-hint {
            color: rgba(255, 255, 255, 0.45);
            font-size: 12px;
            margin-top: 6px;
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

        #map {
            height: 560px;
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(255, 0, 0, 0.2);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
            margin-top: 10px;
        }

        .leaflet-draw-toolbar a {
            background-color: #fff;
        }
    </style>

    <div class="row">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-header">
                    <h4>Define Service Zone</h4>
                    <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <form action="{{ route('admin.zones.store') }}" method="POST" id="zone-form">
                    @csrf
                    <div class="premium-body">
                        <div class="mb-4">
                            <label for="name" class="form-label">Zone Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" placeholder="E.g. Downtown Dubai, Manhattan North..."
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="premium-hint">Give your zone a descriptive name for easy identification.</div>
                        </div>

                        <div>
                            <label class="form-label">Draw Zone Boundary</label>
                            <p class="premium-hint mb-0">
                                Use the polygon tool to draw the service area, or search a place name to auto-draw a boundary.
                            </p>
                            <div id="map"></div>
                            <input type="hidden" name="coordinates" id="coordinates" value="{{ old('coordinates') }}">
                            @error('coordinates')
                                <div class="text-danger small mt-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="premium-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Zone
                            </button>
                            <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('partials.google-maps-script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mapCenter = [25.2048, 55.2708];
            var map = L.map('map').setView(mapCenter, 11);
            var shapeColor = '#E63946';

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var editableLayers = new L.FeatureGroup();
            map.addLayer(editableLayers);

            const autocompleteInput = document.getElementById('name');
            const autocomplete = new google.maps.places.Autocomplete(autocompleteInput, {
                types: ['geocode'],
            });

            autocompleteInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                editableLayers.clearLayers();

                if (place.geometry.viewport) {
                    var sw = place.geometry.viewport.getSouthWest();
                    var ne = place.geometry.viewport.getNorthEast();

                    var latLngs = [
                        [sw.lat(), sw.lng()],
                        [ne.lat(), sw.lng()],
                        [ne.lat(), ne.lng()],
                        [sw.lat(), ne.lng()],
                        [sw.lat(), sw.lng()]
                    ];

                    L.polygon(latLngs, {
                        color: shapeColor,
                        weight: 3,
                        fillOpacity: 0.2
                    }).addTo(editableLayers);

                    map.fitBounds(L.latLngBounds(
                        L.latLng(sw.lat(), sw.lng()),
                        L.latLng(ne.lat(), ne.lng())
                    ));

                    updateCoordinatesFromLatLngs(latLngs);
                } else {
                    map.setView([place.geometry.location.lat(), place.geometry.location.lng()], 15);

                    var radius = 0.005;
                    var lat = place.geometry.location.lat();
                    var lng = place.geometry.location.lng();
                    var latLngs = [
                        [lat - radius, lng - radius],
                        [lat + radius, lng - radius],
                        [lat + radius, lng + radius],
                        [lat - radius, lng + radius],
                        [lat - radius, lng - radius]
                    ];

                    L.polygon(latLngs, {
                        color: shapeColor,
                        weight: 3,
                        fillOpacity: 0.2
                    }).addTo(editableLayers);

                    updateCoordinatesFromLatLngs(latLngs);
                }
            });

            var drawControl = new L.Control.Draw({
                draw: {
                    polygon: {
                        allowIntersection: false,
                        showArea: true,
                        shapeOptions: { color: shapeColor }
                    },
                    polyline: false, stripe: false, rectangle: false, circle: false, marker: false, circlemarker: false
                },
                edit: {
                    featureGroup: editableLayers,
                    remove: true
                }
            });
            map.addControl(drawControl);

            map.on('draw:created', function (e) {
                editableLayers.clearLayers();
                editableLayers.addLayer(e.layer);
                updateCoordinates(e.layer);
            });

            map.on('draw:edited', function (e) {
                e.layers.eachLayer(function (layer) {
                    updateCoordinates(layer);
                });
            });

            map.on('draw:deleted', function () {
                document.getElementById('coordinates').value = '';
            });

            function updateCoordinates(layer) {
                var latlngs = layer.getLatLngs()[0];
                var coords = latlngs.map(function(ll) {
                    return { lat: ll.lat, lng: ll.lng };
                });
                document.getElementById('coordinates').value = JSON.stringify(coords);
            }

            function updateCoordinatesFromLatLngs(latLngs) {
                var coords = latLngs.slice(0, -1).map(function(ll) {
                    return { lat: ll[0], lng: ll[1] };
                });
                document.getElementById('coordinates').value = JSON.stringify(coords);
            }

            if (navigator.geolocation && !autocompleteInput.value) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    map.setView([position.coords.latitude, position.coords.longitude], 13);
                });
            }
        });
    </script>
@endpush
