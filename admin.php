<?php
// Vérifier si l'utilisateur est admin (simulé via session)
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: index.php"); // Redirection si pas admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ajouter un Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Ajouter un Produit</h1>

        <?php
        // Afficher un message de confirmation après ajout
        if (isset($_GET["success"]) && $_GET["success"] == "1") {
            echo '<div class="alert alert-success text-center">Produit ajouté avec succès !</div>';
        }
        ?>

        <form action="add_product.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nom du produit</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" id="description" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Prix</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">URL de l'image</label>
                <input type="text" name="image" id="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Ajouter le produit</button>
        </form>

        <div class="text-center mt-4">
            <a href="index.php">Retour à la liste des produits</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
