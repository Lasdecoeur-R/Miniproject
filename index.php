<?php
include 'db.php';  // Connexion à la base de données

// Récupération de la catégorie à filtrer (si spécifiée) et du texte de recherche
$categorie_filter = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Requête SQL avec le filtre de catégorie et la recherche (si spécifiés)
$sql = "SELECT recettes.*, categories.nom AS categorie FROM recettes 
        JOIN categories ON recettes.categorie_id = categories.id WHERE 1";

// Ajout du filtre de catégorie
if ($categorie_filter > 0) {
    $sql .= " AND recettes.categorie_id = :categorie";
}

// Ajout du filtre de recherche
if (!empty($search)) {
    $sql .= " AND recettes.nom LIKE :search";
}

$sql .= " ORDER BY recettes.id DESC";
$stmt = $conn->prepare($sql);

// Préparer les paramètres de la requête
$params = [];
if ($categorie_filter > 0) {
    $params['categorie'] = $categorie_filter;
}
if (!empty($search)) {
    $params['search'] = '%' . $search . '%';
}

$stmt->execute($params);
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

        <!-- Formulaire de recherche -->
        <form class="form-inline mb-4 justify-content-center" action="index.php" method="get">
            <!-- Zone de recherche -->
            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Rechercher une recette" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success" type="submit">Rechercher</button>
            
            <!-- Inclure la catégorie sélectionnée dans le formulaire de recherche -->
            <?php if ($categorie_filter > 0): ?>
                <input type="hidden" name="categorie" value="<?php echo $categorie_filter; ?>">
            <?php endif; ?>
        </form>

        <!-- Boutons de filtre par catégorie -->
        <div class="btn-group mb-4 d-flex justify-content-center" role="group">
            <a href="index.php?search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 0 ? 'active' : ''; ?>">Afficher tout</a>
            <a href="index.php?categorie=1&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 1 ? 'active' : ''; ?>">Chinoise</a>
            <a href="index.php?categorie=2&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 2 ? 'active' : ''; ?>">Japonaise</a>
            <a href="index.php?categorie=3&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 3 ? 'active' : ''; ?>">Thaïlandaise</a>
            <a href="index.php?categorie=4&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-secondary <?php echo $categorie_filter == 4 ? 'active' : ''; ?>">Vietnamienne</a>
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
