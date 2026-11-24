@extends('layouts.admin')

@section('title', 'View Zone: ' . $zone->name)

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        .premium-hero {
            padding: 28px;
        }

        .premium-avatar {
            width: 90px;
            height: 90px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 0, 0, 0.35);
            box-shadow:
                0 10px 24px rgba(0, 0, 0, 0.35),
                0 0 18px rgba(255, 0, 0, 0.2);
            background: linear-gradient(145deg, #2a2a2a, #171717);
            color: #ff4d5a;
            font-size: 34px;
        }

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

        .premium-meta i {
            color: #ff4d5a;
            width: 18px;
        }

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
            color: #34d399;
        }

        .premium-actions .btn {
            border-radius: 10px;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
        }

        #map {
            height: 560px;
            width: 100%;
            border-radius: 14px;
            border: 1px solid rgba(255, 0, 0, 0.2);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
        }

        .premium-map-wrap {
            padding: 0 24px 24px;
        }

        .premium-section-title {
            color: #fff;
            font-weight: 700;
            margin: 0 0 14px;
            padding: 0 24px;
        }
    </style>

    <div class="row g-4">
        <div class="col-xl-12">
            <div class="premium-panel">
                <div class="premium-hero">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <div class="premium-avatar">
                                <i class="fas fa-draw-polygon"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <h2 class="premium-title">{{ $zone->name }}</h2>
                                <span class="premium-badge">Active</span>
                            </div>
                            <p class="premium-meta mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Area Coverage:
                                @if($zone->area && $zone->area->getGeometries()->first())
                                    {{ $zone->area->getGeometries()->first()->getGeometries()->count() }} Points
                                @else
                                    Invalid Area
                                @endif
                            </p>
                            <p class="premium-meta mb-0">
                                <i class="fas fa-store me-1"></i>
                                Franchises: {{ $zone->franchises_count ?? 0 }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar me-1"></i>
                                Created {{ $zone->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div class="col-auto premium-actions d-flex gap-2">
                            <a href="{{ route('admin.zones.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <a href="{{ route('admin.zones.edit', $zone->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-pencil-alt me-1"></i> Edit Zone
                            </a>
                        </div>
                    </div>
                </div>

                <h5 class="premium-section-title">Boundary Map</h5>
                <div class="premium-map-wrap">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @php
                $points = [];
                if ($zone->area && $zone->area->getGeometries()->first()) {
                    $points = $zone->area->getGeometries()->first()->getGeometries()->map(function($point) {
                        return [$point->latitude, $point->longitude];
                    })->toArray();
                }
            @endphp

            var latLngs = @json($points);
            if (!latLngs.length) {
                return;
            }

            var map = L.map('map');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var polygon = L.polygon(latLngs, {
                color: '#E63946',
                weight: 3,
                fillColor: '#ff0000',
                fillOpacity: 0.2
            }).addTo(map);

            map.fitBounds(polygon.getBounds());
        });
    </script>
@endpush
