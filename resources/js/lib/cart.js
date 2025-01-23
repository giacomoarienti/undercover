import {showAlert} from './alert.js';

export async function storeItem(itemId, quantity) {
    try {
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
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred while updating the cart.', 'danger');
    }
}

export async function removeFromCart(itemId) {
    try {
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
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred while removing the item.', 'danger');
    }
}
