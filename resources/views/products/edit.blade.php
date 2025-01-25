@extends('layouts.app')

@section('content')
    <h1 class="text-primary">{{$product ? 'Edit product' : 'New product'}}</h1>
    <form class="m-0" id="form" action="{{ $product ? route('products.update', $product->slug) : route('products.store') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        @if ($product)
            @method("PATCH")
        @endif
        <div class="row">
            <div class="col">
                <div class="input-group p-1">
                    <label for="name" class="input-group-text col-sm-4">Name</label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name', $product?->name) }}"
                           maxlength="100"
                            {{$product ? 'disabled' : ''}}>
                </div>
                <div class="input-group p-1">
                    <label for="price" class="input-group-text col-sm-4">Price</label>
                    <input type="number"
                           min="0"
                           step="0.01"
                           class="form-control @error('price') is-invalid @enderror"
                           id="price"
                           name="price"
                           value="{{ old('price', $product?->price) }}">
                    <label for="price" class="input-group-text">€</label>
                </div>
            </div>

            <div class="col-md-8">
                <div class="input-group h-100 p-1">
                    <span class="input-group-text">Description</span>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $product?->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="input-group p-1 mt-2 rounded">
            <label for="brand" class="input-group-text">Brand</label>
            <input type="text" id="brand" list="brand_list" name="brand" value="{{ old('brand', $product?->phone?->brand?->name) }}" class="form-control @error('brand') is-invalid @enderror" {{$product ? 'disabled' : ''}}/>
            <datalist id="brand_list">
                @foreach(\App\Models\Brand::all() as $brand)
                    <option value="{{$brand->name}}"></option>
                @endforeach
            </datalist>
            <datalist id="phone_list">
                @foreach(\App\Models\Phone::query()->select("name")->distinct()->get() as $phone)
                    <option value="{{$phone['name']}}"></option>
                @endforeach
            </datalist>
            <label for="phone" class="input-group-text">Model</label>
            <input type="text" id="phone" list="phone_list" name="phone" value="{{ old('phone', $product?->phone?->name) }}" class="form-control @error('phone') is-invalid @enderror" {{$product ? 'disabled' : ''}}/>
        </div>
        <div class="input-group p-1 mt-2">
            <label for="material_id" class="input-group-text">Material</label>
            <select class="form-select @error('material_id') is-invalid @enderror" id="material_id" name="material_id">
                {{$oldMaterialId = old('material_id', $product?->material?->id)}}
                <option {{ $oldMaterialId ? "" : "selected" }} value=""></option>
                @foreach(\App\Models\Material::all() as $material)
                    <option value="{{$material->id}}" {{ $oldMaterialId == $material->id ? "selected" : "" }}>{{$material->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group d-flex flex-column p-1 mt-2">
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between align-items-center @error('images') bg-danger-subtle @enderror">
                    Product images
                    @error('images')
                    <span aria-hidden="true" class="fa-regular fa-circle-exclamation text-danger text-end"></span>
                    @enderror
                </div>
                <div class="card-body">
                    <div id="image-preview-container" class="d-flex flex-row align-items-center justify-items-start overflow-x-scroll">
                        @foreach($product?->getMedia('images') ?? [] as $image)
                            <div class="d-flex flex-column justify-content-between align-items-center m-2 col-2 flex-shrink-0" id="existing-image-{{$image->id}}">
                                <img src="{{$image->getUrl()}}" alt="" class="img-thumbnail m-2 h-100">
                                <button class="btn btn-danger m-2 mt-0" type="button" onclick="addDeleteImage({{$image->id}})">
                                    Delete
                                    <span aria-hidden="true" class="fa-solid fa-circle-minus"></span>
                                </button>
                            </div>
                        @endforeach
                        <button id="image-add" type="button" class="btn btn-success m-2" onclick="addImage()">
                            Add
                            <span aria-hidden="true" class="fa-solid fa-circle-plus"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group d-flex flex-column p-1 mt-2">
            <div class="card">
                <div class="card-header d-flex flex-row justify-content-between align-items-center @error('colors') bg-danger-subtle @enderror">
                    Available colors
                    @error('images')
                    <span aria-hidden="true" class="fa-regular fa-circle-exclamation text-danger text-end"></span>
                    @enderror
                </div>
                <div class="card-body">
                    @foreach(\App\Models\Color::all() as $color)
                        <div class="input-group mb-2">
                            <label for="color_{{ $color->id }}" class="input-group-text col-sm-3 text-wrap">
                                <span aria-hidden="true" class="fa-solid fa-circle fa-lg m-2 ms-0" style="color: {{ $color->rgb }};"></span>
                                {{ $color->name }}
                            </label>
                            <div class="input-group-text">
                                <input
                                    class="form-check-input mt-0 color-checkbox"
                                    id="color_{{ $color->id }}"
                                    name="colors[{{ $color->id }}][selected]"
                                    type="checkbox"
                                    value="1"
                                    {{ old('colors.' . $color->id . '.selected', $product?->hasColor($color)) ? 'checked' : ''}}>
                            </div>
                            <label for="quantity_{{ $color->id }}" class="input-group-text">
                                x
                            </label>
                            <input
                                type="number"
                                step="1"
                                min="0"
                                class="form-control color-quantity @error('colors.' . $color->id . '.quantity') is-invalid @enderror"
                                id="quantity_{{ $color->id }}"
                                name="colors[{{ $color->id }}][quantity]"
                                placeholder="Number of pieces available"
                                value="{{ old('colors.' . $color->id . '.quantity', $product?->hasColor($color) ? $product->specificProducts->where('color_id', $color->id)->first()->quantity : '') }}"
                                {{ old('colors.' . $color->id . '.quantity', $product?->hasColor($color) ? '' : 'disabled') }}>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="form-group d-flex flex-row p-1 mt-2">
            @if (!$product)
                <button type="button" class="btn btn-danger w-100 me-4" onclick="location.href='{{ url()->previous() }}'">Abort</button>
            @endif
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </div>
    </form>

    @if ($product)
        <form class="p-1" action="{{route('products.destroy', $product->slug)}}" method="POST">
            @method('DELETE')
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button type="submit" class="btn btn-danger w-100 me-2">Delete product</button>
        </form>
    @endif
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.color-checkbox');

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const quantityInput = document.getElementById(`quantity_${checkbox.id.split('_')[1]}`);
                if (checkbox.checked) {
                    quantityInput.removeAttribute('disabled');
                } else {
                    quantityInput.setAttribute('disabled', 'true');
                    quantityInput.value = '';
                }
            });
        });
    });
    function nextImage() {
        return document.getElementById('image-preview-container').children.length - 1;
    }
    function addImage() {
        let number = nextImage();
        let newImage = document.createElement('div');
        newImage.classList.add('d-flex', 'flex-column', 'justify-content-between','align-items-center', 'm-2', 'col-2', 'flex-shrink-0');

        let input = document.createElement('input');
        input.id = `image_${number}`;
        input.type = 'file';
        input.accept = 'image/*';
        input.name = 'images[]';
        input.hidden = true;

        newImage.appendChild(input);
        input.addEventListener('cancel', function () {
           newImage.remove();
        });
        input.onchange = function () {
            if (this.files && this.files[0]) {
                let img_preview = document.createElement('img');
                img_preview.src = URL.createObjectURL(this.files[0]);
                img_preview.alt = '';
                img_preview.classList.add('img-thumbnail', 'm-2', 'h-100');
                newImage.appendChild(img_preview);

                let button = document.createElement('button');
                button.classList.add('btn', 'btn-danger', 'm-2', 'mt-0');
                button.type = 'button';
                button.onclick = function () {
                    newImage.remove();
                };
                button.innerHTML = 'Delete\n<span aria-hidden="true" class="fa-solid fa-circle-minus"></i>';
                newImage.appendChild(button);
            }else{
                newImage.remove();
            }
        }
        input.click();
        document.getElementById('image-add').before(newImage);
    }
    function addDeleteImage(id) {
        document.getElementById(`existing-image-${id}`).remove();

        let deleteCommand = document.createElement('input');
        deleteCommand.type = 'hidden';
        deleteCommand.name = 'delete_images[]';
        deleteCommand.value = id;

        document.getElementById(`form`).appendChild(deleteCommand);
    }
</script>
