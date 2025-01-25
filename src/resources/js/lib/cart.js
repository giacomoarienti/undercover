import {showAlert} from './alert.js';

export async function addItemToCart(itemId, quantity) {
    const response = await fetch('/cart/add', {
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

    const data = await response.json();
    if (!response.ok) {
        showAlert(data.message, 'danger');
        throw new Error(data.message)
    }

    const itemsN = data.items;
    setHeaderCart(itemsN);
}

export async function updateItemCart(itemId, quantity) {
    const response = await fetch('/cart', {
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

    if (!response.ok) {
        const data = await response.json();
        showAlert(data.message, 'danger');
        throw new Error(data.message)
    }
}

export async function removeFromCart(itemId) {
    const response = await fetch('/cart', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
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
        throw new Error(data.message)
    }

    updateHeaderCart(-1);
}

function setHeaderCart(quantity) {
    const cartNotification = document.getElementById('cart-notification')
    cartNotification.innerText = quantity;

    if (quantity === 0) {
        // hide the notification badge
        cartNotification.classList.add('d-none')
    } else {
        // show the notification badge
        cartNotification.classList.remove('d-none')
    }
}

function updateHeaderCart(update) {
    const cartNotification = document.getElementById('cart-notification')
    setHeaderCart(Number(cartNotification.innerText) + update)
}
