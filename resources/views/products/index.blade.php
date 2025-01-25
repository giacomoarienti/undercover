@php use App\Models\Brand;use App\Models\Color;use App\Models\Material; @endphp
@extends('layouts.app')

@section('content')

    <div class="row d-md-none mb-3 p-1">
        <!-- Button trigger modal -->
        <form class="w-100" action="{{ route('products.index') }}" method="GET" role="search">
            <div class="input-group rounded-pill">
                <span class="input-group-text"><span aria-hidden="true" class="fa-solid fa-magnifying-glass"></span></span>
                <input type="text" name="search" class="form-control" placeholder="Find your new identity"
                       value="{{$filters ? $filters['search'] ?? '' : ''}}">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#modal-filtersAccordion">
                    Filters <span aria-hidden="true" class="fa-solid fa-filter ms-2"></span>
                </button>
            </div>
        </form>


        <!-- Modal -->
        <div class="modal fade" id="modal-filtersAccordion" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Filters</h1>
                        <button type="button" class="btn btn-link"
                                onclick="window.location='{{ route('products.index', isset($filters['search']) ? ['search' => $filters['search']] : []) }}'">
                            reset
                        </button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form class="w-100" action="{{ route('products.index') }}" method="GET">
                        <input type="hidden" name="search" value="{{$filters['search'] ?? ''}}"/>
                        <div
                            class="modal-body container d-flex flex-column justify-content-start align-items-start">
                            <div class="accordion accordion-flush w-100">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#modal-brandAccordion">
                                            Brand
                                        </button>
                                    </h2>
                                    <div id="modal-brandAccordion" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @foreach (Brand::all() as $brand)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="brands[]"
                                                           value="{{$brand->slug}}"
                                                           id="modal-{{$brand->slug}}Checkbox" {{ in_array($brand->slug, $filters['brands'] ?? []) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                           for="modal-{{$brand->slug}}Checkbox">
                                                        {{$brand->name}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#modal-phoneAccordion">
                                            Model
                                        </button>
                                    </h2>
                                    <div id="modal-phoneAccordion" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <label for="modal-phone" class="d-none">Model name</label>
                                            <input type="text" placeholder="Model name" id="modal-phone"
                                                   name="phone" class="form-control"
                                                   value="{{ $filters['phone'] ?? '' }}"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#modal-materialAccordion">
                                            Material
                                        </button>
                                    </h2>
                                    <div id="modal-materialAccordion" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            @foreach (Material::all() as $material)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="materials[]" value="{{$material->slug}}"
                                                           id="modal-{{$material->slug}}Checkbox" {{ in_array($material->slug, $filters['materials'] ?? []) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                           for="modal-{{$material->slug}}Checkbox">
                                                        {{$material->name}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#modal-colorAccordion">
                                            Color
                                        </button>
                                    </h2>
                                    <div id="modal-colorAccordion" class="accordion-collapse collapse">
                                        <div
                                            class="accordion-body d-flex flex-row flex-wrap justify-content-between">
                                            @foreach (Color::all() as $color)
                                                <div class="container-fluid col-lg-6 p-1">
                                                    <input type="checkbox" name="colors[]" value="{{$color->slug}}"
                                                           class="btn-check" id="modal-{{$color->slug}}-btn-check"
                                                           autocomplete="off" {{ in_array($color->slug, $filters['colors'] ?? []) ? 'checked' : '' }}>
                                                    <label
                                                        class="btn btn-light border-dark-subtle text-nowrap text-start w-100"
                                                        for="modal-{{$color->slug}}-btn-check"><i
                                                            class="fa-solid fa-dot-circle me-2"
                                                            style="color: {{$color->rgb}};"></i>{{$color->name}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 p-3 border-top">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w-100">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-3 d-none d-md-flex flex-column justify-content-start align-items-start p-1">
            <form class="w-100" action="{{ route('products.index') }}" method="GET">
                <input type="hidden" name="search" value="{{$filters['search'] ?? ''}}"/>
                <div class="d-flex flex-column justify-content-start align-items-start">
                    <div
                        class="container d-flex flex-row flex-wrap justify-content-between align-items-center border-bottom">
                        <h1 class="d-inline h4">Filters</h1>
                        <button type="button" class="btn btn-link p-0 mb-2"
                                onclick="window.location='{{ route('products.index', isset($filters['search']) ? ['search' => $filters['search']] : []) }}'">
                            reset
                        </button>
                    </div>
                    <div class="accordion accordion-flush w-100">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#brandAccordion">
                                    Brand
                                </button>
                            </h2>
                            <div id="brandAccordion" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    @foreach (Brand::all() as $brand)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="brands[]"
                                                   value="{{$brand->slug}}"
                                                   id="{{$brand->slug}}Checkbox" {{ in_array($brand->slug, $filters['brands'] ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{$brand->slug}}Checkbox">
                                                {{$brand->name}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#phoneAccordion">
                                    Model
                                </button>
                            </h2>
                            <div id="phoneAccordion" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    <label for="phone" class="d-none">Model name</label>
                                    <input type="text" placeholder="Model name" id="phone" name="phone"
                                           class="form-control" value="{{ $filters['phone'] ?? '' }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#materialAccordion">
                                    Material
                                </button>
                            </h2>
                            <div id="materialAccordion" class="accordion-collapse collapse">
                                <div class="accordion-body">
                                    @foreach (Material::all() as $material)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="materials[]"
                                                   value="{{$material->slug}}"
                                                   id="{{$material->slug}}Checkbox" {{ in_array($material->slug, $filters['materials'] ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{$material->slug}}Checkbox">
                                                {{$material->name}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#colorAccordion">
                                    Color
                                </button>
                            </h2>
                            <div id="colorAccordion" class="accordion-collapse collapse">
                                <div class="accordion-body d-flex flex-row flex-wrap justify-content-between">
                                    @foreach (Color::all() as $color)
                                        <div class="container-fluid col-lg-6 p-1">
                                            <input type="checkbox" name="colors[]" value="{{$color->slug}}"
                                                   class="btn-check" id="{{$color->slug}}-btn-check"
                                                   autocomplete="off" {{ in_array($color->slug, $filters['colors'] ?? []) ? 'checked' : '' }}>
                                            <label
                                                class="btn btn-light border-dark-subtle text-nowrap text-start w-100"
                                                for="{{$color->slug}}-btn-check"><i
                                                    class="fa-solid fa-dot-circle me-2"
                                                    style="color: {{$color->rgb}};"></i>{{$color->name}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex w-100 p-3 border-top">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col d-flex flex-row flex-wrap justify-content-start">
            @foreach ($products as $product)
                <div class="container col-6 col-lg-3 p-1 m-0">
                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none h-100">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column justify-content-between align-items-start h-100">
                                <h1 class="card-title h5">{{$product->name}}</h1>
                                <h2 class="card-subtitle mb-2 text-muted h6">{{$product->phone->brand->name . ' ' . $product->phone->name}}</h2>
                                <img
                                    class="img-thumbnail bg-white h-100 object-fit-contain product-image align-self-center"
                                    src="{{$product->getFirstMediaUrl('images', 'thumb')}}" alt=""/>
                                <p class="align-self-end card-text text-muted text-end m-2">{{$product->price . '€'}}</p>
                                <div
                                    class="d-flex flex-row align-self-center flex-wrap justify-content-center bg-secondary-subtle border rounded-pill ms-2 me-2">
                                    @foreach ($product->specificProducts as $specificProduct)
                                        <span aria-hidden="true" class="fa-solid fa-circle fa-2xs m-1"
                                           style="color: {{$specificProduct->color->rgb}};">
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            {{ $products->links() }}
        </div>
    </div>

@endsection
