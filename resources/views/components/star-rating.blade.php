@if($averageRating > 0)
    <div class="stars-rating">
        @for($i = 1; $i <= 5; $i++)
            <i class="bi bi-star{{ $i <= $averageRating ? '-fill' : '' }} text-warning"></i>
        @endfor
        <span>{{ number_format($averageRating, 1) }} / 5</span>
    </div>
@endif
