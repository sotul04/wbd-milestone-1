document.addEventListener('DOMContentLoaded', () => {
    checkSession();
});

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
        navProfile.innerHTML = `
        <li class='profileCt'>
            <p id='profile-trigger' class="shadow-2">${response.data.name ? response.data.name.substring(0, 1).toUpperCase() : ''}</p>
            <form action="http://localhost:8000/auth/logout" method="GET">
                <button id="logout" class="btn btn-destroy" type="submit">Logout</button>
            </form>
            <div id="profile-nav" class="shadow-4">
                <div>
                    <p>${response.data.name}</p>
                    <p>${response.data.role}</p>
                </div>
                <form action="http://localhost:8000/auth/logout" method="GET">
                    <button id="profile-logout" class="btn btn-destroy shadow-3" type="submit">Logout</button>
                </form>
            </div>
        </li>`;
        navRight.appendChild(navProfile);

        document.getElementById('profile-nav-close').addEventListener('click', closeProfile);
        document.getElementById('profile-trigger').addEventListener('click', openProfile);

        const profileLink = navRight.querySelector('.hover-underline-animation[data-role="jobseeker"]');
        const historyLink = navRight.querySelector('.hover-underline-animation[data-role="company"]');

        if (profileLink) {
            profileLink.addEventListener('click', redirectToHistory);
        }

        if (historyLink) {
            historyLink.addEventListener('click', redirectToProfile);
        }

    } else {
        navRight.innerHTML = `
            <li class="hover-underline-animation link" id="login">Login</li>
            <li class="hover-underline-animation link" id="register">Register</li>
        `;

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

function closeProfile() {
    document.getElementById('profile-nav-close').classList.remove('active');
    document.getElementById('profile-nav').classList.remove('active');
}

function openProfile() {
    document.getElementById('profile-nav-close').classList.toggle('active');
    document.getElementById('profile-nav').classList.toggle('active');
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


function redirectToHome() {
    window.location.href = '/home';  // Replace with your home URL
}

function redirectToLogin() {
    window.location.href = '/user/login';  // Replace with your login URL
}

function redirectToRegister() {
    window.location.href = '/user/register';  // Replace with your register URL
}

function redirectToHistory() {
    window.location.href = '/history';  // Replace with your history page URL
}

function redirectToProfile() {
    window.location.href = '/company/profile';  // Replace with your profile page URL
}