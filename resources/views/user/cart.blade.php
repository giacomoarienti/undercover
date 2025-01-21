@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-primary">Cart</h1>

    <div id="cart-contents">
        <div class="text-center py-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading cart contents...</span>
            </div>
        </div>
    </div>

    <template id="empty-cart-template">
        <h2 class="h4">Your cart is empty</h2>
        <p>Browse our products to add items to your cart.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            Continue Shopping
            <span class="visually-hidden">- browse our product catalog</span>
        </a>
    </template>

    <template id="cart-template">
        <div class="cart-items" role="region" aria-label="Shopping cart items">
            <div class="row fw-bold d-none d-md-flex py-2 border-bottom">
                <div class="col-md-6">Product</div>
                <div class="col-md-2">Color</div>
                <div class="col-md-1">Price</div>
                <div class="col-md-2">Quantity</div>
                <div class="col-md-1"></div>
            </div>

            <div id="cart-items" class="mx-3 mx-md-0"></div>

            <div class="row mt-3 py-2 border-top">
                <div class="col-12 d-flex justify-content-end align-items-center">
                    <span class="fw-bold me-3">Total:</span>
                    <span class="fw-bold" id="cart-total">0.00 €</span>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary mb-3">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Continue Shopping
                </a>
            </div>
            <div class="col-6 text-end">
                <a href="#" class="btn btn-success">
                    Proceed to Checkout
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </template>

    <template id="cart-item-template">
        <div class="row py-3 border-bottom cart-item" data-item-id="">
            <!-- image and name -->
            <div class="col-12 col-md-6 mb-3">
                <div class="d-flex flex-column flex-md-row">
                    <img src="" alt="" class="img-thumbnail mb-2 mb-md-0 me-md-3" width="150" height="150">
                    <h3 class="h6 mb-0"></h3>
                </div>
            </div>

            <!-- color -->
            <div class="col-12 col-md-2 mb-3">
                <div class="d-md-none fw-bold mb-1">Color</div>
                <div class="d-flex align-items-center">
                    <span class="color-swatch" aria-hidden="true"></span>
                    <span class="ms-2 color-name"></span>
                </div>
            </div>

            <!-- price -->
            <div class="col-12 col-md-1 mb-3">
                <div class="d-md-none fw-bold mb-1">Price</div>
                <span class="item-price" data-price="">0.00 €</span>
            </div>

            <!-- quantity -->
            <div class="col-12 col-md-2 mb-3">
                <div class="d-md-none fw-bold mb-1">Quantity</div>
                <div class="input-group">
                    <button class="btn btn-outline-secondary decrease-qty" type="button">-</button>
                    <input type="number" class="form-control text-center item-quantity" style="max-width: 80px" value="1" min="1">
                    <button class="btn btn-outline-secondary increase-qty" type="button">+</button>
                </div>
            </div>

            <!-- remove btn -->
            <div class="col-12 col-md-1 mb-3">
                <button class="btn btn-outline-danger remove-item" title="Remove from cart" type="button">
                    <i class="fa fa-trash" aria-hidden="true">Trash bin</i>
                </button>
                <span class="visually-hidden">Remove item from cart</span>
            </div>
        </div>
    </template>

    <style>
        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            display: inline-block;
        }

        @media (max-width: 767.98px) {
            .cart-item {
                margin-bottom: 1rem;
                padding: 1.5rem;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
            }

            .remove-item {
                margin-top: 0.5rem;
            }

            .input-group {
                width: auto;
            }
        }
    </style>

    @push('scripts')
        @vite('resources/js/cart.js')
    @endpush
@endsection
