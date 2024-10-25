<?php
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "nom_de_votre_base_de_donnees"; // Remplacez par le nom de votre base de données

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupération de l'ID de la recette depuis la requête GET
$recipeId = $_GET['id'];

// Préparation de la requête pour récupérer les données
$sql = "SELECT * FROM recettes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $recipeId);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

// Renvoyer les données sous forme JSON
echo json_encode($recipe);

$conn->close();
?>
