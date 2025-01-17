import { showAlert } from './alert';

document.addEventListener('DOMContentLoaded', function () {
    loadCart();
});

function loadCart() {
    fetch('/cart', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => renderCart(data.cart))
        .catch(error => {
            console.error('Error loading cart:', error);
            showAlert('Error loading cart contents. Please refresh the page.', 'danger');
        });
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
    let total = 0;

    cart.forEach(item => {
        const itemElement = createCartItemElement(item);
        cartItemsContainer.appendChild(itemElement);
        total += item.product.price * item.quantity;
    });

    document.getElementById('cart-total').textContent = formatPrice(total);
    initializeEventListeners();
}

function createCartItemElement(item) {
    const template = document.getElementById('cart-item-template');
    const element = template.content.cloneNode(true);
    const row = element.querySelector('tr');

    row.dataset.itemId = item.id;

    // Set product image and details
    const img = element.querySelector('img');
    img.src = item.product.image || '/placeholder-image.jpg';
    img.alt = `Product image of ${item.product.name}`;

    element.querySelector('h3').textContent = item.product.name;
    element.querySelector('p').textContent = `SKU: ${item.product.id}`;

    // Set color
    const colorSwatch = element.querySelector('.color-swatch');
    colorSwatch.style.backgroundColor = item.color.hex_value;
    element.querySelector('.color-name').textContent = item.color.name;

    // Set price and quantity
    element.querySelector('.item-price').textContent = formatPrice(item.product.price);
    const quantityInput = element.querySelector('.item-quantity');
    quantityInput.value = item.quantity;
    quantityInput.max = item.quantity;

    element.querySelector('.item-subtotal').textContent =
        formatPrice(item.product.price * item.quantity);

    return element;
}

function initializeEventListeners() {
    // Quantity adjustment buttons
    document.querySelectorAll('.increase-qty, .decrease-qty').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const itemId = row.dataset.itemId;
            const input = row.querySelector('.item-quantity');
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

            updateCart(itemId, newValue);
        });
    });

    // Remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const itemId = row.dataset.itemId;
            removeFromCart(itemId);
        });
    });

    // Quantity input direct changes
    document.querySelectorAll('.item-quantity').forEach(input => {
        input.addEventListener('change', function () {
            const row = this.closest('tr');
            const itemId = row.dataset.itemId;
            const newValue = parseInt(this.value);

            if (newValue >= 0 && newValue <= parseInt(this.max)) {
                updateCart(itemId, newValue);
            } else {
                this.value = this.defaultValue;
            }
        });
    });
}

function updateCart(itemId, quantity) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            specific_product_id: itemId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            showAlert(data.message, response.ok ? 'success' : 'danger');
            loadCart(); // Reload cart contents
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating the cart.', 'danger');
        });
}

function removeFromCart(itemId) {
    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            specific_product_id: itemId
        })
    })
        .then(response => response.json())
        .then(data => {
            showAlert(data.message, 'success');
            loadCart(); // Reload cart contents
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while removing the item.', 'danger');
        });
}

function formatPrice(price) {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}
