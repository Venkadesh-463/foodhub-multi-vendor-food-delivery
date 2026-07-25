/* FoodHub Form Validation Routine */
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-validate="true"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            let isValid = true;
            const requiredInputs = form.querySelectorAll('[required]');

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            const password = form.querySelector('input[name="password"]');
            const confirmPass = form.querySelector('input[name="confirm_password"]');
            if (password && confirmPass && password.value !== confirmPass.value) {
                isValid = false;
                showToast('Passwords do not match', 'danger');
            }

            if (!isValid) {
                e.preventDefault();
                showToast('Please check required fields.', 'warning');
            }
        });
    });
});
