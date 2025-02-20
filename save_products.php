<?php
header('Content-Type: application/json');

// Vérifier que la méthode de la requête est POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification de l'existence des données nécessaires
    if (isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['stock'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

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

            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                // Gestion du téléchargement d'image
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageName = basename($_FILES['image']['name']);
                $imagePath = 'uploads/' . $imageName; // Dossier "uploads/" pour les images

                // Déplacer le fichier téléchargé dans le dossier des images
                if (move_uploaded_file($imageTmpName, $imagePath)) {
                    // Si l'image est téléchargée avec succès
                } else {
                    echo json_encode(['error' => 'Erreur lors de l\'upload de l\'image']);
                    exit();
                }
            }

            // Préparer la requête SQL pour insérer un produit
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) 
                                    VALUES (:name, :description, :price, :stock, :image)");

            // Lier les valeurs des paramètres
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':image', $imagePath);

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
