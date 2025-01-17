@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Cart</h1>

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
        <div class="table-responsive">
            <table class="table table-hover" aria-label="Shopping cart items">
                <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Color</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col"><span class="visually-hidden">Actions</span></th>
                </tr>
                </thead>
                <tbody id="cart-items">
                </tbody>
                <tfoot>
                <tr class="table-light">
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td><strong id="cart-total">0.00 €</strong></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Continue Shopping
                </a>
            </div>
            <div class="col-md-6 text-end">
                <a href="#" class="btn btn-success">
                    Proceed to Checkout
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </template>

    <template id="cart-item-template">
        <tr data-item-id="">
            <td>
                <div class="d-flex align-items-center">
                    <img src="" alt="" class="img-thumbnail me-3" width="80" height="80">
                    <div>
                        <h3 class="h6 mb-1"></h3>
                        <p class="small text-muted mb-0"></p>
                    </div>
                </div>
            </td>
            <td>
            <span class="d-flex align-items-center">
                <span class="color-swatch" aria-hidden="true"></span>
                <span class="ms-2 color-name"></span>
            </span>
            </td>
            <td class="item-price"></td>
            <td>
                <div class="input-group">
                    <button class="btn btn-outline-secondary decrease-qty" type="button">-</button>
                    <input type="number" class="form-control text-center item-quantity" value="1" min="1">
                    <button class="btn btn-outline-secondary increase-qty" type="button">+</button>
                </div>
            </td>
            <td class="item-subtotal"></td>
            <td>
                <button type="button" class="btn btn-link text-danger remove-item">
                    <i class="bi bi-trash" aria-hidden="true"></i>
                    <span class="visually-hidden">Remove item</span>
                </button>
            </td>
        </tr>
    </template>

    <style>
        .color-swatch {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid #dee2e6;
            display: inline-block;
        }
    </style>

    @push('scripts')
        @vite('resources/js/cart.js')
    @endpush

@endsection
