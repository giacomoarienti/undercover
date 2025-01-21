import {showAlert} from './alert';

document.addEventListener('DOMContentLoaded', async function () {
    await loadCart();
});

async function loadCart() {
    try {
        const response = await fetch('/cart', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
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

function renderCart(cart) {
    const cartContents = document.getElementById('cart-contents');
    cartContents.innerHTML = '';

    if (!cart || cart.length === 0) {
        const emptyTemplate = document.getElementById('empty-cart-template');
        cartContents.appendChild(emptyTemplate.content.cloneNode(true));
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
    let total = 0;

    document.getElementById('cart-items').querySelectorAll('.cart-item').forEach(item => {
        let price = item.querySelector('.item-price').dataset.price;
        let quantity = item.querySelector('.item-quantity').value;
        total += price * quantity;
    });

    document.getElementById('cart-total').textContent = formatPrice(total);
}

function createCartItemElement(item) {
    const template = document.getElementById('cart-item-template');
    const element = template.content.cloneNode(true);
    const cartItem = element.querySelector('.cart-item');

    cartItem.dataset.itemId = item.id;

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
    const quantityInput = element.querySelector('.item-quantity');
    quantityInput.value = item.pivot.quantity;
    quantityInput.max = item.quantity;

    return element;
}

async function removeCartItem(itemId, cartItem) {
    if (confirm('Are you sure you want to remove this item from the cart?')) {
        await removeFromCart(itemId);
        cartItem.remove();
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
                await updateItem(itemId, newValue);
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
                await updateItem(itemId, newValue);
                updateCartTotal();
            } else {
                this.value = this.defaultValue;
            }
        });
    });
}

async function updateItem(itemId, quantity) {
    console.log(itemId, quantity)
    try {
        const response = await fetch('/cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                specific_product_id: itemId,
                quantity: quantity
            })
        })

        if (!response.ok) {
            const data = await response.json();
            showAlert(data.message, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred while updating the cart.', 'danger');
    }
}

async function removeFromCart(itemId) {
    try {
        const response = await fetch('/cart', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                specific_product_id: itemId
            })
        });

        if (!response.ok) {
            const data = await response.json();
            showAlert(data.message, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred while removing the item.', 'danger');
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}
