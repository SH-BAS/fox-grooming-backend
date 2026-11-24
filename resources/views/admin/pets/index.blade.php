@extends('layouts.admin')

@section('title', 'Pets Management')
@section('page-title', 'Pets List')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="card-title">All Registered Pets</h4>
                    <a href="{{ route('admin.pets.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i>Add New Pet
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Pet Info</th>
                                    <th>Owner</th>
                                    <th>Category/Size</th>
                                    <th>Breed</th>
                                    <th>Age/Weight</th>
                                    <th class="text-center">Vaccinated</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pets as $pet)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 45px; height: 45px; overflow: hidden; border: 1px solid #eee;">
                                                    @if ($pet->profile_image)
                                                        <img src="{{ $pet->profile_image }}" alt="{{ $pet->name }}"
                                                            class="img-fluid w-100 h-100" style="object-fit: cover;">
                                                    @elseif ($pet->images->count() > 0)
                                                        <img src="{{$pet->images->first()->image }}" alt="{{ $pet->name }}"
                                                            class="img-fluid w-100 h-100" style="object-fit: cover;">
                                                    @else
                                                        @if ($pet->category && $pet->category->name == 'Dog')
                                                            <img src="{{ asset('assets/images/dog_defualt.jpg') }}"
                                                                alt="{{ $pet->name }}" class="img-fluid">
                                                        @else
                                                            <img src="{{ asset('assets/images/cat_defualt.jpg') }}"
                                                                alt="{{ $pet->name }}" class="img-fluid">
                                                        @endif
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-bold">{{ $pet->name }}</h6>
                                                    <small
                                                        class="text-muted text-uppercase">{{ $pet->type->name ?? 'Mixed' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $pet->user_id) }}" class="text-dark">
                                                <div class="fw-bold">{{ $pet->user->name ?? 'Unknown' }}</div>
                                                <small class="text-muted">{{ $pet->user->phone ?? 'No phone' }}</small>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-dark">
                                                <div class="fw-bold">{{ $pet->category->name ?? 'Unknown' }}</div>
                                                <small class="text-muted">{{ $pet->size->name ?? 'N/A' }}</small>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="text-dark">
                                                <div class="fw-bold">{{ $pet->type->name ?? 'Unknown' }}</div>
                                            </a>
                                        </td>
                                        <td>
                                            <div>{{ $pet->age }} Years</div>
                                            <small class="text-muted">{{ $pet->weight }} kg</small>
                                        </td>
                                        <td class="text-center">
                                            @if($pet->is_vaccinated)
                                                <span class="badge bg-success-light text-success px-3 rounded-pill">Yes</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger px-3 rounded-pill">No</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.pets.show', $pet->id) }}"
                                                    class="btn btn-info btn-xs sharp shadow-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.pets.edit', $pet->id) }}"
                                                    class="btn btn-primary btn-xs sharp shadow-sm" title="Edit Pet">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.pets.destroy', $pet->id) }}" method="POST"
                                                    data-confirm="Delete this pet and all related bookings? This action cannot be undone.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-xs sharp shadow-sm"
                                                        title="Delete Pet">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted mb-3"><i class="fas fa-paw fa-3x opacity-25"></i></div>
                                            <h5>No pets found</h5>
                                            <p class="mb-0">Start by adding a new pet to the system.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($pets->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        {{ $pets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-primary-light {
            background-color: rgba(var(--primary-rgb), 0.1);
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.1);
        }

        .bg-outline-info {
            border: 1px solid #17a2b8;
            background: transparent;
        }

        .bg-outline-secondary {
            border: 1px solid #6c757d;
            background: transparent;
        }

        .table thead th {
            font-size: 11px;
            letter-spacing: 0.5px;
            border-bottom-width: 0;
        }

        .btn-xs.sharp {
            width: 30px;
            height: 30px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
    </style>
@endpush