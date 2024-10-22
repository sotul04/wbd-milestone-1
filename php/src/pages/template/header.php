<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/x-icon" href="http://localhost:8000/public/assets/icons/logo.ico">
    
    <meta name="description" content="Linkinpurry - imitation of LinkedIn">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#ffffff">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="http://localhost:8000/public/css/reset.css" rel="stylesheet">
    <link href="http://localhost:8000/public/css/global.css" rel="stylesheet">
    <link href="http://localhost:8000/public/css/navbar.css" rel="stylesheet">
    <link href="http://localhost:8000/public/css/toast.css" rel="stylesheet">
    <link href="http://localhost:8000/public/css/modal.css" rel="stylesheet">

    <title><?= isset($title) ? htmlspecialchars($title) : "Linkin" ?></title>

    <script src="http://localhost:8000/public/js/toast.js" defer></script>
    <script src="http://localhost:8000/public/js/modal.js" defer></script>
</head>

<div id="toastContainer">
</div>

<div id="modalContainer"></div>