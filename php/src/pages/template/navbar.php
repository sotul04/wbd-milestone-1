<body>
    <nav class="navbar" aria-label="Main Navigation">
        <div class="navCt container">
            <div class="navLeft hover">
                <div class="navLogoSide" onclick="redirectToHome()" role="button" tabindex="0" aria-label="Go to Homepage">
                    <img src="<?php echo BASE_URL; ?>/public/assets/icons/logo.ico" alt="Linkinpurry Logo" loading="lazy">
                    <h1 class="navTitle">Purry</h1>
                </div>
            </div>
            <div class="navRight">
                <div id="profile-nav-close" aria-label="Profile Navigation Close"></div>
                <ul class="navItems" aria-label="Navigation Items">
                </ul>
                <button class="hamburger" aria-label="Open Navigation Menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
        </div>
    </nav>
