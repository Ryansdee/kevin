<?php
header('Content-Type: application/json');

// Vérifier que la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données JSON envoyées
    $data = json_decode(file_get_contents('php://input'), true);

    // Vérifier que les données sont valides
    if (isset($data['name'], $data['description'], $data['price'], $data['stock'], $data['image'])) {
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $stock = $data['stock'];
        $image = $data['image'];

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

            // Préparer la requête SQL pour insérer un produit
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (:name, :description, :price, :stock, :image)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':image', $image);

            // Exécuter la requête
            $stmt->execute();

            // Retourner une réponse JSON indiquant le succès
            echo json_encode(['message' => 'Produit ajouté avec succès!']);
        } catch (PDOException $e) {
            // En cas d'erreur, retourner un message d'erreur
            echo json_encode(['error' => 'Erreur lors de l\'ajout du produit: ' . $e->getMessage()]);
        }

        // Fermer la connexion
        $conn = null;
    } else {
        echo json_encode(['error' => 'Données invalides']);
    }
} else {
    echo json_encode(['error' => 'Méthode non autorisée']);
}
?>
