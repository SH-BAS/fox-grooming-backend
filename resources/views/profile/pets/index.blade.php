@extends('layouts.web')

@section('title', 'Pet Management | Fox Grooming')

@section('content')
    <section class="inner-header divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white">Pet Management</h2>
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
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="list-group-item text-danger">Logout</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="border-1px p-30">
                        <div class="row mb-20">
                            <div class="col-md-6">
                                <h3 class="text-theme-colored mt-0">My Pets</h3>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('web.pets.create') }}" class="btn btn-theme-colored btn-sm">Add New
                                    Pet</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @forelse($pets as $pet)
                                <div class="col-md-4 col-sm-6 mb-30">
                                    <div class="card text-center border-1px p-15">
                                        <img src="{{ $pet->profile_image }}" alt="{{ $pet->name }}" class="img-circle mb-10"
                                            style="width: 100px; height: 100px; object-fit: cover; margin: 0 auto;">
                                        <h4 class="mt-0 text-theme-colored">{{ $pet->name }}</h4>
                                        <p class="mb-5 text-muted">{{ $pet->category->name ?? 'N/A' }} |
                                            {{ $pet->type->name ?? 'N/A' }}
                                        </p>
                                        <p class="mb-15 font-12">{{ ucfirst($pet->gender ?? 'N/A') }}, {{ $pet->age ?? 'N/A' }}
                                            years</p>
                                        <div class="btn-group">
                                            <a href="{{ route('web.pets.show', $pet->id) }}"
                                                class="btn btn-theme-colored btn-xs"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('web.pets.edit', $pet->id) }}" class="btn btn-dark btn-xs"
                                                style="margin: 2px;"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('web.pets.destroy', $pet->id) }}" method="POST"
                                                style="display:inline;" data-confirm="Are you sure?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 text-center">
                                    <p>No pets found. <a href="{{ route('web.pets.create') }}" class="text-theme-colored">Add
                                            your first pet</a> to start booking!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection