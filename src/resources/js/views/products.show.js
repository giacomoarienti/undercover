import {showAlert} from "../lib/alert.js";
import {addItemToCart} from "../lib/cart.js";

addEventListener('DOMContentLoaded', () => {
    document.getElementById('add-to-cart').addEventListener('click', handleAddToCart);
})
async function handleAddToCart() {
    const selectedColor = document.querySelector('input[name="color"]:checked');
    if (!selectedColor) {
        showAlert('Please select a color.', 'danger');
        return;
    }

    const itemId = selectedColor.value;
    const quantity = document.getElementById('quantity').value;

    try {
        await addItemToCart(itemId, quantity);
        showAlert('Item added to cart successfully!', 'success');
    } catch (error) {
        console.error('Error adding item to cart:', error);
    }
}
