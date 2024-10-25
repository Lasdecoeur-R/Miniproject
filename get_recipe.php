<?php
include 'db.php';  // Connexion à la base de données

// Récupère l'ID de la recette à afficher
$recipeId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Requête SQL pour récupérer les détails de la recette
$sql = "SELECT * FROM recettes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$recipeId]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si la recette existe et retourne les données en JSON
if ($recipe) {
    echo json_encode($recipe);
} else {
    echo json_encode(['error' => 'Recette non trouvée.']);
}
?>
