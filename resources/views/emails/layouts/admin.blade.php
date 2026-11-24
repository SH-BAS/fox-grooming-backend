@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<img src="https://pro-fixer.demoappprojects.com/fox-groomingv2/public/images/logo_black.png" class="logo"
    alt="{{ config('app.name') }}" style="width: 150px;">
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
    @slot('subcopy')
    @component('mail::subcopy')
    {{ $subcopy }}
    @endcomponent
    @endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
@endcomponent
@endslot
@endcomponent