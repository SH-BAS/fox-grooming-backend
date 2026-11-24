@extends('layouts.web')

@section('title', $franchise->name . ' Details | Fox Grooming')

@section('content')
    <section class="inner-header hero-banner divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white text-center">{{ $franchise->name }}</h2>
                        <ol class="breadcrumb text-center text-white mt-10">
                            <li><a href="{{ route('web.index') }}" class="text-white">Home</a></li>
                            <li><a href="{{ route('web.franchise') }}" class="text-white">Franchises</a></li>
                            <li class="active text-gray-silver">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container pb-60">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-5">
                        <div class="thumb">
                            <img src="{{ $franchise->image }}" alt="{{ $franchise->name }}" class="img-fullwidth img-thumbnail">
                        </div>
                        <div class="contact-info border-1px p-25 mt-20 bg-silver-light">
                            <h4 class="text-theme-colored mt-0 mb-15">Contact Information</h4>
                            <p class="mb-10"><i class="fa fa-map-marker text-theme-colored mr-10"></i> <strong>Address:</strong> {{ $franchise->address }}, {{ $franchise->city }}, {{ $franchise->state }}</p>
                            @if($franchise->zone)
                            <p class="mb-10"><i class="fa fa-globe text-theme-colored mr-10"></i> <strong>Service Zone:</strong> {{ $franchise->zone->name }}</p>
                            @endif
                            <div class="mt-20">
                                <a href="{{ route('web.book.franchise') }}" class="btn btn-theme-colored btn-lg btn-block">Book an Appointment Here</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h3 class="text-theme-colored mt-0">{{ $franchise->name }} - <span class="text-gray">Fox Grooming Branch</span></h3>
                        <p class="lead">Welcome to our {{ $franchise->city }} branch. We provide the complete Fox Grooming experience with professional staff and premium equipment.</p>
                        
                        <div class="schedule-box mt-30">
                            <h4 class="text-theme-colored mb-20"><i class="fa fa-clock-o"></i> Weekly Operating Hours</h4>
                            <div class="table-responsive">
                                <table class="table table-hover border-1px">
                                    <thead class="bg-theme-colored text-white">
                                        <tr>
                                            <th>Day</th>
                                            <th>Status</th>
                                            <th>Opening Time</th>
                                            <th>Closing Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($franchise->weeklySchedules as $schedule)
                                        <tr class="{{ $schedule->is_open ? '' : 'bg-lighter' }}">
                                            <td><strong>{{ ucfirst($schedule->day_of_week) }}</strong></td>
                                            <td>
                                                <span class="label {{ $schedule->is_open ? 'label-success' : 'label-danger' }}">
                                                    {{ $schedule->is_open ? 'Open' : 'Closed' }}
                                                </span>
                                            </td>
                                            <td>{{ $schedule->is_open ? \Carbon\Carbon::parse($schedule->opening_time)->format('h:i A') : '--' }}</td>
                                            <td>{{ $schedule->is_open ? \Carbon\Carbon::parse($schedule->closing_time)->format('h:i A') : '--' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="services-info mt-40">
                            <h4 class="text-theme-colored mb-15">Available Services at this Branch</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-check theme-colored">
                                        <li>Full Grooming Service</li>
                                        <li>Bath & Brush</li>
                                        <li>Puppy's First Groom</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-check theme-colored">
                                        <li>Nail Trimming</li>
                                        <li>Ear Cleaning</li>
                                        <li>Teeth Brushing</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section (Optional if integration is needed later) -->
    @if($franchise->latitude && $franchise->longitude)
    <section class="bg-lighter">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-12">
                    <iframe 
                        width="100%" 
                        height="450" 
                        frameborder="0" 
                        style="border:0"
                        src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&q={{ $franchise->latitude }},{{ $franchise->longitude }}" 
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>
    @endif
@endsection
