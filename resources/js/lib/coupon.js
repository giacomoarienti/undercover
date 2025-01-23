import {showAlert} from "./alert.js";

/**
 * Returns the coupon for the given code. Throws an Error if the coupon doesn't exist.
 * @param code
 * @returns {Promise<{"id": string, "code": string, "discount": number, "starts_at": string, "expires_at": string, "user_id": number }>}
 */
export async function getCoupon(code) {
    const response = await fetch(`/coupons/${code}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })

    const data = await response.json();
    if (!response.ok) {
        throw new Error(data.message);
    }

    return data.coupon;
}
