<?php
$servername = "localhost";
$username = "root";  // Remplace "root" par ton nom d'utilisateur MySQL si nécessaire
$password = "";      // Remplace "" par ton mot de passe MySQL si nécessaire
$dbname = "recettes_db";  // Nom de ta base de données

try {
    // Créer une connexion avec PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO sur exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    die(); // Arrêter le script si la connexion échoue
}
?>
