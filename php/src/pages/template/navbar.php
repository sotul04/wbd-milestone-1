<body onload="initPage()">
    <nav class="navbar">
        <div class="navCt container">
            <div class="navLeft hover">
                <div class="navLogoSide" onclick="redirectToHome()">
                    <img src="http://localhost:8000/public/assets/icons/linkinpurry.ico" alt="Linkinpurry Logo">
                    <h1 class="navTitle">Purry</h1>
                </div>
            </div>
            <div class="navRight">
                <ul class="navItems">
                    <li class="hover-underline-animation" onclick="redirectToLogin()">Login</li>
                    <li class="hover-underline-animation" onclick="redirectToRegister()">Register</li>
                </ul>
                <div class="navProfile">
                    <img src="<?php echo BASE_URL; ?>/public/assets/icons/profile.ico" alt="profile picture">
                </div>
            </div>
        </div>
    </nav>