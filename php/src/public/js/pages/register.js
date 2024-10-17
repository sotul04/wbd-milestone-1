document.addEventListener('DOMContentLoaded', () => {
    document.head.title = "Register - Linkin";

    const userTypeDropdown = document.getElementById('userType');
    const form = document.getElementById('loginForm');

    userTypeDropdown.addEventListener('change', (event) => {
        const selectedValue = event.target.value;

        // Clear previously added company fields
        const existingLocationField = document.getElementById('location');
        const existingAboutField = document.getElementById('about');

        if (existingLocationField) {
            existingLocationField.parentElement.remove();
        }
        if (existingAboutField) {
            existingAboutField.parentElement.remove();
        }

        // Add fields for the selected user type
        if (selectedValue === 'company') {
            // Create and append company location field
            const locationGroup = document.createElement('div');
            locationGroup.className = 'form-group';
            locationGroup.innerHTML = `
                <img src="http://localhost:8000/public/assets/icons/Location.ico" alt="Location Icon">
                <input type="text" id="location" name="location" placeholder="Company Location" autocomplete="off">
            `;
            form.insertBefore(locationGroup, form.children[form.children.length - 3]); // Insert before the register button

            // Create and append about field
            const aboutGroup = document.createElement('div');
            aboutGroup.className = 'form-group';
            aboutGroup.innerHTML = `
                <img src="http://localhost:8000/public/assets/icons/About.ico" alt="About Icon">
                <textarea id="about" name="about" placeholder="About your company" ></textarea>
            `;
            form.insertBefore(aboutGroup, form.children[form.children.length - 3]); // Insert before the register button
            document.getElementById('name').placeholder = 'Company Name';
            document.getElementById('email').placeholder = 'Company email';
        } else {
            document.getElementById('name').placeholder = 'Full Name';
            document.getElementById('email').placeholder = 'Email';
        }
    });

    // Add event listener for form submission
    form.addEventListener('submit', validateForm);
});

function validateForm(event) {
    event.preventDefault();

    const userType = document.getElementById('userType').value;
    const name = userType === 'jobseeker' ? document.getElementById('name') : document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirm = document.getElementById('confirm');
    const location = document.getElementById('location');
    const about = document.getElementById('about');

    clearErrorMessages();

    let isValid = true;

    // Name validation based on user type
    if (!name.value) {
        appendErrorMessage(name, userType === 'jobseeker' ? 'Full name is required.' : 'Company name is required.');
        isValid = false;
    }

    // Email validation
    if (!emailInput.value) {
        appendErrorMessage(emailInput, 'Email is required.');
        isValid = false;
    } else if (!validateEmail(emailInput.value)) {
        appendErrorMessage(emailInput, 'Invalid email format.');
        isValid = false;
    }

    // Password validation
    if (!passwordInput.value) {
        appendErrorMessage(passwordInput, 'Password is required.');
        isValid = false;
    }

    // Confirm password validation
    if (passwordInput.value !== confirm.value) {
        appendErrorMessage(confirm, 'Passwords do not match.');
        isValid = false;
    }

    // Additional validations for company fields
    if (userType === 'company') {
        if (location && !location.value) {
            appendErrorMessage(location, 'Company location is required.');
            isValid = false;
        }
        if (about && !about.value) {
            appendErrorMessage(about, 'About your company is required.');
            isValid = false;
        }
    }

    // If valid, send a POST request
    if (isValid) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://localhost:8000/auth/register", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Prepare data to send
        const formData = new URLSearchParams();
        formData.append('userType', userType);
        formData.append('name', name.value);
        formData.append('email', emailInput.value);
        formData.append('password', passwordInput.value);
        formData.append('role', userType);
        if (userType === 'company') {
            formData.append('location', location.value);
            formData.append('about', about.value);
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    window.location.href = 'http://localhost:8000/';
                } else {
                    if (response.data === 'The email has been used') {
                        appendErrorMessage(emailInput, response.data);
                    } else {
                        const parent = document.querySelector('.form-container');
                        const errorDiv = document.createElement('div');
                        errorDiv.classList.add('top-error-message');
                        errorDiv.innerText = response.data;
                        parent.insertBefore(errorDiv, parent.children[parent.children.length-2]);
                    }
                }
            }
        };

        xhr.send(formData.toString());
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
