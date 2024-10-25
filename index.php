<?php
include 'db.php';  // Connexion à la base de données

$search = $_GET['search'] ?? '';  // Récupérer la recherche si elle existe
$sql = "SELECT recettes.*, categories.nom AS categorie FROM recettes 
        JOIN categories ON recettes.categorie_id = categories.id 
        WHERE recettes.nom LIKE :search ORDER BY recettes.id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute(['search' => '%' . $search . '%']);
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes</title>
    <!-- Intégration de Bootstrap via CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4 text-center">Site de Recettes</h1>

        <!-- Formulaire de recherche -->
        <form class="form-inline mb-4 justify-content-center" action="index.php" method="get">
            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Rechercher une recette" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success" type="submit">Rechercher</button>
        </form>

        <!-- Liste des recettes avec Bootstrap Cards -->
        <div class="row">
            <?php foreach ($recettes as $recette): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($recette['nom']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($recette['description']); ?></p>
                        <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($recette['categorie']); ?></small></p>
                        <a href="edit.php?id=<?php echo $recette['id']; ?>" class="btn btn-primary">Modifier</a>
                        <a href="delete.php?id=<?php echo $recette['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <a href="create.php" class="btn btn-success my-4">Ajouter une nouvelle recette</a>
        </div>
    </div>

    <!-- Intégration de Bootstrap et dépendances via CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
