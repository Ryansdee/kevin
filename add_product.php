<?php
// Autoriser les requêtes depuis le frontend (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Vérifier si la requête est bien POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $file = "products.json";

    // Lire le fichier JSON existant
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
    } else {
        $data = [];
    }

    // Récupérer les données envoyées par le formulaire
    $input = json_decode(file_get_contents("php://input"), true);

    // Vérifier si les champs sont remplis
    if (isset($input["name"], $input["description"], $input["price"], $input["stock"], $input["image"])) {
        $newProduct = [
            "id" => time(),
            "name" => $input["name"],
            "description" => $input["description"],
            "price" => (float) $input["price"],
            "stock" => (int) $input["stock"],
            "image" => $input["image"]
        ];

        // Ajouter le nouveau produit
        $data[] = $newProduct;

        // Enregistrer dans le fichier JSON
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

        // Retourner une réponse JSON
        echo json_encode(["message" => "Produit ajouté avec succès !"]);
    } else {
        echo json_encode(["error" => "Tous les champs sont requis !"]);
    }
} else {
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>
