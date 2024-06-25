<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes - Recipe Sharing Website</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../../assets/dist/img/WebImg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.8) !important;
        }

        .navbar .nav-link,
        .navbar .navbar-brand {
            color: white !important;
        }

        .content {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
        }

        .recipe-card img {
            height: 200px;
            object-fit: cover;
        }

        .recipe-card {
            margin-bottom: 20px;
        }

        .pagination {
            justify-content: center;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
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

    <div class="container content">
        <h1><?php echo !isset($_GET['search']) ? 'Recipes' : 'Result: ' . "'" . $_GET['search'] . "'" ?></h1>

        <form method="GET" action="Recipe.page.php">
            <div class="row mb-4">
                <div class="col-md-3">
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search for recipes..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <div class="col-md-2">
                    <select id="cuisineFilter" name="cuisine" class="form-control">
                        <option value="">All Cuisines</option>
                        <option value="">None</option>
                        <?php foreach ($cuisines as $cuisine) : ?>
                            <option value="<?php echo htmlspecialchars($cuisine); ?>" <?php echo $selectedCuisine == $cuisine ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cuisine); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="mealTypeFilter" name="mealType" class="form-control">
                        <option value="">All Meal Types</option>
                        <option value="">None</option>
                        <?php foreach ($mealTypes as $mealType) : ?>
                            <option value="<?php echo htmlspecialchars($mealType); ?>" <?php echo $selectedMealType == $mealType ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mealType); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="dietRestrictionFilter" name="dietRestriction" class="form-control">
                        <option value="">All Diet Restrictions</option>
                        <option value="">None</option>
                        <?php foreach ($dietRestrictions as $dietRestriction) : ?>
                            <option value="<?php echo htmlspecialchars($dietRestriction); ?>" <?php echo $selectedDietRestriction == $dietRestriction ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dietRestriction); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="filterButton" class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>

        <div class="row" id="recipe-cards">
            <?php if ($recipes->num_rows > 0) : ?>
                <?php while ($row = $recipes->fetch_assoc()) : ?>
                    <div class="col-md-4 recipe-card-container">
                        <div class="card recipe-card">
                            <img src="<?php echo htmlspecialchars($row["ImagePath"]); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row["RecipeName"]); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row["RecipeName"]) . "<br><br> by <br>" . '<span><br>' . htmlspecialchars($row['AddedBy']) . '</span>'; ?></h5>
                                <a href="view_recipe.php?id=<?php echo htmlspecialchars($row["RecipeID"]); ?>" class="btn btn-primary">View</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No recipes found.</p>
            <?php endif; ?>
        </div>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $numPages; $i++) : ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>&cuisine=<?php echo urlencode($selectedCuisine); ?>&mealType=<?php echo urlencode($selectedMealType); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Recipe Oasis. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>