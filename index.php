<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Sharing Website</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('assets/dist/img/WebImg.jpg');
            /* Update with the correct path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.8) !important;
            /* Darker background for the navbar */
        }

        .navbar .nav-link {
            color: white !important;
            /* Make the nav links white */
        }

        .navbar .navbar-brand {
            color: white !important;
            /* Make the brand white */
        }

        .jumbotron {
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent background for readability */
        }

        footer {
            background-color: rgba(255, 255, 255, 0.8);
            /* Semi-transparent background for the footer */
            margin-top: auto;
            /* Ensure footer stays at the bottom */
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Recipe Oasis</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Public/Home/AboutUs.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Header -->
    <header class="jumbotron text-center">
        <h1 class="display-4">Welcome to Recipe Oasis</h1>
        <p class="lead">Discover and share your favorite recipes with the world</p>
        <a href="Public/Home/Recipe.page.php" class="btn btn-primary btn-lg">View Recipes</a>
    </header>

    <!-- Main Content -->
    <div class="container text-center mb-5">
        <p>Explore a variety of recipes shared by our community. Join us and share your own!</p>
        <a href="loginRecipe.php" class="btn btn-secondary btn-lg">Share a Recipe</a>
    </div>

    <!-- Footer -->
    <footer class="text-center text-lg-start p-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Recipe Oasis</h5>
                    <p>
                        Share your favorite recipes with our community. Discover new and exciting recipes from around the world.
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Links</h5>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#!" class="text-dark">Home</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Contact</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">About Us</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center bg-dark text-white p-3">
            © 2024 Recipe Oasis
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>