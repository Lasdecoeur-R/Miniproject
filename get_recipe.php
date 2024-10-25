<?php
include 'db.php';  // Connexion à la base de données
include 'header.php';  // Inclusion de la barre de navigation

// Vérifiez si un ID de recette a été fourni dans l'URL
if (isset($_GET['id'])) {
    $recette_id = intval($_GET['id']);

    // Récupérer les informations de la recette
    $sql_recette = "SELECT * FROM recettes WHERE id = :recette_id";
    $stmt_recette = $conn->prepare($sql_recette);
    $stmt_recette->execute(['recette_id' => $recette_id]);
    $recette = $stmt_recette->fetch(PDO::FETCH_ASSOC);

    // Si la recette existe, récupérer ses ingrédients
    if ($recette) {
        $sql_ingredients = "
            SELECT i.nom, ri.quantite
            FROM recette_ingredients ri
            JOIN ingredients i ON ri.ingredient_id = i.id
            WHERE ri.recette_id = :recette_id
        ";
        $stmt_ingredients = $conn->prepare($sql_ingredients);
        $stmt_ingredients->execute(['recette_id' => $recette_id]);
        $ingredients = $stmt_ingredients->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "<div class='container'><h1>Recette non trouvée</h1></div>";
        exit;
    }
} else {
    echo "<div class='container'><h1>Aucune recette sélectionnée</h1></div>";
    exit;
}
?>

<div class="container my-4">
    <h1 class="text-center"><?php echo htmlspecialchars($recette['nom']); ?></h1>
    <p class="lead text-center"><?php echo htmlspecialchars($recette['description']); ?></p>

    <h2 class="my-4">Ingrédients</h2>
    <ul class="list-group">
        <?php foreach ($ingredients as $ingredient): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($ingredient['nom']); ?> - <?php echo htmlspecialchars($ingredient['quantite']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
    
    <!-- Ajoutez ici d'autres informations sur la recette si nécessaire -->
    
    <a href="recettes.php" class="btn btn-secondary mt-4">Retour aux recettes</a>
</div>

<!-- Inclusion des fichiers Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
