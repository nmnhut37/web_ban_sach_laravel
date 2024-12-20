<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators">
        @foreach ($banners as $index => $banner)
            <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">
        @foreach ($banners as $index => $banner)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                @if($banner->url)
                    <a href="{{ $banner->url }}" target="_blank">
                        <img class="d-block w-100" src="{{ asset('storage/images/banner/' . $banner->image) }}" alt="Slide {{ $index + 1 }}" style="object-fit: cover; height: 500px;">
                    </a>
                @else
                    <img class="d-block w-100" src="{{ asset('storage/images/banner/' . $banner->image) }}" alt="Slide {{ $index + 1 }}" style="object-fit: cover; height: 500px;">
                @endif
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>