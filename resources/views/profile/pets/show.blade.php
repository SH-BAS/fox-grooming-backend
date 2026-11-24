@extends('layouts.web')

@section('title', 'Pet Details | Fox Grooming')

@section('content')
    <section class="inner-header divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white">Pet Details: {{ $pet->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="{{ route('web.profile.index') }}" class="list-group-item">My Profile</a>
                        <a href="{{ route('web.pets.index') }}" class="list-group-item active">Pet Management</a>
                        <a href="{{ route('web.user.bookings.index') }}" class="list-group-item">My Bookings</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="border-1px p-30">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="{{ $pet->profile_image }}" alt="{{ $pet->name }}" class="img-circle mb-20"
                                    style="width: 200px; height: 200px; object-fit: cover; border: 5px solid #5cb85c;">
                                <div class="btn-group">
                                    <a href="{{ route('web.pets.edit', $pet->id) }}" class="btn btn-dark btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="{{ route('web.pets.index') }}" class="btn btn-default btn-sm">Back</a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h3 class="text-theme-colored mt-0">{{ $pet->name }}</h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Category:</strong> {{ $pet->category->name ?? 'N/A' }}</p>
                                        <p><strong>Breed:</strong> {{ $pet->type->name ?? 'N/A' }}</p>
                                        <p><strong>Size:</strong> {{ $pet->size ? $pet->size->name . ' (' . $pet->size->weight_from . '-' . $pet->size->weight_to . ' kg)' : 'N/A' }}</p>
                                        <p><strong>Vaccinated:</strong> 
                                            <span class="label {{ $pet->is_vaccinated ? 'label-success' : 'label-danger' }}">
                                                {{ $pet->is_vaccinated ? 'Yes' : 'No' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Age:</strong> {{ $pet->age ?? 'N/A' }} Years</p>
                                        <p><strong>Weight:</strong> {{ $pet->weight ?? 'N/A' }} kg</p>
                                        <p><strong>Gender:</strong> {{ ucfirst($pet->gender ?? 'N/A') }}</p>
                                    </div>
                                </div>
                                <div class="mt-20">
                                    <label>Notes:</label>
                                    <p class="text-muted">{{ $pet->notes ?: 'No special notes.' }}</p>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4 class="text-theme-colored mb-20">Gallery</h4>
                        <div class="row multi-row-clearfix">
                            @forelse($pet->images as $img)
                            <div class="col-md-3 col-sm-4 col-xs-6 mb-20">
                                <div class="gallery-item">
                                    <a href="{{ $img->image }}" class="lightbox-image" title="{{ $pet->name }} Gallery">
                                        <img src="{{ $img->image }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="col-md-12">
                                <p class="text-muted">No gallery images found.</p>
                            </div>
                            @endforelse
                        </div>

                        <hr>
                        <h4 class="text-theme-colored mb-20">Documents</h4>
                        <div class="row">
                            @forelse($pet->documents as $doc)
                            <div class="col-md-6 mb-10">
                                <div class="p-10 border-1px d-flex justify-content-between align-items-center" style="display: flex; justify-content: space-between; align-items: center;">
                                    <span><i class="fa fa-file-text-o text-theme-colored mr-10"></i> {{ $doc->document_type }}</span>
                                    <a href="{{ $doc->document }}" target="_blank" class="btn btn-theme-colored btn-xs">View Document</a>
                                </div>
                            </div>
                            @empty
                            <div class="col-md-12">
                                <p class="text-muted">No documents uploaded.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
