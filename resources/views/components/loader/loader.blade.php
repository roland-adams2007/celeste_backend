{{--
    Usage: <x-loader.loader />
    Props:
      :show   = "true/false"   — toggle visibility (default true)
      size    = "sm|md|lg|xl"  — loader size      (default md)
      message = "Loading..."   — text below logo  (default empty)
      overlay = "true/false"   — full-screen backdrop (default true)
--}}
@props([
    'show'    => true,
    'size'    => 'md',
    'message' => '',
    'overlay' => true,
])

@if ($show)
    <div
        class="x-loader-root {{ $overlay ? 'x-loader-overlay' : '' }}"
        role="status"
        aria-label="{{ $message ?: 'Loading' }}"
        aria-live="polite"
    >
        <div class="x-loader-box x-loader-size-{{ $size }}">
            <x-loader.svg />

            @if ($message)
                <p class="x-loader-message">{{ $message }}</p>
            @endif
        </div>
    </div>
@endif
