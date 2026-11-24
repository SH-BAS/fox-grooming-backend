@if(isset($message) && $franchises->isEmpty())
    <div class="col-md-12 text-center">
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> {{ $message }}
        </div>
    </div>
@elseif($franchises->isEmpty())
    <div class="col-md-12 text-center" style="padding: 100px 0;">
        <div class="search-prompt">
            <i class="fa fa-search fa-5x text-theme-colored mb-30" style="opacity: 0.3;"></i>
            <h3 class="text-theme-colored">Ready to Pamper Your Pet?</h3>
            <p class="font-18">Please enter your address above to see the nearest Fox Grooming branches available to serve you.</p>
        </div>
    </div>
@else
    @if(isset($out_of_area) && $out_of_area)
        <div class="col-md-12 text-center mb-20">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> We are currently out of your direct zone, but we can still serve you! An "Out
                of Area" fee of <strong>${{ number_format($out_of_area_fee, 2) }}</strong> will apply.
            </div>
        </div>
    @endif

    @foreach($franchises as $franchise)
        <div class="col-md-4 mb-30">
            <div class="franchise-card border-1px p-15 text-center">
                <img src="{{ $franchise->image }}" alt="{{ $franchise->name }}" class="img-thumbnail mb-15"
                    style="width: 100%; height: 200px; object-fit: cover;">
                <h3 class="mt-0 text-theme-colored">{{ $franchise->name }}</h3>
                <p class="mb-10"><i class="fa fa-map-marker text-theme-colored"></i> {{ $franchise->address }},
                    {{ $franchise->city }}</p>
                <a href="{{ route('web.user.bookings.create', $franchise->id) }}?out_of_area={{ isset($out_of_area) && $out_of_area ? 1 : 0 }}"
                    class="btn btn-theme-colored btn-block mt-10">Select This Branch</a>
            </div>
        </div>
    @endforeach
@endif