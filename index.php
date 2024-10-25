<!-- index.php -->
<?php
include 'header.php';  // Inclusion de la barre de navigation

// Connexion à la base de données
include 'db.php'; // Assurez-vous que ce fichier contient la connexion PDO à la base de données

// Sélectionnez quelques recettes aléatoires
$sql = "SELECT * FROM recettes ORDER BY RAND() LIMIT 4";
$stmt = $conn->prepare($sql);
$stmt->execute();
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Site de Recettes</title>
    <style>
        .hero-section {
            background-image: url('https://cdn.shopify.com/s/files/1/0590/7418/3357/files/ingredients_1024x1024.jpg?v=1686907423');
            background-size: cover;
            background-position: center;
            height: 70vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .hero-overlay {
            background: rgba(0, 0, 0, 0.5); /* Ombre pour le texte */
            padding: 30px;
            border-radius: 10px;
        }

        .search-section {
            margin-top: 30px;
            text-align: center;
        }

        .popular-searches {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .popular-searches a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- Section de la bannière principale -->
    <section class="hero-section">
        <div class="hero-overlay">
            <h1 class="display-4">Que veux-tu cuisiner ?</h1>
            <form class="form-inline justify-content-center my-4" action="recettes.php" method="get">
                <input type="text" class="form-control mr-2" name="search" placeholder="Trouve la recette parfaite">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
            <div class="popular-searches">
                <a href="recettes.php?search=riz+frit">Riz frit</a>
                <a href="recettes.php?search=porc+caramel">Porc caramel</a>
                <a href="recettes.php?search=bœuf+aux+oignons">Bœuf aux oignons</a>
                <a href="recettes.php?search=nouilles">Nouilles</a>
                <a href="recettes.php?search=fondue+chinoise">Fondue chinoise</a>
            </div>
        </div>
    </section>

    <!-- Section des recettes aléatoires -->
    <section class="container mt-5">
        <h2>Quelques recettes aléatoires</h2>
        <div class="row">
            <?php foreach ($recettes as $recette): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($recette['nom']); ?></h5>
                            <a href="recette.php?id=<?php echo $recette['id']; ?>" class="btn btn-primary">Voir la recette</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>

</html>
