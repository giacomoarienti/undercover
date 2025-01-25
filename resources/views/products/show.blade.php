@extends('layouts.app')
@php
    function generateRatingStars(float $rating): array
    {
        $stars = [];
        $normalizedRating = $rating / 2; // Normalize the rating to a 0-5 scale
        for ($i = 0; $i < 5; $i++) {
            if ($normalizedRating >= 1) {
                $stars[] = 'star-full';
                $normalizedRating -= 1;
            } elseif ($normalizedRating >= 0.5) {
                $stars[] = 'star-half';
                $normalizedRating -= 0.5;
            } else {
                $stars[] = 'star-empty';
            }
        }
        return $stars;
    }
@endphp



@section('content')
    <div class="row border-bottom">
        <div class="col-md-6 p-3">
            <div class="card h-100 p-0 bg-white p-0">
                <div class="card-body h-100 p-0 m-0">
                    <div id="imagesCarousel" class="carousel slide col-12 h-100 m-0" data-bs-theme="dark">
                        <div class="carousel-indicators">
                            @php
                                $imagesNumber = $product->getMedia('images')->count();
                            @endphp
                            @for($i=0; $i<$imagesNumber; $i++)
                                <button type="button" data-bs-target="#imagesCarousel" data-bs-slide-to="{{$i}}"
                                        class="{{$i==0?'active':''}}"></button>
                            @endfor
                        </div>
                        <div class="carousel-inner h-100 p-5">
                            <?php $first = true; ?>
                            @foreach ($product->getMedia('images') as $media)
                                <div class="carousel-item {{$first ? 'active' : ''}} h-100">
                                    <img src="{{$media->getUrl()}}" class="d-block w-100 h-100 object-fit-contain"
                                         alt="">
                                </div>
                                    <?php if ($first) $first = false; ?>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#imagesCarousel"
                                data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imagesCarousel"
                                data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 p-3">
            <div class="border-bottom p-2">
                <h1 class="mb-0">{{$product->name}}</h1>
                <h2 class="text-muted h6">{{$product->phone->brand->name . ' ' . $product->phone->name}}</h2>
                <div class="d-flex flex-row justify-content-start align-items-center">
                    @foreach (generateRatingStars($product->reviewsAverage()) as $star)
                        <img class="review-star-normal" src="{{ Storage::url('public/' . $star . '.svg') }}" alt="">
                    @endforeach
                </div>
            </div>

            <div class="border-bottom p-2">
                <p class="text-wrap text-break m-0">{{$product->description}}</p>
            </div>
            @if($user->is_seller)
                <div class="border-bottom p-2">
                    <h2 class="h5">
                        Colors
                    </h2>
                    @foreach ($product->specificProducts as $specificProduct)
                        <div class="btn btn-secondary rounded-pill me-1 mb-1"><i
                                class="fa-solid fa-circle me-1"
                                style="color: {{$specificProduct->color->rgb}};"></i>{{$specificProduct->color->name}}
                        </div>
                    @endforeach
                </div>
                <div class="border-bottom p-2">
                    <a href="{{ route('products.edit', $product->slug) }}" class="btn btn-primary w-100"><span aria-hidden="true" class="fa-solid fa-pencil me-2"></span>Edit</a>
                </div>
            @else
                <div class="border-bottom p-2">
                    <h2 class="h5">
                        Color
                    </h2>
                        <?php $n = 0 ?>
                    @foreach ($product->specificProducts as $specificProduct)
                        <input type="radio" class="btn-check" name="color" id="option{{++$n}}" autocomplete="off"
                               value="{{$specificProduct->id}}">
                        <label class="btn btn-secondary rounded-pill me-1 mb-1" for="option{{$n}}"><i
                                class="fa-solid fa-circle me-1"
                                style="color: {{$specificProduct->color->rgb}};"></i>{{$specificProduct->color->name}}
                        </label>
                    @endforeach
                </div>
                <div class="border-bottom p-2">
                    <h2 class="h5">
                        <label for="quantity">Amount</label>
                    </h2>
                    <input type="number" step="1" min="0" class="form-control" id="quantity" name="quantity"
                           value="{{old('quantity', 1)}}">
                </div>
                <div class="p-2">
                    <div class="row">
                        <div class="col-6">
                            <h2 class="h5">
                                Total
                            </h2>
                            <p class="h2 mb-0 text-muted" id="total"></p>
                        </div>
                        <div class="col-6 d-flex flex-column justify-content-center align-items-end">
                            <button id="add-to-cart" type="button" class="btn btn-primary w-100">Add to cart</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if($userReview or $user->can('review', $product))
        <div class="row border-bottom">
            <div class="col-12 p-3">
                <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between align-items-center">
                        {{ $userReview ? 'Your review' : 'Leave a review' }}
                        @if ($userReview)
                            <form action="{{route('reviews.destroy', $userReview->id)}}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><span aria-hidden="true" class="fa-solid fa-trash me-2"></span>Delete</button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="{{ $userReview ? route('reviews.update', $userReview->id) : route('reviews.store') }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @if ($userReview)
                                @method('PUT')
                            @endif

                            <input type="hidden" name="product_id" value="{{$product->id}}"/>
                            <input type="hidden" name="user_id" value="{{$user->id}}"/>

                            <div class="d-flex flex-column align-items-start mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <div class="d-flex flex-column justify-content-start align-items-center">
                                    <div class="flex-row justify-content-start align-items-center" id="rating-stars" style="margin-bottom: -17.5px;"></div>
                                    <input type="range" min="0" max="10" class="opacity-0 w-100 @error('rating') is-invalid @enderror" id="rating" name="stars" value="{{ old('stars', $userReview->stars ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $userReview->title ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="body" class="form-label">Body</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="3">{{ old('body', $userReview->body ?? '') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">@if($userReview)<span aria-hidden="true" class="fa-solid fa-pencil me-2"></span>@endif{{ $userReview ? 'Edit' : 'Submit' }}</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12 p-3">
            <h2 class="h5 mb-3">Reviews</h2>
                <div class="d-flex flex-column">
                    @foreach($reviews as $review)
                        <div class="card mb-3">
                            <div class="card-body d-flex flex-column justify-content-start align-items-start">
                                <div class="d-flex w-100 flex-row justify-content-between align-items-center mb-2">
                                    <h1 class="card-title h5 mb-0">
                                        {{$review->title}}
                                    </h1>
                                    <div class="d-flex flex-row justify-content-start align-items-center">
                                        @foreach (generateRatingStars($review->stars) as $star)
                                            <img class="review-star-normal" src="{{ Storage::url('public/' . $star . '.svg') }}" alt="">
                                        @endforeach
                                    </div>
                                </div>
                                <p class="card-text">{{$review->body}}</p>
                            </div>
                            <div class="card-footer">
                                <p class="card-text text-muted">{{$review->user->name}} - {{$review->created_at->diffForHumans()}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            {{$reviews->links()}}
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/views/products.show.js')
    @endpush

    <script>
        function generateRatingStars(rating) {
            const normalizedRating = rating / 2;
            let starsHTML = '';
            let remainingRating = normalizedRating;

            for (let i = 0; i < 5; i++) {
                if (remainingRating >= 1) {
                    starsHTML += '<img class="review-star-big" src="/storage/star-full.svg" alt="">';
                    remainingRating -= 1;
                } else if (remainingRating >= 0.5) {
                    starsHTML += '<img class="review-star-big" src="/storage/star-half.svg" alt="">';
                    remainingRating -= 0.5;
                } else {
                    starsHTML += '<img class="review-star-big" src="/storage/star-empty.svg" alt="">';
                }
            }

            return starsHTML;
        }
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const totalElement = document.getElementById('total');
            const price = parseFloat("{{$product->price}}");

            function updateTotal() {
                const quantity = parseInt(quantityInput.value, 10) || 0;
                const total = (price * quantity).toFixed(2);
                totalElement.textContent = `${total}€`;
            }
            quantityInput.addEventListener('input', updateTotal);
            updateTotal();

            const ratingInput = document.getElementById('rating');
            const ratingStars = document.getElementById('rating-stars');
            function updateRatingStars() {
                const rating = parseFloat(ratingInput.value) || 0;
                ratingStars.innerHTML = generateRatingStars(rating);
            }
            ratingInput.addEventListener('input', updateRatingStars);
            updateRatingStars();
        });
    </script>

@endsection
