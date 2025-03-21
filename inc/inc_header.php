<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="inc/style.css">

    <style>
        body {
            font-family: "Outfit", serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        #nav-login .btn {
            font-family: "Outfit", sans-serif;
            font-weight: 500px; 
        }
        
        @media (min-width: 992px) {
            #mainNav .navbar-nav {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }
            
            #mainNav .navbar-nav .nav-item {
                text-align: center;
            }
            
            #mainNav .navbar-nav .nav-item + .nav-item {
                margin-left: 2rem;
            }
            
            .navbar-brand {
                z-index: 1;
                position: relative;
            }
            
            #nav-login {
                z-index: 1;
                position: absolute;
                right: 0;
                display: flex;
                gap: 10px;
            }
            
            .navbar .container {
                position: relative;
            }
        }
        
        #nav-login .btn {
            margin-left: 10px;
        }
        
        #nav-login .btn-outline-dark {
            min-width: 110px;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="bi bi-bootstrap-fill fs-2 text-dark me-2"></i>
                <span class="fs-4 fw-semibold">Blog</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav text-center">
                    <!-- Home link is always visible -->
                    <li class="nav-item">
                        <a class="nav-link text-dark fs-5" href="index.php">Home</a>
                    </li>
                    <!-- Manage article link only visible when logged in -->
                    <?php if (isset($_SESSION['username'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-dark fs-5" href="write.php">Manage article</a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="d-flex" id="nav-login">
                    <?php if (isset($_SESSION['username'])) { ?>
                        <a class="btn btn-outline-dark px-4" href="logout.php">
                            Logout ->
                        </a>
                    <?php } 
                    else 
                    { ?>
                        <li class="nav-item">
                        <a class="btn btn-outline-dark px-4" href="register.php">
                            Sign Up ->
                        </a>
                        <span class="align-self-center mx-2 text-dark">/</span>
                        <a class="btn btn-outline-dark px-4" href="login.php">
                            Login ->
                        </a>
                        </li>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>