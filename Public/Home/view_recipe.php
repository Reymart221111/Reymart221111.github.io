<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Recipe - Recipe Sharing Website</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            /*background-image: url('../../assets/dist/img/WebImg.jpg');/*
            /* Update with the correct path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
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

        .content {
            background-color: rgba(255, 255, 255, 0.9);
            /* Semi-transparent background for readability */
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            flex-grow: 1;
        }

        .recipe-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .recipe-section {
            margin-top: 20px;
        }

        footer {
            background-color: #333;
            /* Dark background */
            color: #fff;
            /* Light text */
            padding: 40px 20px;
            /* More padding for better spacing */
            margin-top: 40px;
            /* Separate footer from content */
        }

        footer h5 {
            font-size: 18px;
            /* Slightly larger font for headings */
            margin-bottom: 20px;
            font-weight: bold;
        }

        footer p,
        footer a {
            font-size: 16px;
            /* Increase text size for better readability */
            color: #fff;
            /* Set text and link color to white */
        }

        footer a:hover {
            color: #ddd;
            /* Lighter color for hover */
            text-decoration: underline;
            /* Underline on hover for better indication */
        }

        footer .list-unstyled {
            padding-left: 0;
            list-style: none;
        }

        footer .list-unstyled li {
            margin-bottom: 10px;
            /* Space between list items */
        }

        footer .list-unstyled a {
            color: #fff;
            /* Link color */
            text-decoration: none;
            /* Remove underline from links */
        }

        footer .list-unstyled a:hover {
            color: #ddd;
            /* Highlight on hover */
        }

        .footer-bottom {
            background-color: #222;
            /* Darker bottom section */
            padding: 10px;
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
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
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="AboutUs.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../login.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container content">
        <?php
        $conn = new mysqli('localhost', 'root', '', 'recipeapp');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $recipeID = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $sql = "SELECT 
                RecipeDetails.RecipeName,
                RecipeDetails.Ingredients,
                RecipeDetails.Procedures,
                RecipeDetails.CookingTime,
                RecipeDetails.ServingSize,
                RecipeDetails.ImagePath,
                RecipeDetails.AddedDate,
                Users.UserName AS AddedBy,
                CuisineNationality.CuisineOrigin AS Cuisine,
                MealType.MealType AS MealType,
                DietRestriction.Restriction AS Restriction
                FROM 
                RecipeDetails
                JOIN 
                Users ON RecipeDetails.AddedBy = Users.UserID
                LEFT JOIN 
                CuisineNationality ON RecipeDetails.CuisineID = CuisineNationality.CuisineID
                LEFT JOIN 
                MealType ON RecipeDetails.MealTypeID = MealType.MealTypeID
                LEFT JOIN 
                DietRestriction ON RecipeDetails.RestrictionID = DietRestriction.RestrictionID
                WHERE RecipeDetails.RecipeID = $recipeID";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo '<h1>' . $row["RecipeName"] . '</h1>';
            echo '<p>by ' . $row["AddedBy"] . '</p>';
            echo '<img src="' . $row["ImagePath"] . '" class="recipe-image" alt="' . $row["RecipeName"] . '">';
            echo '<div class="recipe-section">';
            echo '<h3>Ingredients</h3>';
            echo '<p>' . nl2br($row["Ingredients"]) . '</p>';
            echo '</div>';
            echo '<div class="recipe-section">';
            echo '<h3>Instructions</h3>';
            echo '<p>' . nl2br($row["Procedures"]) . '</p>';
            echo '</div>';
            echo '<div class="recipe-section">';
            echo '<p><strong>Cooking Time:</strong> ' . $row["CookingTime"] . ' minutes</p>';
            echo '<p><strong>Serving Size:</strong> ' . $row["ServingSize"] . ' servings</p>';
            echo '<p><strong>Cuisine:</strong> ' . ($row["Cuisine"] ? $row["Cuisine"] : 'None') . '</p>';
            echo '<p><strong>Meal Type:</strong> ' . ($row["MealType"] ? $row["MealType"] : 'None') . '</p>';
            echo '<p><strong>Dietary Restriction:</strong> ' . ($row["Restriction"] ? $row["Restriction"] : 'None') . '</p>';
            echo '</div>';
        } else {
            echo '<p>Recipe not found.</p>';
        }

        $conn->close();
        ?>

        <!-- Button to go back to the previous page -->
        <div class="text-center">
            <button onclick="history.back()" class="btn btn-secondary mt-3">Go Back</button>
        </div>
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
                            <a href="../../index.php">Home</a>
                        </li>
                        <li>
                            <a href="Contact.php">Contact</a>
                        </li>
                        <li>
                            <a href="AboutUs.php">About Us</a>
                        </li>
                        <li>
                            <a href="../../login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom bg-dark text-white p-3">
            © 2024 Recipe Oasis
        </div>
    </footer>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>