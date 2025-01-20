@extends('layouts.app')

@section('content')
    <h1>New product</h1>
    <form action="{{ $product ? route('products.update') : route('products.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col">
                <div class="input-group p-1">
                    <label for="name" class="input-group-text col-sm-4">Name</label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name', $product?->name) }}"
                           required
                           maxlength="100">
                </div>
                <div class="input-group p-1">
                    <label for="price" class="input-group-text col-sm-4">Price</label>
                    <input type="number"
                           min="0"
                           step="0.01"
                           class="form-control @error('price') is-invalid @enderror"
                           id="price"
                           name="price"
                           value="{{ old('price', $product?->price) }}"
                           required>
                    <label for="price" class="input-group-text">€</label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="input-group h-100 p-1">
                    <span class="input-group-text">Description</span>
                    <textarea class="form-control" name="description"></textarea>
                </div>
            </div>
        </div>
        <div class="input-group p-1 mt-2">
            <label for="brand" class="input-group-text">Brand</label>
            <input type="text" name="brand" class="form-control">

            <label for="model" class="input-group-text">Model</label>
            <input type="text" name="model" class="form-control">
        </div>
        <div class="form-group d-flex flex-column p-1 mt-2">
            <div class="card">
                <div class="card-header">
                    Product images
                </div>
                <div class="card-body">
                    <div id="image-preview-container" class="d-flex flex-row align-items-center justify-items-start overflow-x-scroll">
                        <div class="d-flex flex-column align-items-center m-2 col-2 flex-shrink-0">
                            <img src="https://cdn3.pixelcut.app/7/20/uncrop_hero_bdf08a8ca6.jpg" alt="" class="img-thumbnail m-2"/>
                            <button class="btn btn-danger m-2 mt-0" onclick="">
                                Delete
                                <i class="fa-solid fa-circle-minus"></i>
                            </button>
                        </div>
                        <div class="d-flex flex-column align-items-center m-2 col-2 flex-shrink-0">
                            <img src="https://cdn3.pixelcut.app/7/20/uncrop_hero_bdf08a8ca6.jpg" alt="" class="img-thumbnail m-2"/>
                            <button class="btn btn-danger m-2 mt-0" onclick="">
                                Delete
                                <i class="fa-solid fa-circle-minus"></i>
                            </button>
                        </div>
                        <div class="d-flex flex-column align-items-center m-2 col-2 flex-shrink-0">
                            <img src="https://cdn3.pixelcut.app/7/20/uncrop_hero_bdf08a8ca6.jpg" alt="" class="img-thumbnail m-2"/>
                            <button class="btn btn-danger m-2 mt-0" onclick="">
                                Delete
                                <i class="fa-solid fa-circle-minus"></i>
                            </button>
                        </div>
                        <div class="d-flex flex-column align-items-center m-2 col-2 flex-shrink-0">
                            <img src="https://cdn3.pixelcut.app/7/20/uncrop_hero_bdf08a8ca6.jpg" alt="" class="img-thumbnail m-2"/>
                            <button class="btn btn-danger m-2 mt-0" onclick="">
                                Delete
                                <i class="fa-solid fa-circle-minus"></i>
                            </button>
                        </div>
                        <button class="btn btn-success m-2 mt-0 col-2" onclick="">
                            Add
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    4/10 images added
                </div>
            </div>
        </div>
        <div class="form-group d-flex flex-column p-1 mt-2">
            <div class="card">
                <div class="card-header">
                    Available colors
                </div>
                <div class="card-body">
                    @foreach(\App\Models\Color::all() as $color)
                        <div class="input-group mb-2">
                            <label for="{{$color->span}}" class="input-group-text col-sm-3 text-wrap">
                                <i class="fa-solid fa-circle fa-lg m-2 ms-0" style="color: {{$color->rgb}};"></i>
                                {{' '.$color->name}}
                            </label>
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" id="{{$color->span}}" name="{{$color->span}}" type="checkbox" value="">
                            </div>
                            <span class="input-group-text">x</span>
                            <input type="text" class="form-control" placeholder="number of pieces available" name="{{$color->span}}_availability"/>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="form-group d-flex flex-column p-1 mt-2">
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </div>
    </form>
@endsection
