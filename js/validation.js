function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const inputs = form.querySelectorAll('input, select, textarea');
            let valid = true;

            inputs.forEach(input => {
                if (input.required && !input.value.trim()) {
                    valid = false;
                    showToast(`${input.name} is required.`, 'error');
                    input.style.borderColor = 'red';
                } else if (input.type === 'email' && !validateEmail(input.value)) {
                    valid = false;
                    showToast('Please enter a valid email.', 'error');
                    input.style.borderColor = 'red';
                } else if (input.name === 'password' && input.value.length < 6) {
                    valid = false;
                    showToast('Password must be at least 6 characters.', 'error');
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '#ddd';
                }
            });

            if (!valid) e.preventDefault();
        });
    });
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}