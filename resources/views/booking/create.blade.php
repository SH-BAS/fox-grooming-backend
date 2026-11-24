@extends('layouts.web')

@section('title', 'Book Now | Fox Grooming')

@section('styles')
<style>
    .service-item { cursor: pointer; transition: all 0.3s; }
    .service-item.selected { border: 2px solid #5cb85c; background-color: #f9fff9; }
    .slot-item { cursor: pointer; padding: 10px; border: 1px solid #ddd; text-align: center; margin-bottom: 10px; transition: all 0.2s; }
    .slot-item:hover { background-color: #efefef; }
    .slot-item.selected { background-color: #5cb85c; color: white; border-color: #4cae4c; }
    #slots-container { max-height: 400px; overflow-y: auto; }
    
    .price-summary-table { width: 100%; margin-top: 15px; }
    .price-summary-table td { padding: 5px 0; }
    .price-summary-table .total-row { border-top: 2px solid #eee; font-weight: bold; font-size: 1.2em; }
    .terms-box { background: #f8f8f8; border: 1px solid #e5e5e5; border-radius: 6px; padding: 12px 15px; }
    .terms-box label { margin-bottom: 0; font-weight: 500; }
    .booking-actions { display: flex; gap: 10px; align-items: stretch; }
    .booking-actions .btn { flex: 1; }
    .modal-terms-content { max-height: 65vh; overflow-y: auto; line-height: 1.7; }
    .modal-terms-content p { margin-bottom: 12px; }
    .signature-box { background: #fff; border: 1px solid #e5e5e5; border-radius: 6px; padding: 12px 15px; }
    .signature-pad { width: 100%; height: 160px; border: 1px dashed #ccc; border-radius: 4px; background: #fff; touch-action: none; cursor: crosshair; }
    .signature-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
</style>
@endsection

@section('content')
<section class="inner-header divider parallax layer-overlay overlay-dark-5" data-bg-img="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&w=1920&q=80">
    <div class="container pt-70 pb-20">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="title text-white">Book Your Pet at {{ $franchise->name }}</h2>
                    <p class="text-white">Professional grooming at your convenience.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <form action="{{ route('web.user.bookings.store') }}" method="POST" id="booking-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="franchise_id" value="{{ $franchise->id }}">
            <input type="hidden" name="start_time" id="selected-start-time">
            <input type="hidden" name="out_of_area" value="{{ $outOfArea }}">
            @if($eSignaturePage)
                <input type="hidden" name="cms_page_id" value="{{ $eSignaturePage->id }}">
            @endif
            
            <div class="row">
                <!-- Step 1: Select Pet -->
                <div class="col-md-4">
                    <div class="border-1px p-20 mb-30">
                        <h4 class="text-theme-colored mt-0"><i class="fa fa-dog"></i> 1. Select Your Pet</h4>
                        <hr>
                        @if($pets->count() > 0)
                        <div class="form-group">
                            @foreach($pets as $pet)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="pet_id" value="{{ $pet->id }}" 
                                        data-category="{{ $pet->category_id }}" 
                                        data-category-name="{{ $pet->category->name }}" 
                                        data-size-id="{{ $pet->size_id }}"
                                        data-base-minutes="{{ $pet->category->minutes ?? 0 }}" required>
                                    <strong>{{ $pet->name }}</strong> ({{ $pet->category->name }})
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-danger">No pets found. <a href="{{ route('web.pets.create') }}">Add a pet first.</a></p>
                        @endif
                    </div>

                    <div id="services-section" style="display: none;">
                        <div class="border-1px p-20 mb-30">
                            <h4 class="text-theme-colored mt-0"><i class="fa fa-cut"></i> 2. Select Services</h4>
                            <hr>
                            <div id="services-container">
                                <!-- Dynamic Services will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Date & Time -->
                <div class="col-md-4">
                    <div id="date-section" style="display: none;">
                        <div class="border-1px p-20 mb-30">
                            <h4 class="text-theme-colored mt-0"><i class="fa fa-calendar"></i> 3. Choose Date</h4>
                            <hr>
                            <div class="form-group">
                                <input type="date" name="booking_date" id="booking_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="border-1px p-20 mb-30">
                            <h4 class="text-theme-colored mt-0"><i class="fa fa-clock"></i> 4. Available Slots</h4>
                            <hr>
                            <p id="slot-status" class="text-muted">Loading slots...</p>
                            <div id="slots-container" class="row">
                                <!-- Slots will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Summary & Confirm -->
                <div class="col-md-4">
                    <div id="summary-section" style="display: none;">
                        <div class="border-1px p-20 mb-30">
                            <h4 class="text-theme-colored mt-0"><i class="fa fa-list"></i> 5. Summary</h4>
                            <hr>
                            <div id="booking-summary">
                                <p><strong>Pet:</strong> <span id="summary-pet"></span></p>
                                <p><strong>Base Category:</strong> <span id="summary-category"></span></p>
                                <p><strong>Duration:</strong> <span id="summary-duration">0</span> Minutes</p>
                                <p><strong>Selected Services:</strong></p>
                                <ul id="summary-services" class="mb-10 small"></ul>
                                <p><strong>Arrival Estimate:</strong> <span id="summary-time" class="text-success font-weight-bold"></span></p>
                                
                                <table class="price-summary-table">
                                    <tr>
                                        <td>Base Price:</td>
                                        <td class="text-right">$<span id="price-base">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>Services:</td>
                                        <td class="text-right">$<span id="price-services">0.00</span></td>
                                    </tr>
                                    @if($outOfArea)
                                    <tr id="out-of-area-row">
                                        <td>Out of Area Fee:</td>
                                        <td class="text-right text-danger">$<span id="price-out-of-area">{{ number_format($tax->out_of_area_fee, 2) }}</span></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td class="text-right">$<span id="price-subtotal">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>Tax ({{ $tax->tax }}%):</td>
                                        <td class="text-right">$<span id="price-tax">0.00</span></td>
                                    </tr>
                                    <tr class="total-row text-theme-colored">
                                        <td>Total Amount:</td>
                                        <td class="text-right">$<span id="price-total">0.00</span></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <hr>
                            <div class="form-group mt-20">
                                <label for="user_notes">Additional Notes</label>
                                <textarea name="user_notes" id="user_notes" class="form-control" rows="3" placeholder="Special requirements..."></textarea>
                            </div>

                            <div class="terms-box mt-20">
                                <div class="checkbox mb-10">
                                    <label for="terms_accepted">
                                        <input type="checkbox" name="terms_accepted" id="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }}>
                                        I agree to the grooming release and terms.
                                    </label>
                                </div>
                                <p class="mb-0 small text-muted">
                                    Please review the agreement and sign below before confirming your booking.
                                </p>
                            </div>

                            <div class="signature-box mt-20">
                                <label class="font-weight-bold">Signature <span class="text-danger">*</span></label>
                                <canvas id="signature-pad" class="signature-pad"></canvas>
                                <div class="signature-actions">
                                    <small class="text-muted">Draw your signature in the box above.</small>
                                    <button type="button" class="btn btn-default btn-sm" id="clear-signature">Clear Signature</button>
                                </div>
                                <input type="file" name="image" id="signature-image" class="d-none" accept="image/png,image/jpeg,image/jpg,image/webp">
                            </div>

                            <div class="booking-actions mt-20">
                                <button type="button" class="btn btn-default btn-lg" data-toggle="modal" data-target="#termsModal">
                                    View Terms
                                </button>
                                <button type="submit" class="btn btn-theme-colored btn-lg" id="confirm-booking-btn" disabled>Confirm Booking</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="termsModalLabel">{{ $eSignaturePage->title ?? 'Release - Hold Harmless Agreement' }}</h4>
            </div>
            <div class="modal-body modal-terms-content">
                @if (!empty($eSignaturePage?->body))
                    {!! $eSignaturePage->body !!}
                @else
                    <p>
                        I am the owner or caregiver of this pet and I have read and understand the safety precautions above.
                        During the grooming service, I release Aussie Pet Mobile, Inc. and its independently owned and
                        operated franchisees from responsibility for accidents, medical problems, or other issues that may
                        arise while grooming my pet.
                    </p>
                    <p>
                        I understand that the terms of this agreement are subject to change without notice and will supersede
                        any prior signed contracts or releases. By authorizing the grooming of my pet, I hold Aussie Pet
                        Mobile and its employees, franchisees, or other representatives harmless from damage, loss, or claims
                        arising from any known or unknown condition of my pet. If my pet requires veterinary care during or
                        after grooming, I agree to pay any such fees.
                    </p>
                    <p>
                        Please note that all pets must be up to date with rabies vaccinations. Proof of vaccination may be
                        required before service is provided.
                    </p>
                    <p>Payment for grooming services is due at the time of service.</p>
                    <p>
                        You authorize Aussie Pet Mobile to send you emails and text messages. Photos of your pet may also be
                        used on social media, websites, or emails.
                    </p>
                    <p>
                        This release form represents Aussie Pet Mobile, LLC and its franchisees as an overarching release,
                        and local individually owned franchisees may have additional policies.
                    </p>
                    <p class="mb-0">
                        By checking the box on this page, you confirm that the information you provide is true and accurate
                        and that you agree to this release.
                    </p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const categoryStandards = @json($categoryStandards);
        const taxPercentage = {{ $tax->tax }};
        const outOfAreaFee = {{ $outOfArea ? $tax->out_of_area_fee : 0 }};
        
        let loadedServices = [];
        let totalDuration = 0;
        let baseMinutes = 0;
        let currentSizeId = null;
        let currentCategoryId = null;
        let hasSignatureDrawn = false;

        const signatureCanvas = document.getElementById('signature-pad');
        const signatureContext = signatureCanvas ? signatureCanvas.getContext('2d') : null;
        let isDrawingSignature = false;

        function resizeSignaturePad() {
            if (!signatureCanvas || !signatureContext) {
                return false;
            }

            const width = signatureCanvas.clientWidth;
            const height = signatureCanvas.clientHeight;

            if (width <= 0 || height <= 0) {
                return false;
            }

            const ratio = window.devicePixelRatio || 1;
            signatureCanvas.width = Math.floor(width * ratio);
            signatureCanvas.height = Math.floor(height * ratio);
            signatureContext.setTransform(1, 0, 0, 1, 0, 0);
            signatureContext.scale(ratio, ratio);
            signatureContext.lineWidth = 2;
            signatureContext.lineCap = 'round';
            signatureContext.strokeStyle = '#222';

            return true;
        }

        function getSignaturePoint(event) {
            const rect = signatureCanvas.getBoundingClientRect();
            const clientX = event.touches ? event.touches[0].clientX : event.clientX;
            const clientY = event.touches ? event.touches[0].clientY : event.clientY;

            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function startSignature(event) {
            if (!signatureCanvas) {
                return;
            }

            isDrawingSignature = true;
            const point = getSignaturePoint(event);
            signatureContext.beginPath();
            signatureContext.moveTo(point.x, point.y);
            event.preventDefault();
        }

        function drawSignature(event) {
            if (!isDrawingSignature || !signatureCanvas) {
                return;
            }

            const point = getSignaturePoint(event);
            signatureContext.lineTo(point.x, point.y);
            signatureContext.stroke();
            hasSignatureDrawn = true;
            updateConfirmButtonState();
            event.preventDefault();
        }

        function stopSignature() {
            isDrawingSignature = false;
        }

        function clearSignaturePad() {
            if (!signatureCanvas || !signatureContext) {
                return;
            }

            signatureContext.save();
            signatureContext.setTransform(1, 0, 0, 1, 0, 0);
            signatureContext.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            signatureContext.restore();
            $('#signature-image').val('');
            hasSignatureDrawn = false;
            updateConfirmButtonState();
        }

        function canvasToBlobPromise(canvas) {
            return new Promise(function(resolve, reject) {
                if (!canvas || canvas.width === 0 || canvas.height === 0) {
                    reject(new Error('Signature pad is not ready.'));
                    return;
                }

                if (typeof canvas.toBlob === 'function') {
                    canvas.toBlob(function(blob) {
                        if (blob) {
                            resolve(blob);
                            return;
                        }

                        try {
                            const dataUrl = canvas.toDataURL('image/png');
                            fetch(dataUrl)
                                .then(function(response) { return response.blob(); })
                                .then(resolve)
                                .catch(reject);
                        } catch (error) {
                            reject(error);
                        }
                    }, 'image/png');
                    return;
                }

                try {
                    const dataUrl = canvas.toDataURL('image/png');
                    fetch(dataUrl)
                        .then(function(response) { return response.blob(); })
                        .then(resolve)
                        .catch(reject);
                } catch (error) {
                    reject(error);
                }
            });
        }

        function initializeSignaturePad() {
            if (!signatureCanvas) {
                return;
            }

            if (!resizeSignaturePad()) {
                setTimeout(initializeSignaturePad, 150);
            }
        }

        function updateConfirmButtonState() {
            const hasSelectedSlot = $('#selected-start-time').val() !== '';
            const hasAcceptedTerms = $('#terms_accepted').is(':checked');
            const hasSignature = hasSignatureDrawn;
            $('#confirm-booking-btn').prop('disabled', !(hasSelectedSlot && hasAcceptedTerms && hasSignature));
        }

        // When pet is selected
        $('input[name="pet_id"]').on('change', function() {
            const petInput = $(this);
            currentCategoryId = petInput.data('category');
            currentSizeId = petInput.data('size-id');
            const categoryName = petInput.data('category-name');
            baseMinutes = parseInt(petInput.data('base-minutes')) || 0;
            
            $('#summary-pet').text(petInput.closest('label').find('strong').text());
            $('#summary-category').text(categoryName);
            $('#selected-start-time').val('');
            $('#summary-time').text('');
            
            loadServices(currentCategoryId);
            $('#services-section').fadeIn();
            $('#summary-section').fadeIn(function() {
                initializeSignaturePad();
            });
            
            calculatePrices();
            updateConfirmButtonState();
        });

        // Load services via AJAX
        function loadServices(categoryId) {
            $('#services-container').html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading services...</p>');
            $.ajax({
                url: "{{ route('web.user.bookings.getServices') }}",
                data: { category_id: categoryId },
                success: function(response) {
                    if(response.success && response.data.length > 0) {
                        loadedServices = response.data;
                        let html = '';
                        response.data.forEach(function(service) {
                            // Find price for current pet size
                            let price = 0;
                            if (service.pricing_type === 'fixable') {
                                const sizePrice = service.sizes.find(s => s.size_id === null);
                                price = sizePrice ? sizePrice.price : 0;
                            } else {
                                const sizePrice = service.sizes.find(s => s.size_id == currentSizeId);
                                price = sizePrice ? sizePrice.price : 0;
                            }

                            html += `
                                <div class="checkbox service-checkbox-item">
                                    <label>
                                        <input type="checkbox" name="services[]" value="${service.id}" 
                                            data-minutes="${service.minutes || 0}" 
                                            data-name="${service.name}"
                                            data-price="${price}">
                                        ${service.name} ($${parseFloat(price).toFixed(2)})
                                    </label>
                                </div>
                            `;
                        });
                        $('#services-container').html(html);
                        $('#date-section').fadeIn();
                    } else {
                        $('#services-container').html('<p class="text-muted">No specific services found for this category.</p>');
                        $('#date-section').fadeIn();
                    }
                }
            });
        }

        // When service is selected/unselected
        $(document).on('change', 'input[name="services[]"]', function() {
            calculatePrices();
            fetchSlots();
        });

        function calculatePrices() {
            // 1. Base Category Price
            let basePrice = 0;
            // Match category and size
            let match = categoryStandards.find(s => s.category_id == currentCategoryId && s.size_id == currentSizeId);
            if (!match) {
                // Match only category
                match = categoryStandards.find(s => s.category_id == currentCategoryId && (s.size_id == null || s.size_id == ''));
            }
            basePrice = match ? parseFloat(match.price) : 0;
            
            // 2. Services Price and Minutes
            let servicePrice = 0;
            let serviceMinutes = 0;
            let servicesList = '';
            
            $('input[name="services[]"]:checked').each(function() {
                servicePrice += parseFloat($(this).data('price')) || 0;
                serviceMinutes += parseInt($(this).data('minutes')) || 0;
                servicesList += `<li>${$(this).data('name')} ($${parseFloat($(this).data('price')).toFixed(2)})</li>`;
            });

            // 3. Totals
            const subtotal = basePrice + servicePrice + parseFloat(outOfAreaFee);
            const taxAmount = (subtotal * taxPercentage) / 100;
            const total = subtotal + taxAmount;
            totalDuration = baseMinutes + serviceMinutes;

            // 4. Update UI
            $('#price-base').text(basePrice.toFixed(2));
            $('#price-services').text(servicePrice.toFixed(2));
            $('#price-subtotal').text(subtotal.toFixed(2));
            $('#price-tax').text(taxAmount.toFixed(2));
            $('#price-total').text(total.toFixed(2));
            
            $('#summary-duration').text(totalDuration);
            $('#summary-services').html(servicesList);
        }

        // Fetch Slots
        $('#booking_date').on('change', fetchSlots);

        function fetchSlots() {
            const date = $('#booking_date').val();
            const franchiseId = "{{ $franchise->id }}";
            
            if(!date || totalDuration <= 0) return;

            $('#slot-status').show().text('Loading available slots...');
            $('#slots-container').html('');
            $('#selected-start-time').val('');
            $('#summary-time').text('');
            updateConfirmButtonState();

            $.ajax({
                url: "{{ route('web.user.bookings.getTimeSlots') }}",
                data: {
                    date: date,
                    franchise_id: franchiseId,
                    total_duration: totalDuration
                },
                success: function(response) {
                    if(response.success && response.slots.length > 0) {
                        $('#slot-status').text('Available time slots found:');
                        let html = '';
                        response.slots.forEach(function(slot) {
                            html += `
                                <div class="col-xs-6 col-sm-4">
                                    <div class="slot-item" data-time="${slot.start_time}">
                                        ${slot.display}
                                    </div>
                                </div>
                            `;
                        });
                        $('#slots-container').html(html);
                    } else {
                        $('#slot-status').text(response.message || 'No available slots for this duration on selected date.');
                    }
                }
            });
        }

        // When slot is selected
        $(document).on('click', '.slot-item', function() {
            $('.slot-item').removeClass('selected');
            $(this).addClass('selected');
            const time = $(this).data('time');
            $('#selected-start-time').val(time);
            $('#summary-time').text($(this).text().trim());
            updateConfirmButtonState();
        });

        $('#terms_accepted').on('change', updateConfirmButtonState);

        if (signatureCanvas) {
            signatureCanvas.addEventListener('mousedown', startSignature);
            signatureCanvas.addEventListener('mousemove', drawSignature);
            signatureCanvas.addEventListener('mouseup', stopSignature);
            signatureCanvas.addEventListener('mouseleave', stopSignature);
            signatureCanvas.addEventListener('touchstart', startSignature, { passive: false });
            signatureCanvas.addEventListener('touchmove', drawSignature, { passive: false });
            signatureCanvas.addEventListener('touchend', stopSignature);

            $('#clear-signature').on('click', clearSignaturePad);
            window.addEventListener('resize', function() {
                if (!hasSignatureDrawn) {
                    resizeSignaturePad();
                }
            });
        }

        $('#booking-form').on('submit', function(e) {
            e.preventDefault();

            if (!hasSignatureDrawn) {
                alert('Please provide your signature before confirming the booking.');
                return;
            }

            if (signatureCanvas.width === 0 || signatureCanvas.height === 0) {
                if (!resizeSignaturePad()) {
                    alert('Signature box is not ready yet. Please wait a moment and try again.');
                    return;
                }

                alert('Please sign again in the signature box.');
                hasSignatureDrawn = false;
                updateConfirmButtonState();
                return;
            }

            const form = this;
            const submitBtn = $('#confirm-booking-btn');
            submitBtn.prop('disabled', true).text('Submitting...');

            canvasToBlobPromise(signatureCanvas)
                .then(function(blob) {
                    const formData = new FormData(form);
                    formData.delete('image');
                    formData.append('image', blob, 'signature.png');

                    return fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html,application/xhtml+xml'
                        },
                        credentials: 'same-origin'
                    });
                })
                .then(function(response) {
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }

                    return response.text().then(function(html) {
                        document.open();
                        document.write(html);
                        document.close();
                    });
                })
                .catch(function() {
                    submitBtn.prop('disabled', false).text('Confirm Booking');
                    updateConfirmButtonState();
                    alert('Unable to save your signature. Please try again.');
                });
        });

        updateConfirmButtonState();
    });
</script>
@endsection
