import {getCoupon} from "../lib/coupon.js";
import {showAlert} from "../lib/alert.js";
addEventListener('DOMContentLoaded', () => {
    document.getElementById('apply_coupon').addEventListener('click', applyCoupon);
})
async function applyCoupon() {
    const couponInput = document.getElementById('coupon_code');
    const form = document.getElementById('form');
    const couponHelp = document.getElementById('coupon_help');
    const couponId = document.getElementById('coupon_id');

    let code = couponInput.value;
    try {
        let coupon = await getCoupon(code);
        couponId.value = coupon.id;
        showAlert('Coupon code applied successfully!', 'success');
        couponHelp.textContent = "Current coupon: " + '"' + coupon.code + '"' + " - " + Math.trunc(coupon.discount * 100) + "% off on selected products."
    } catch (error) {
        showAlert('Coupon code is invalid.', 'danger');
        couponInput.classList.add('is-invalid');
    }
}
