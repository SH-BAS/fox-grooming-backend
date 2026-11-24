@extends('layouts.admin')

@section('title', 'Edit Pet - ' . $pet->name)
@section('page-title', $redirectUser ? 'Edit Pet for ' . $redirectUser->name : 'Edit Pet')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Update Pet Details</h4>
                    <!-- back left arrow -->
                    <a href="{{ $redirectUser ? route('admin.users.show', $redirectUser->id) : route('admin.pets.index') }}" class="btn btn-primary btn-xs sharp shadow-sm"><i
                            class="fas fa-arrow-left"></i></a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @if ($redirectUser)
                            <input type="hidden" name="redirect_user_id" value="{{ $redirectUser->id }}">
                        @endif

                        <div class="row">
                            <!-- Pet Profile Image -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label font-weight-bold">Pet Profile Image <span class="text-danger">*</span></label>
                                <div class="bg-light p-3 rounded border text-center mb-2" style="border: 2px dashed #eee; cursor: pointer;" onclick="document.getElementById('profile_image').click();">
                                    <div id="profile_preview_container" class="mb-2">
                                        @if($pet->profile_image)
                                            <img src="{{ $pet->profile_image }}" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #fff;">
                                        @else
                                            <i class="fas fa-user-circle fa-4x text-muted"></i>
                                        @endif
                                    </div>
                                    <p class="mb-0 small text-muted">Click to change primary profile photo</p>
                                    <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*" onchange="previewProfileImage(this)">
                                </div>
                            </div>

                            <!-- Existing Gallery -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label font-weight-bold">Existing Gallery</label>
                                <div class="row g-2 mb-3" id="existing_gallery">
                                    @forelse($pet->images as $image)
                                        <div class="col-md-2 col-sm-3 col-4 mb-2 existing-image-wrapper"
                                            data-id="{{ $image->id }}">
                                            <div class="position-relative border rounded overflow-hidden shadow-sm"
                                                style="height: 100px;">
                                                <img src="{{ $image->image }}" class="w-100 h-100" style="object-fit: cover;">
                                                <button type="button"
                                                    class="btn btn-danger btn-xs position-absolute top-0 end-0 m-1 rounded-circle delete-existing-img"
                                                    data-id="{{ $image->id }}"
                                                    style="width: 22px; height: 22px; padding: 0; line-height: 22px; z-index: 10;">
                                                    <i class="fas fa-trash-alt" style="font-size: 10px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted small">No images in gallery.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <label class="form-label font-weight-bold">Add New Images (Max 10 total) <span
                                        class="text-secondary small">(Optional)</span></label>
                                <div class="bg-light p-4 rounded border-dashed text-center mb-2" id="drop-zone"
                                    style="border: 2px dashed #ccc; cursor: pointer;">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <p class="mb-0">Click or drag images here to upload</p>
                                    <small class="text-muted">Allowed: JPG, PNG, WEBP (Max 2MB per image)</small>
                                    <input type="file" id="pet_images" name="images[]" class="d-none" multiple
                                        accept="image/*">
                                </div>
                                <div id="image_preview_container" class="row g-2 mt-2"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Pet Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $pet->name) }}"
                                    placeholder="e.g. Buddy" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Owner <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-control select2" required>
                                    <option value="">-- Select Owner --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $pet->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Category <span
                                        class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $pet->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Breed/Type <span
                                        class="text-danger">*</span></label>
                                <select name="category_type_id" id="category_type_id" class="form-control" required>
                                    <option value="">-- Select Breed --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('category_type_id', $pet->category_type_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3" id="size_category_wrapper"
                                style="{{ $pet->category && $pet->category->pricing_type === 'sizeable' ? '' : 'display: none;' }}">
                                <label class="form-label font-weight-bold">Size Category <span
                                        class="text-danger">*</span></label>
                                <select name="size_id" id="size_id" class="form-control" {{ $pet->category && $pet->category->pricing_type === 'sizeable' ? 'required' : '' }}>
                                    <option value="">-- Select Size --</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ old('size_id', $pet->size_id) == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Age (Years) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="age" class="form-control" value="{{ old('age', $pet->age) }}"
                                    required min="0" step="0.1">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Weight (kg) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="weight" class="form-control"
                                    value="{{ old('weight', $pet->weight) }}" required min="0" step="0.1">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="male" {{ old('gender', $pet->gender) == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ old('gender', $pet->gender) == 'female' ? 'selected' : '' }}>
                                        Female</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label font-weight-bold d-block">Vaccination Status <span
                                        class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_vaccinated" id="vac_yes" value="1"
                                        {{ old('is_vaccinated', $pet->is_vaccinated) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vac_yes">Vaccinated</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_vaccinated" id="vac_no" value="0"
                                        {{ old('is_vaccinated', $pet->is_vaccinated) == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vac_no">Not Vaccinated</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label font-weight-bold">Notes</label>
                                <textarea name="notes" class="form-control" rows="4"
                                    placeholder="Any special needs or behavioral notes...">{{ old('notes', $pet->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="text-end border-top pt-4">
                            <a href="{{ $redirectUser ? route('admin.users.show', $redirectUser->id) : route('admin.pets.index') }}"
                                class="btn btn-light me-2 rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow">Update Pet
                                Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile_preview_container').innerHTML = 
                    `<img src="${e.target.result}" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #fff;">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
                const dropZone = document.getElementById('drop-zone');
                const fileInput = document.getElementById('pet_images');
                const container = document.getElementById('image_preview_container');
                let selectedFiles = [];

                // Dynamic Loading Logic
                const categorySelect = document.getElementById('category_id');
                const breedSelect = document.getElementById('category_type_id');
                const sizeWrapper = document.getElementById('size_category_wrapper');
                const sizeSelect = document.getElementById('size_id');

                let isInitialLoad = true;

                categorySelect.addEventListener('change', function () {
                    const categoryId = this.value;
                    const currentBreedId = breedSelect.value;

                    if (!categoryId) {
                        breedSelect.innerHTML = '<option value="">-- Select Category First --</option>';
                        $(sizeWrapper).fadeOut();
                        sizeSelect.removeAttribute('required');
                        return;
                    }

                    // Don't fetch on initial load if we already have breeds (backend pre-rendered them)
                    if (isInitialLoad) {
                        isInitialLoad = false;
                        return;
                    }

                    // Clear and Load Breeds
                    breedSelect.innerHTML = '<option value="">-- Loading --</option>';
                    // set route admin.categories.breeds
                    fetch(`{{ route('admin.categories.breeds', ':id') }}`.replace(':id', categoryId))
                        .then(response => response.json())
                        .then(data => {
                            // Populate Breeds
                            breedSelect.innerHTML = '<option value="">-- Select Breed --</option>';
                            data.breeds.forEach(breed => {
                                const selected = (breed.id == currentBreedId) ? 'selected' : '';
                                breedSelect.innerHTML += `<option value="${breed.id}" ${selected}>${breed.name}</option>`;
                            });

                            // Handle Size Visibility
                            if (data.is_sizeable) {
                                $(sizeWrapper).fadeIn();
                                sizeSelect.setAttribute('required', 'required');
                            } else {
                                $(sizeWrapper).fadeOut();
                                sizeSelect.removeAttribute('required');
                                sizeSelect.value = '';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching breeds:', error);
                            breedSelect.innerHTML = '<option value="">-- Error loading breeds --</option>';
                        });
                });

                // Image Gallery Management Logic
                document.querySelectorAll('.delete-existing-img').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const imageId = this.getAttribute('data-id');
                        const wrapper = document.querySelector(`.existing-image-wrapper[data-id="${imageId}"]`);
                        if (window.confirmDelete) {
                            window.confirmDelete('Are you sure you want to delete this image permanently?').then(function (ok) {
                                if (!ok) return;
                                deletePetImage(imageId, wrapper);
                            });
                        } else if (confirm('Are you sure you want to delete this image permanently?')) {
                            deletePetImage(imageId, wrapper);
                        }
                    });
                });

                function deletePetImage(imageId, wrapper) {
                    const url = "{{ route('admin.pets.deleteImage', ':id') }}".replace(':id', imageId);
                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                wrapper.remove();
                                if (document.querySelectorAll('.existing-image-wrapper').length === 0) {
                                    document.getElementById('existing_gallery').innerHTML = '<div class="col-12"><p class="text-muted small">No images in gallery.</p></div>';
                                }
                            } else {
                                alert('Error deleting image. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Something went wrong.');
                        });
                }

                // 2. Handle New Multi-Upload
                dropZone.addEventListener('click', () => fileInput.click());

                fileInput.addEventListener('change', function (e) {
                    handleFiles(Array.from(e.target.files));
                });

                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.style.borderColor = '#007bff';
                });

                dropZone.addEventListener('dragleave', () => {
                    dropZone.style.borderColor = '#ccc';
                });

                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.style.borderColor = '#ccc';
                    handleFiles(Array.from(e.dataTransfer.files));
                });

                function handleFiles(files) {
                    const validImages = files.filter(file => file.type.startsWith('image/'));
                    const currentTotal = document.querySelectorAll('.existing-image-wrapper').length + selectedFiles.length;

                    if (currentTotal + validImages.length > 15) { // Adjusted limit to 15 for flexibility
                        alert('Maximum 15 images allowed total.');
                        return;
                    }

                    selectedFiles = [...selectedFiles, ...validImages];
                    renderPreviews();
                }

                function renderPreviews() {
                    container.innerHTML = '';

                    const dt = new DataTransfer();
                    selectedFiles.forEach((file, index) => {
                        dt.items.add(file);
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3 col-sm-4 col-6 mb-2';
                            col.innerHTML = `
                                                        <div class="position-relative border rounded overflow-hidden shadow-sm" style="height: 120px;">
                                                            <img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">
                                                            <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 m-1 rounded-circle remove-img" data-index="${index}" style="width: 22px; height: 22px; padding: 0; line-height: 22px; z-index: 10;">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    `;
                            container.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    });
                    fileInput.files = dt.files;
                }

                container.addEventListener('click', function (e) {
                    const btn = e.target.closest('.remove-img');
                    if (btn) {
                        const index = parseInt(btn.getAttribute('data-index'));
                        selectedFiles.splice(index, 1);
                        renderPreviews();
                    }
                });
            });
        </script>
    @endpush
@endsection