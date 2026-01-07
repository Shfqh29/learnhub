// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const svg = field.parentElement.querySelector('.toggle-password svg');

    if (!field || !svg) return;

    if (field.type === 'password') {
        field.type = 'text';
        svg.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    } else {
        field.type = 'password';
        svg.innerHTML = `
            <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    }
}

// Wait until page loads
document.addEventListener('DOMContentLoaded', () => {

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const confirmMessage = document.getElementById('confirm-message');
    const actionButton =
        document.getElementById('register-button') ||
        document.getElementById('reset-button');
    const passwordDialog = document.getElementById('password-dialog');

    if (!passwordInput || !confirmInput) return;

    function isPasswordValid() {
        const value = passwordInput.value;
        return (
            value.length >= 8 &&
            /[A-Z]/.test(value) &&
            /[a-z]/.test(value) &&
            /[0-9]/.test(value) &&
            /[@$!%*#?&]/.test(value)
        );
    }

    function updateRules() {
        const value = passwordInput.value;
        const rules = {
            'rule-length': value.length >= 8,
            'rule-uppercase': /[A-Z]/.test(value),
            'rule-lowercase': /[a-z]/.test(value),
            'rule-number': /[0-9]/.test(value),
            'rule-symbol': /[@$!%*#?&]/.test(value),
        };

        Object.keys(rules).forEach(rule => {
            const el = document.getElementById(rule);
            if (el) el.style.color = rules[rule] ? 'green' : 'red';
        });

        if (passwordDialog) {
            if (isPasswordValid()) passwordDialog.classList.remove('show');
            else passwordDialog.classList.add('show');
        }
    }

    function checkConfirmPassword() {
        if (!confirmMessage) return;

        if (confirmInput.value !== '') {
            if (passwordInput.value === confirmInput.value) {
                confirmMessage.textContent = 'Passwords match';
                confirmMessage.style.color = 'green';

                if (isPasswordValid()) {
                    confirmMessage.style.display = 'none';
                } else {
                    confirmMessage.style.display = 'block';
                }
            } else {
                confirmMessage.textContent = 'Passwords do not match';
                confirmMessage.style.color = 'red';
                confirmMessage.style.display = 'block';
            }
        } else {
            confirmMessage.style.display = 'none';
        }
    }

    function validateForm() {
        if (!actionButton) return;
        actionButton.disabled = !(isPasswordValid() &&
            passwordInput.value === confirmInput.value);
    }

    passwordInput.addEventListener('focus', () => {
        if (passwordDialog) passwordDialog.classList.add('show');
    });

    passwordInput.addEventListener('input', () => {
        updateRules();
        checkConfirmPassword();
        validateForm();
    });

    confirmInput.addEventListener('input', () => {
        checkConfirmPassword();
        validateForm();
    });
});
