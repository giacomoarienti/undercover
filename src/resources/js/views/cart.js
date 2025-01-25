import {showAlert} from "../lib/alert.js";
import {removeFromCart, updateItemCart} from "../lib/cart.js";

document.addEventListener('DOMContentLoaded', loadCart);

async function loadCart() {
    try {
        const response = await fetch('/cart?json=true', {
            headers: {
                'Accept': 'application/json',
            }
        });

        const data = await response.json();
        if (!response.ok) {
            showAlert(data.message, 'danger');
        }

        renderCart(data.cart);
    } catch (error) {
        console.error('Error loading cart:', error);
        showAlert('Error loading cart contents. Please refresh the page.', 'danger');
    }
}

function emptyCart() {
    const cartContents = document.getElementById('cart-contents');
    cartContents.innerHTML = '';

    const emptyTemplate = document.getElementById('empty-cart-template');
    cartContents.appendChild(emptyTemplate.content.cloneNode(true));
}

function renderCart(cart) {
    const cartContents = document.getElementById('cart-contents');
    cartContents.innerHTML = '';

    if (!cart || cart.length === 0) {
        emptyCart();
        return;
    }

    const cartTemplate = document.getElementById('cart-template');
    cartContents.appendChild(cartTemplate.content.cloneNode(true));

    const cartItemsContainer = document.getElementById('cart-items');

    cart.forEach(item => {
        const itemElement = createCartItemElement(item);
        cartItemsContainer.appendChild(itemElement);
    });

    updateCartTotal();

    initializeEventListeners();
}

function updateCartTotal() {
    const cartItems = document.getElementById('cart-items');
    let total = 0;

    if (cartItems) {
        cartItems.querySelectorAll('.cart-item').forEach(item => {
            let price = item.querySelector('.item-price').dataset.price;
            let quantity = item.querySelector('.item-quantity').value;
            total += price * quantity;
        });

        document.getElementById('cart-total').textContent = formatPrice(total);
    }
}

function createCartItemElement(item) {
    const template = document.getElementById('cart-item-template');
    const element = template.content.cloneNode(true);
    const cartItem = element.querySelector('.cart-item');

    cartItem.dataset.itemId = item.id;

    // Set product link
    const productLink = cartItem.querySelector('.product-link');
    productLink.href = item.product.url;

    // Set product image and details
    const img = element.querySelector('img');
    img.src = item.product.media_url;
    img.alt = `Product image of ${item.product.name}`;

    element.querySelector('h3').textContent = item.product.name;

    // color
    const colorSwatch = element.querySelector('.color-swatch');
    colorSwatch.style.backgroundColor = item.color.rgb;
    element.querySelector('.color-name').textContent = item.color.name;

    // price
    const itemPrice = element.querySelector('.item-price');
    itemPrice.textContent = formatPrice(item.product.price);
    itemPrice.dataset.price = item.product.price;

    // quantity
    const quantityInputId = "quantity-product-" + item.id;

    const quantityLabel = element.querySelector('.item-quantity-label');
    quantityLabel.htmlFor = quantityInputId;

    const quantityInput = element.querySelector('.item-quantity');
    quantityInput.value = item.pivot.quantity;
    quantityInput.max = item.quantity;
    quantityInput.id = quantityInputId;

    return element;
}

async function removeCartItem(itemId, cartItem) {
    if (confirm('Are you sure you want to remove this item from the cart?')) {
        await removeFromCart(itemId);
        cartItem.remove();

        const cartItemsContainer = document.getElementById('cart-items');
        if (cartItemsContainer.children.length === 0) {
            emptyCart()
        }
    }
}

function initializeEventListeners() {
    // Quantity adjustment buttons
    document.querySelectorAll('.increase-qty, .decrease-qty').forEach(button => {
        button.addEventListener('click', async function () {
            const cartItem = this.closest('.cart-item');
            const itemId = cartItem.dataset.itemId;
            const input = cartItem.querySelector('.item-quantity');
            const currentValue = parseInt(input.value);
            const maxQuantity = parseInt(input.max);

            let newValue;
            if (this.classList.contains('increase-qty') && currentValue < maxQuantity) {
                newValue = currentValue + 1;
            } else if (this.classList.contains('decrease-qty') && currentValue > 0) {
                newValue = currentValue - 1;
            } else {
                return;
            }

            if (newValue <= 0) {
                await removeCartItem(itemId, cartItem);
            } else {
                await updateItemCart(itemId, newValue);
                input.value = newValue;
            }

            updateCartTotal();
        });
    });

    // Remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', async function () {
            const cartItem = this.closest('.cart-item');
            const itemId = cartItem.dataset.itemId;

            await removeCartItem(itemId, cartItem);
            updateCartTotal();
        });
    });

    // Quantity input direct changes
    document.querySelectorAll('.item-quantity').forEach(input => {
        input.addEventListener('change', async function () {
            const cartItem = this.closest('.cart-item');
            const itemId = cartItem.dataset.itemId;
            const newValue = parseInt(this.value);

            if (newValue >= 0 && newValue <= parseInt(this.max)) {
                await updateItemCart(itemId, newValue);
                updateCartTotal();
            } else {
                this.value = this.defaultValue;
            }
        });
    });
}

function formatPrice(price) {
    return new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}
