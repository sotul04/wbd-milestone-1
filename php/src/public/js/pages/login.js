function validateForm(event) {
    event.preventDefault();

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    clearErrorMessages();

    let isValid = true;

    if (!emailInput.value) {
        appendErrorMessage(emailInput, 'Email is required.');
        isValid = false;
    } else if (!validateEmail(emailInput.value)) {
        appendErrorMessage(emailInput, 'Invalid email format.');
        isValid = false;
    }

    if (!passwordInput.value) {
        appendErrorMessage(passwordInput, 'Password is required.');
        isValid = false;
    }

    if (isValid) {
        event.target.submit();
    }
}

function appendErrorMessage(inputElement, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    inputElement.parentNode.appendChild(errorDiv);
}

function clearErrorMessages() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => error.remove());
}

function validateEmail(email) {
    const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(String(email).toLowerCase());
}

document.getElementById('loginForm').addEventListener('submit', validateForm);

document.getElementById('password').addEventListener('focusin', () => {
    document.getElementById('password').removeAttribute('readonly');
});
document.getElementById('email').addEventListener('focusin', ()=> {
    document.getElementById('email').removeAttribute('readonly');
});

document.getElementById('password').addEventListener('focusout', () => {
    document.getElementById('password').setAttribute('readonly', '');
});
document.getElementById('email').addEventListener('focusout', ()=> {
    document.getElementById('email').setAttribute('readonly', '');
});
