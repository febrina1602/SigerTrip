@php
    // Minimal safe fallback layout component.
    // Some older blades expect `components.layout.app` to exist — provide a simple wrapper.
@endphp

{{-- header is now included globally in layouts.app; this component should not include header to avoid duplication. --}}
<div class="container mt-4 mb-5">
    {{-- If the caller used sections, yield them; otherwise render any passed HTML via $slot if present. --}}
    @hasSection('content')
        @yield('content')
    @else
        {!! $slot ?? '' !!}
    @endif
</div>

@stack('scripts')
