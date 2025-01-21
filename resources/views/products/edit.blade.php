@extends('layouts.app')

@section('content')
    <h1>New product</h1>
    <form action="{{ $product ? route('products.update') : route('products.store') }}" method="POST" enctype="multipart/form-data">
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
            <input type="text" id="brand" name="brand" class="form-control" required>

            <label for="phone" class="input-group-text">Model</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>
        <div class="input-group p-1 mt-2">
            <label for="material_id" class="input-group-text">Material</label>
            <select class="form-select" id="material_id" name="material_id">
                <option selected value=""></option>
                @foreach(\App\Models\Material::all() as $material)
                    <option value="{{$material->id}}">{{$material->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group d-flex flex-column p-1 mt-2">
            <div class="card">
                <div class="card-header">
                    Product images
                </div>
                <div class="card-body">
                    <div id="image-preview-container" class="d-flex flex-row align-items-center justify-items-start overflow-x-scroll">
                        <button id="image-add" type="button" class="btn btn-success m-2" onclick="addImage()">
                            Add
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                    </div>
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
                            <label for="color_{{ $color->id }}" class="input-group-text col-sm-3 text-wrap">
                                <i class="fa-solid fa-circle fa-lg m-2 ms-0" style="color: {{ $color->rgb }};"></i>
                                {{ $color->name }}
                            </label>
                            <div class="input-group-text">
                                <input
                                    class="form-check-input mt-0 color-checkbox"
                                    id="color_{{ $color->id }}"
                                    name="colors[{{ $color->id }}][selected]"
                                    type="checkbox"
                                    {{$product?->hasColor($color) ? 'checked' : ''}}>
                            </div>
                            <label for="quantity_{{ $color->id }}" class="input-group-text">
                                x
                            </label>
                            <input
                                type="number"
                                step="1"
                                min="0"
                                class="form-control color-quantity"
                                id="quantity_{{ $color->id }}"
                                name="colors[{{ $color->id }}][quantity]"
                                placeholder="Number of pieces available"
                                {{$product?->hasColor($color) ? '' : 'disabled'}}>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="form-group d-flex flex-row p-1 mt-2">
            <button type="submit" class="btn btn-success w-100 me-2">Save</button>
            <button type="button" class="btn btn-danger w-100 ms-2" onclick="location.href='{{ url()->previous() }}'">Abort</button>
        </div>
    </form>
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
                button.innerHTML = 'Delete\n<i class="fa-solid fa-circle-minus"></i>';
                newImage.appendChild(button);
            }else{
                newImage.remove();
            }
        }
        input.click();
        document.getElementById('image-add').before(newImage);
    }
</script>
