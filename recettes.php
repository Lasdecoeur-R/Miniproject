<?php
include 'db.php';  // Connexion à la base de données
include 'header.php';  // Inclusion de la barre de navigation

// Récupération de la catégorie et du texte de recherche
$categorie_filter = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Requête SQL avec filtre de catégorie et recherche
$sql = "SELECT recettes.*, categories.nom AS categorie FROM recettes 
        JOIN categories ON recettes.categorie_id = categories.id WHERE 1";

if ($categorie_filter > 0) {
    $sql .= " AND recettes.categorie_id = :categorie";
}

if (!empty($search)) {
    $sql .= " AND recettes.nom LIKE :search";
}

$sql .= " ORDER BY recettes.id DESC";
$stmt = $conn->prepare($sql);

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

<div class="container my-4">
    <h1 class="text-center mb-4">Recettes</h1>

    <!-- Boutons de filtre par catégorie avec Bootstrap -->
    <div class="btn-group mb-4 d-flex justify-content-center" role="group">
        <a href="recettes.php?search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-primary <?php echo $categorie_filter == 0 ? 'active' : ''; ?>">Afficher tout</a>
        <a href="recettes.php?categorie=1&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-primary <?php echo $categorie_filter == 1 ? 'active' : ''; ?>">Chinoise</a>
        <a href="recettes.php?categorie=2&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-primary <?php echo $categorie_filter == 2 ? 'active' : ''; ?>">Japonaise</a>
        <a href="recettes.php?categorie=3&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-primary <?php echo $categorie_filter == 3 ? 'active' : ''; ?>">Thaïlandaise</a>
        <a href="recettes.php?categorie=4&search=<?php echo htmlspecialchars($search); ?>" class="btn btn-outline-primary <?php echo $categorie_filter == 4 ? 'active' : ''; ?>">Vietnamienne</a>
    </div>

    <!-- Formulaire de recherche -->
    <form class="form-inline mb-4 justify-content-center" action="recettes.php" method="get">
        <input class="form-control mr-2" type="search" name="search" placeholder="Rechercher une recette" value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-success" type="submit">Rechercher</button>
        <?php if ($categorie_filter > 0): ?>
            <input type="hidden" name="categorie" value="<?php echo $categorie_filter; ?>">
        <?php endif; ?>
    </form>

    <!-- Liste des recettes améliorée avec Bootstrap -->
    <div class="row">
        <?php foreach ($recettes as $recette): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($recette['nom']); ?></h5>
                    <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($recette['categorie']); ?></small></p>
                    <a href="get_recipe.php?id=<?php echo $recette['id']; ?>" class="btn btn-primary">Voir la recette</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Inclusion des fichiers Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
