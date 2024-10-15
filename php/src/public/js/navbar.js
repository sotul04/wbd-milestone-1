function initPage() {
    checkSession();
}

function checkSession() {
    // Create an AJAX request
    const xhr = new XMLHttpRequest();
            xhr.open('GET', '/auth/checkAuth', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            updateNavbar(response);
        } else {
            console.error("Failed to fetch session info.");
        }
    };
    xhr.onerror = function () {
        console.error("An error occurred during the AJAX request.");
    };
    xhr.send();
}

function updateNavbar(response) {
    const navRight = document.querySelector('.navRight ul.navItems');

    // Remove existing navProfile if it exists
    const existingNavProfile = document.querySelector('.navProfile');
    if (existingNavProfile) {
        existingNavProfile.remove();
    }

    if (response.status === 'success') {
        // User is logged in
        navRight.innerHTML = '';  // Clear existing items

        if (response.data.role === 'jobseeker') {
            navRight.innerHTML = '<li class="hover-underline-animation link" data-role="jobseeker">History</li>';
        } else if (response.data.role === 'company') {
            navRight.innerHTML = '<li class="hover-underline-animation link" data-role="company">Profile</li>';
        }

        // Create and append the navProfile dynamically
        const navProfile = document.createElement('div');
        navProfile.classList.add('navProfile');
        navProfile.innerHTML = `<li class='profileCt'><p>${response.data.name ? response.data.name.substring(0,1).toUpperCase() : ''}</p><button id="logout" class="btn btn-destroy">Logout</button></li>`;
        navRight.appendChild(navProfile);

        // Add event listeners for History and Profile links
        const profileLink = navRight.querySelector('.hover-underline-animation[data-role="jobseeker"]');
        const historyLink = navRight.querySelector('.hover-underline-animation[data-role="company"]');

        if (profileLink) {
            profileLink.addEventListener('click', redirectToHistory);
        }

        if (historyLink) {
            historyLink.addEventListener('click', redirectToProfile);
        }

        // Add event listener for Logout button
        const logoutButton = navProfile.querySelector('#logout');
        logoutButton.addEventListener('click', handleLogout);

    } else {
        // User is not logged in
        navRight.innerHTML = `
            <li class="hover-underline-animation link" id="login">Login</li>
            <li class="hover-underline-animation link" id="register">Register</li>
        `;

        // Add event listeners for Login and Register links
        const loginLink = navRight.querySelector('#login');
        const registerLink = navRight.querySelector('#register');

        if (loginLink) {
            loginLink.addEventListener('click', redirectToLogin);
        }

        if (registerLink) {
            registerLink.addEventListener('click', redirectToRegister);
        }
    }
}

function handleLogout() {
    const xhr = new XMLHttpRequest();
    
    xhr.open('GET', '/auth/logout', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log("Logout successful.");
            window.location.href = '/user/login';
        } else {
            console.error("Failed to log out.");
        }
    };
    
    xhr.onerror = function () {
        console.error("An error occurred during the logout request.");
    };
    
    xhr.send();
}

const hamburger = document.querySelector(".hamburger");
const navItems = document.querySelector(".navItems");

hamburger.addEventListener("click", mobileMenu);

function mobileMenu() {
    hamburger.classList.toggle("active");
    navItems.classList.toggle("active");
}

const navLink = document.querySelectorAll(".navItems li");
navLink.forEach(i => i.addEventListener("click", closeMenu));

function closeMenu() {
    hamburger.classList.remove("active");
    navItems.classList.remove("active");
}
