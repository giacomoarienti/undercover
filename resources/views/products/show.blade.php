@extends('layouts.app')




<style>
    .rate{

        border-bottom-right-radius: 12px;
        border-bottom-left-radius: 12px;

    }

    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center
    }

    .rating>input {
        display: none
    }

    .rating>label {
        position: relative;
        width: 1em;
        font-size: 30px;
        font-weight: 300;
        color: #FFD600;
        cursor: pointer
    }

    .rating>label::before {
        content: "\2605";
        position: absolute;
        opacity: 0
    }

    .rating>label:hover:before,
    .rating>label:hover~label:before {
        opacity: 1 !important
    }

    .rating>input:checked~label:before {
        opacity: 1
    }

    .rating:hover>input:checked~label:before {
        opacity: 0.4
    }
</style>

@section('content')

    <div class="row border-bottom">
        <div class="col-md-6 p-3" style="max-height: 70vh">
            <div class="card h-100 p-0 bg-white p-0">
                <div class="card-body h-100 p-0 m-0">
                    <div id="imagesCarousel" class="carousel slide col-12 h-100 m-0" data-bs-theme="dark">
                        <div class="carousel-indicators">
                            <?php $imagesNumber = $product->getMedia('images')->count(); ?>
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
                <div>
                    <?php
                    $score = $product->reviewsAverage();
                    $stars = intdiv($score, 2);
                    $halfStar = ($score - (int)$score) >= 0.5;
                    $emptyStars = 5 - $stars - ($halfStar ? 1 : 0);
                    ?>
                    @for($i = 0; $i<$stars; $i++)
                        <i class="fa-solid fa-star text-body-emphasis"></i>
                    @endfor
                    @if($halfStar)
                        <i class="fa-solid fa-star-half-stroke text-body-emphasis"></i>
                    @endif
                    @for($i = 0; $i<$emptyStars; $i++)
                        <i class="fa-solid fa-star text-muted"></i>
                    @endfor
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
                    <a href="{{ route('products.edit', $product->slug) }}" class="btn btn-primary w-100"><i class="fa-solid fa-pencil me-2"></i>Edit</a>
                </div>
            @else
                <form>
                    <div class="border-bottom p-2">
                        <h2 class="h5">
                            Color
                        </h2>
                            <?php $n = 0 ?>
                        @foreach ($product->specificProducts as $specificProduct)
                            <input type="radio" class="btn-check" name="color" id="option{{++$n}}" autocomplete="off"
                                   value="{{$specificProduct->color->slug}}">
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
                                <button type="submit" class="btn btn-primary w-100">Add to cart</button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
    @can('review', $product)
        <div class="row border-bottom">
            <div class="col-12 p-3">
                <div class="card">
                    <div class="card-header">
                        {{ $userReview ? 'Edit your review' : 'Leave a review' }}
                    </div>
                    <div class="card-body">
                        <form action="{{ $userReview ? route('reviews.update', $userReview->id) : route('reviews.store') }}" method="POST">
                            @csrf
                            @if ($userReview)
                                @method('PUT')
                            @endif

                            <input type="hidden" name="product_id" value="{{$product->id}}"/>
                            <input type="hidden" name="user_id" value="{{$user->id}}"/>

                            <div class="mb-3">
                                <div class="rate">
                                    <label for="rating" class="form-label">Rating</label>
                                    <div class="d-flex flex-row justify-content-start align-items-center">
                                        <div class="rating">
                                            <input type="radio" name="stars" value="5" id="5"><label for="5">☆</label>
                                            <input type="radio" name="stars" value="4" id="4"><label for="4">☆</label>
                                            <input type="radio" name="stars" value="3" id="3"><label for="3">☆</label>
                                            <input type="radio" name="stars" value="2" id="2"><label for="2">☆</label>
                                            <input type="radio" name="stars" value="1" id="1"><label for="1">☆</label>
                                        </div>
                                        @error('stars') <i class="fa-regular fa-circle-exclamation text-danger ms-2"></i> @enderror
                                    </div>
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
                            <div class="mb-3 d-flex flex-row">
                                <button type="submit" class="btn btn-primary">{{ $userReview ? 'Edit' : 'Submit' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <div class="row">
        <div class="col-12 p-3">
            <h2 class="h5">Reviews</h2>
            @foreach($reviews as $review)

            @endforeach
        </div>
    </div>

@endsection
<script>
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
    });

</script>
