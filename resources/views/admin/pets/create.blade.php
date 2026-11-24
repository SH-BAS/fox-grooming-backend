@extends('layouts.admin')

@section('title', 'Add New Pet')
@section('page-title', $selectedUser ? 'Add Pet for ' . $selectedUser->name : 'Create Pet')

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom">
                    <h4 class="card-title">
                        {{ $selectedUser ? 'Register New Pet for ' . $selectedUser->name : 'Register New Pet' }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (request('redirect_user_id'))
                            <input type="hidden" name="redirect_user_id" value="{{ request('redirect_user_id') }}">
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label font-weight-bold">Pet Profile Image <span class="text-danger">*</span></label>
                                <div class="bg-light p-3 rounded border text-center mb-2" style="border: 2px dashed #eee; cursor: pointer;" onclick="document.getElementById('profile_image').click();">
                                    <div id="profile_preview_container" class="mb-2">
                                        <i class="fas fa-user-circle fa-4x text-muted"></i>
                                    </div>
                                    <p class="mb-0 small text-muted">Click to select primary profile photo</p>
                                    <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*" onchange="previewProfileImage(this)">
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label font-weight-bold">Pet Gallery Images (Max 10) <span class="text-secondary small">(Optional)</span></label>
                                <div class="bg-light p-4 rounded border-dashed text-center mb-2" id="drop-zone" style="border: 2px dashed #ccc; cursor: pointer;">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                    <p class="mb-0">Click or drag images here to upload</p>
                                    <small class="text-muted">Allowed: JPG, PNG, WEBP (Max 2MB per image)</small>
                                    <input type="file" id="pet_images" name="images[]" class="d-none" multiple accept="image/*">
                                </div>
                                <div id="image_preview_container" class="row g-2 mt-2"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Pet Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Buddy" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold">Owner <span class="text-danger">*</span></label>
                                @if ($selectedUser)
                                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                                    <input type="text" class="form-control"
                                        value="{{ $selectedUser->name }} ({{ $selectedUser->email }})" readonly>
                                    <small class="text-muted">This pet will be added under the selected customer.</small>
                                @else
                                    <select name="user_id" id="user_id" class="form-control" required>
                                        <option value="">-- Select Owner --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Breed/Type <span class="text-danger">*</span></label>
                                <select name="category_type_id" id="category_type_id" class="form-control" required>
                                    <option value="">-- Select Breed --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('category_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3" id="size_category_wrapper" style="display: none;">
                                <label class="form-label font-weight-bold">Size Category <span class="text-danger">*</span></label>
                                <select name="size_id" id="size_id" class="form-control">
                                    <option value="">-- Select Size --</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Age (Years) <span class="text-danger">*</span></label>
                                <input type="number" name="age" class="form-control" value="{{ old('age', 0) }}" required min="0" step="0.1">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Weight (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="weight" class="form-control" value="{{ old('weight', 0) }}" required min="0" step="0.1">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" required>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label font-weight-bold d-block">Vaccination Status <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_vaccinated" id="vac_yes" value="1" {{ old('is_vaccinated', 1) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vac_yes">Vaccinated</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_vaccinated" id="vac_no" value="0" {{ old('is_vaccinated') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vac_no">Not Vaccinated</label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label font-weight-bold">Notes</label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="Any special needs or behavioral notes...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="text-end border-top pt-4">
                            <a href="{{ $selectedUser ? route('admin.users.show', $selectedUser->id) : route('admin.pets.index') }}"
                                class="btn btn-light me-2 rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow">Register Pet</button>
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

    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('pet_images');
        const container = document.getElementById('image_preview_container');
        let selectedFiles = [];

        // Image Upload Logic - FIX: Ensure click triggers selection
        if (dropZone && fileInput) {
            dropZone.addEventListener('click', (e) => {
                // Prevent duplicate triggering if clicking the input itself
                if (e.target !== fileInput) {
                    fileInput.click();
                }
            });
        }

        // Dynamic Loading Logic
        const categorySelect = document.getElementById('category_id');
        const breedSelect = document.getElementById('category_type_id');
        const sizeWrapper = document.getElementById('size_category_wrapper');
        const sizeSelect = document.getElementById('size_id');

        if (categorySelect && breedSelect) {
            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                
                // Clear breeds
                breedSelect.innerHTML = '<option value="">-- Loading --</option>';
                
                if (!categoryId) {
                    breedSelect.innerHTML = '<option value="">-- Select Category First --</option>';
                    if (sizeWrapper) $(sizeWrapper).fadeOut();
                    if (sizeSelect) sizeSelect.removeAttribute('required');
                    return;
                }

                fetch(`{{ route('admin.categories.breeds', ':id') }}`.replace(':id', categoryId))
                    .then(response => response.json())
                    .then(data => {
                        // Populate Breeds
                        breedSelect.innerHTML = '<option value="">-- Select Breed --</option>';
                        if (data.breeds) {
                            data.breeds.forEach(breed => {
                                breedSelect.innerHTML += `<option value="${breed.id}">${breed.name}</option>`;
                            });
                        }

                        // Handle Size Visibility
                        if (data.is_sizeable) {
                            if (sizeWrapper) $(sizeWrapper).fadeIn();
                            if (sizeSelect) sizeSelect.setAttribute('required', 'required');
                        } else {
                            if (sizeWrapper) $(sizeWrapper).fadeOut();
                            if (sizeSelect) {
                                sizeSelect.removeAttribute('required');
                                sizeSelect.value = '';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching breeds:', error);
                        breedSelect.innerHTML = '<option value="">-- Error loading breeds --</option>';
                    });
            });

            // Trigger initial state
            if (categorySelect.value) {
                categorySelect.dispatchEvent(new Event('change'));
            }
        }

        // Rest of Image Logic
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                handleFiles(Array.from(e.target.files));
            });
        }

        if (dropZone) {
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
        }

        function handleFiles(files) {
            const validImages = files.filter(file => file.type.startsWith('image/'));
            
            if (selectedFiles.length + validImages.length > 10) {
                alert('Maximum 10 images allowed.');
                return;
            }

            selectedFiles = [...selectedFiles, ...validImages];
            renderPreviews();
        }

        function renderPreviews() {
            if (!container) return;
            container.innerHTML = '';
            
            const dt = new DataTransfer();
            selectedFiles.forEach((file, index) => {
                dt.items.add(file);
                const reader = new FileReader();
                reader.onload = function(e) {
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
            if (fileInput) fileInput.files = dt.files;
        }

        if (container) {
            container.addEventListener('click', function(e) {
                const btn = e.target.closest('.remove-img');
                if (btn) {
                    const index = parseInt(btn.getAttribute('data-index'));
                    selectedFiles.splice(index, 1);
                    renderPreviews();
                }
            });
        }
    });
</script>
@endpush
@endsection
