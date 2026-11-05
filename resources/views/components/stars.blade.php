@props(['rating' => 5, 'class' => 'h-3.5 w-3.5'])

<div class="flex items-center gap-1 text-gold-500" role="img" aria-label="{{ $rating }} out of 5 stars">
    @for ($i = 1; $i <= 5; $i++)
        <svg class="{{ $class }} {{ $i <= $rating ? '' : 'opacity-25' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17l-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"/>
        </svg>
    @endfor
</div>
