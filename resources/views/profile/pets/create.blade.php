@extends('layouts.web')

@section('title', 'Add Pet | Fox Grooming')

@section('content')
    <section class="inner-header divider parallax layer-overlay overlay-dark-5"
        data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
        <div class="container pt-70 pb-20">
            <div class="section-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title text-white">Add New Pet</h2>
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
                        <h3 class="text-theme-colored mt-0">Pet Details</h3>
                        <hr>
                            <form action="{{ route('web.pets.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="profile_image">Profile Image</label>
                                    <input type="file" name="profile_image" id="profile_image" class="form-control">
                                    <small class="text-muted">Main photo of your pet</small>
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="name">Pet Name*</label>
                                    <input type="text" name="name" id="name" class="form-control" required
                                        placeholder="Buddy" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="category_id">Category*</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="category_type_id">Breed*</label>
                                    <select name="category_type_id" id="category_type_id" class="form-control" required>
                                        <option value="">Select Breed</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4" id="size_div" style="display: none;">
                                    <label for="size_id">Size*</label>
                                    <select name="size_id" id="size_id" class="form-control">
                                        <option value="">Select Size</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>
                                                {{ $size->name }} ({{ $size->weight_from }} - {{ $size->weight_to }} kg)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="age">Age (Years)*</label>
                                    <input type="number" step="0.1" name="age" id="age" class="form-control" placeholder="2" required value="{{ old('age') }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="weight">Weight (kg)*</label>
                                    <input type="number" step="0.1" name="weight" id="weight" class="form-control" placeholder="10" required value="{{ old('weight') }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="gender">Gender*</label>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="is_vaccinated">is Vaccinated?*</label>
                                    <select name="is_vaccinated" id="is_vaccinated" class="form-control" required>
                                        <option value="1" {{ old('is_vaccinated') == '1' ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('is_vaccinated') == '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Special Notes / Medical History</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any allergies or behavior notes...">{{ old('notes') }}</textarea>
                            </div>

                            <hr>
                            <h4 class="text-theme-colored">Gallery & Documents</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="images">Gallery Images (Multiple)</label>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">You can select multiple photos</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pet Documents</label>
                                    <div id="document-rows">
                                        <div class="row mb-10">
                                            <div class="col-md-5">
                                                <select name="document_types[]" class="form-control">
                                                    <option value="Vaccination Card">Vaccination Certificate</option>
                                                    <option value="Medical Record">Medical Record</option>
                                                    <option value="Registration">Registration Paper</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-7">
                                                <input type="file" name="documents[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-xs btn-default mt-5" id="add-more-docs">+ Add More Documents</button>
                                </div>
                            </div>

                            <div class="form-group mt-30">
                                <button type="submit" class="btn btn-theme-colored btn-lg">Save Pet Profile</button>
                                <a href="{{ route('web.pets.index') }}" class="btn btn-dark btn-lg">Back to List</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    $(document).ready(function() {
        $('#category_id').change(function() {
            var categoryId = $(this).val();
            var breedSelect = $('#category_type_id');
            var sizeDiv = $('#size_div');
            var sizeSelect = $('#size_id');
            
            breedSelect.html('<option value="">Loading breeds...</option>');
            
            if (categoryId) {
                $.ajax({
                    url: '{{ url("pets/get-breeds") }}/' + categoryId,
                    type: 'GET',
                    success: function(response) {
                        breedSelect.html('<option value="">Select Breed</option>');
                        $.each(response.breeds, function(key, breed) {
                            breedSelect.append('<option value="' + breed.id + '">' + breed.name + '</option>');
                        });
                        
                        if (response.is_sizeable) {
                            sizeDiv.show();
                            sizeSelect.attr('required', true);
                        } else {
                            sizeDiv.hide();
                            sizeSelect.removeAttr('required');
                        }
                    },
                    error: function() {
                        breedSelect.html('<option value="">Error loading breeds</option>');
                    }
                });
            } else {
                breedSelect.html('<option value="">Select Breed</option>');
                sizeDiv.hide();
            }
        });

        // Add more documents logic
        $('#add-more-docs').click(function() {
            var newRow = `
                <div class="row mb-10">
                    <div class="col-md-5">
                        <select name="document_types[]" class="form-control">
                            <option value="Vaccination Card">Vaccination Certificate</option>
                            <option value="Medical Record">Medical Record</option>
                            <option value="Registration">Registration Paper</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="file" name="documents[]" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            `;
            $('#document-rows').append(newRow);
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('.row').remove();
        });
    });
    </script>
@endsection