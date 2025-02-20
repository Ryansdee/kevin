<?php
header('Content-Type: application/json');

// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplace avec ton nom d'utilisateur MySQL
$password = "root"; // Remplace avec ton mot de passe MySQL
$dbname = "admin_db";

try {
    // Créer la connexion
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurer le mode d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les produits
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();

    // Récupérer les produits sous forme de tableau
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les produits en JSON
    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des produits: ' . $e->getMessage()]);
}

// Fermer la connexion
$conn = null;
?>
