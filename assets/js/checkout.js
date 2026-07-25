/**
 * checkout.js — FoodHub Checkout Page Logic
 * Handles: address selection, payment method switching, order summary update,
 *          coupon application, and form submission.
 */

const Checkout = (() => {
    const BASE = window.FOODHUB_BASE_URL || '/FoodHub/';

    /* ── Payment method tab switching ───────────────────── */
    function initPaymentTabs() {
        document.querySelectorAll('.payment-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.payment-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.payment-panel').forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                const target = document.getElementById('panel-' + tab.dataset.method);
                if (target) target.classList.add('active');
                // Write hidden input for form submission
                const methodInput = document.getElementById('payment_method');
                if (methodInput) methodInput.value = tab.dataset.method;
            });
        });
    }

    /* ── Address card selection ─────────────────────────── */
    function initAddressCards() {
        document.querySelectorAll('.address-select-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.address-select-card').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                const hidden = document.getElementById('delivery_address_id');
                if (hidden) hidden.value = card.dataset.id;
                const addrText = document.getElementById('delivery_address_text');
                if (addrText) addrText.value = card.dataset.address;
            });
        });
    }

    /* ── Coupon / promo code ────────────────────────────── */
    function initCoupon() {
        const btn = document.getElementById('applyCouponBtn');
        if (!btn) return;
        btn.addEventListener('click', async () => {
            const input = document.getElementById('coupon_code');
            const code = input?.value.trim();
            if (!code) return showCouponMsg('Enter a coupon code.', 'danger');

            btn.disabled = true;
            btn.textContent = 'Applying…';
            try {
                const res = await fetch(BASE + 'api/cart/apply-coupon.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ code })
                });
                const data = await res.json();
                if (data.success) {
                    showCouponMsg(`✔ ${data.message}`, 'success');
                    updateSummaryLine('discount', data.discount_amount);
                    updateSummaryLine('final', data.final_amount);
                    const hiddenDiscount = document.getElementById('discount_amount');
                    if (hiddenDiscount) hiddenDiscount.value = data.discount_amount;
                } else {
                    showCouponMsg(data.message || 'Invalid coupon.', 'danger');
                }
            } catch {
                showCouponMsg('Network error. Please try again.', 'danger');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Apply';
            }
        });
    }

    function showCouponMsg(msg, type) {
        let el = document.getElementById('couponMsg');
        if (!el) return;
        el.textContent = msg;
        el.className = 'coupon-msg ' + type;
        setTimeout(() => { el.textContent = ''; el.className = 'coupon-msg'; }, 4000);
    }

    function updateSummaryLine(key, value) {
        const el = document.getElementById('summary_' + key);
        if (el && window.currencySymbol) el.textContent = window.currencySymbol + parseFloat(value).toFixed(2);
    }

    /* ── Form validation before submit ─────────────────── */
    function initFormValidation() {
        const form = document.getElementById('checkoutForm');
        if (!form) return;
        form.addEventListener('submit', e => {
            const addrId   = document.getElementById('delivery_address_id')?.value;
            const addrText = document.getElementById('delivery_address_text')?.value;
            if (!addrId && !addrText) {
                e.preventDefault();
                if (typeof showToast === 'function') showToast('Please select a delivery address.', 'danger');
                return;
            }
            const method = document.getElementById('payment_method')?.value;
            if (!method) {
                e.preventDefault();
                if (typeof showToast === 'function') showToast('Please select a payment method.', 'danger');
                return;
            }
            const btn = form.querySelector('[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Placing Order…';
            }
        });
    }

    /* ── Public init ────────────────────────────────────── */
    function init() {
        initPaymentTabs();
        initAddressCards();
        initCoupon();
        initFormValidation();
    }

    return { init };
})();

document.addEventListener('DOMContentLoaded', Checkout.init);
