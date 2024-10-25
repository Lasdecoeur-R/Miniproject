<?php include 'header.php'; ?>
<?php
include 'db.php';  // Connexion à la base de données

// Récupération de l'origine des recettes via un paramètre GET
$origine = isset($_GET['origine']) ? intval($_GET['origine']) : 0;

// Définir le nom de la catégorie selon l'origine
switch ($origine) {
    case 1:
        $categorie_nom = "Chinoise";
        break;
    case 2:
        $categorie_nom = "Japonaise";
        break;
    case 3:
        $categorie_nom = "Thaïlandaise";
        break;
    case 4:
        $categorie_nom = "Vietnamienne";
        break;
    default:
        $categorie_nom = "Toutes les Recettes";
        break;
}

// Récupération des recettes selon la catégorie sélectionnée
$sql = "SELECT * FROM recettes WHERE categorie_id = :origine ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['origine' => $origine]);
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes <?php echo htmlspecialchars($categorie_nom); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Site de Recettes</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="recettes.php">Recettes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ingredients.php">Ingrédients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu de la page des recettes -->
    <div class="container my-5">
        <h1 class="text-center">Recettes <?php echo htmlspecialchars($categorie_nom); ?></h1>
        <div class="row">
            <?php foreach ($recettes as $recette): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($recette['nom']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($recette['description']); ?></p>
                            <a href="recette_detail.php?id=<?php echo $recette['id']; ?>" class="btn btn-primary">Voir la recette</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
