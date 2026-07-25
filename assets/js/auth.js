/**
 * auth.js — FoodHub Authentication Page Interactions
 * Handles: password toggle, strength meter, role selector, form validation
 */

document.addEventListener('DOMContentLoaded', () => {

    /* ── Password Visibility Toggle ───────────────────────── */
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.input-wrapper').querySelector('input');
            if (!input) return;
            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';
            btn.querySelector('i').classList.toggle('fa-eye', isText);
            btn.querySelector('i').classList.toggle('fa-eye-slash', !isText);
        });
    });

    /* ── Password Strength Meter ──────────────────────────── */
    const pwInput = document.getElementById('password');
    const strengthBar   = document.getElementById('strengthBar');
    const strengthLabel = document.getElementById('strengthLabel');

    if (pwInput && strengthBar) {
        pwInput.addEventListener('input', () => {
            const val = pwInput.value;
            const score = getStrengthScore(val);
            const map = [
                { cls: '',               label: '' },
                { cls: 'strength-weak',  label: '⚠ Weak' },
                { cls: 'strength-medium',label: '↑ Medium' },
                { cls: 'strength-strong',label: '✔ Strong' },
            ];
            strengthBar.className = 'password-strength-bar ' + (map[score]?.cls || '');
            if (strengthLabel) strengthLabel.textContent = map[score]?.label || '';
        });
    }

    /**
     * Score a password 0-3.
     * @param {string} pw
     * @returns {number}
     */
    function getStrengthScore(pw) {
        if (!pw) return 0;
        let score = 0;
        if (pw.length >= 8)                    score++;
        if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
        if (/[0-9]/.test(pw) && /[^A-Za-z0-9]/.test(pw)) score++;
        return score;
    }

    /* ── Role Card Selector (Register page) ───────────────── */
    document.querySelectorAll('.role-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            const radio = card.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });
    });

    /* ── Mark default-selected role on page load ──────────── */
    const checkedRadio = document.querySelector('.role-card input[type="radio"]:checked');
    if (checkedRadio) checkedRadio.closest('.role-card')?.classList.add('selected');

    /* ── Client-side Register Form Validation ─────────────── */
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', e => {
            const pw  = document.getElementById('password')?.value || '';
            const cpw = document.getElementById('confirm_password')?.value || '';
            const terms = document.getElementById('terms');

            if (pw !== cpw) {
                e.preventDefault();
                showAuthAlert('Passwords do not match.', 'danger');
                return;
            }
            if (terms && !terms.checked) {
                e.preventDefault();
                showAuthAlert('Please accept the Terms & Conditions.', 'danger');
                return;
            }
            if (pw.length < 8) {
                e.preventDefault();
                showAuthAlert('Password must be at least 8 characters.', 'danger');
            }
        });
    }

    /* ── Login Form — disable button on submit ─────────────── */
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', () => {
            const btn = loginForm.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Signing in…';
            }
        });
    }

    /* ── Helper: display inline alert inside auth card ─────── */
    function showAuthAlert(message, type = 'danger') {
        let existing = document.querySelector('.auth-alert.js-injected');
        if (existing) existing.remove();

        const icons = { danger: 'fa-circle-exclamation', success: 'fa-circle-check', info: 'fa-circle-info' };
        const alert = document.createElement('div');
        alert.className = `auth-alert ${type} js-injected`;
        alert.innerHTML = `<i class="fa-solid ${icons[type] || icons.danger}"></i><span>${message}</span>`;
        const form = document.querySelector('.auth-form');
        if (form) form.prepend(alert);
    }

    /* ── Auto-dismiss server-rendered alerts ──────────────── */
    document.querySelectorAll('.auth-alert:not(.js-injected)').forEach(el => {
        setTimeout(() => el.remove(), 5000);
    });
});
