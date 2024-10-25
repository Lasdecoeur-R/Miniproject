<?php
include 'db.php';  // Connexion à la base de données

// Récupération de la catégorie à filtrer (si spécifiée)
$categorie_filter = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;

// Requête SQL avec le filtre de catégorie (si spécifié)
$sql = "SELECT recettes.*, categories.nom AS categorie FROM recettes 
        JOIN categories ON recettes.categorie_id = categories.id";
if ($categorie_filter > 0) {
    $sql .= " WHERE recettes.categorie_id = :categorie";
}
$sql .= " ORDER BY recettes.id DESC";

$stmt = $conn->prepare($sql);

// Ajout du paramètre de catégorie si nécessaire
if ($categorie_filter > 0) {
    $stmt->execute(['categorie' => $categorie_filter]);
} else {
    $stmt->execute();
}

$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Site de Recettes</h1>

        <!-- Boutons de filtre par catégorie -->
        <div class="btn-group mb-4 d-flex justify-content-center" role="group">
            <a href="index.php" class="btn btn-outline-secondary <?php echo $categorie_filter == 0 ? 'active' : ''; ?>">Afficher tout</a>
            <a href="index.php?categorie=1" class="btn btn-outline-secondary <?php echo $categorie_filter == 1 ? 'active' : ''; ?>">Chinoise</a>
            <a href="index.php?categorie=2" class="btn btn-outline-secondary <?php echo $categorie_filter == 2 ? 'active' : ''; ?>">Japonaise</a>
            <a href="index.php?categorie=3" class="btn btn-outline-secondary <?php echo $categorie_filter == 3 ? 'active' : ''; ?>">Thaïlandaise</a>
            <a href="index.php?categorie=4" class="btn btn-outline-secondary <?php echo $categorie_filter == 4 ? 'active' : ''; ?>">Vietnamienne</a>
        </div>

        <!-- Liste des recettes (comme avant) -->
        <div class="row">
            <?php foreach ($recettes as $recette): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($recette['nom']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($recette['description']); ?></p>
                        <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($recette['categorie']); ?></small></p>
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
