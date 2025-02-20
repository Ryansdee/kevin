<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2>Bienvenue, <?php echo $_SESSION['username']; ?>!</h2>
                <p>Voici votre tableau de bord d'administration.</p>
                <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
            </div>
        </div>

        <!-- Section des graphiques -->
        <div class="row mt-5">
            <div class="col-md-6">
                <canvas id="stockChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="priceChart"></canvas>
            </div>
        </div>

        <!-- Ajouter un tag aux produits -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h3>Ajouter un Tag à un Produit</h3>
                <form id="add-tag-form">
                    <div class="mb-3">
                        <label for="product-id" class="form-label">ID du Produit</label>
                        <input type="number" class="form-control" id="product-id" required>
                    </div>
                    <div class="mb-3">
                        <label for="tag" class="form-label">Tag à Ajouter</label>
                        <input type="text" class="form-control" id="tag" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter Tag</button>
                </form>
            </div>
        </div>

        <!-- Ajouter un nouveau produit -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h3>Ajouter un Nouveau Produit</h3>
                <form id="add-product-form" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du produit</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Prix (€)</label>
                        <input type="number" class="form-control" id="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image du Produit</label>
                        <input type="file" class="form-control" id="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter Produit</button>
                </form>
            </div>
        </div>

        <!-- Tableau des produits existants -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h3>Liste des Produits</h3>
                <table class="table table-striped" id="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Les produits seront chargés ici -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Ajouter un nouveau produit via le formulaire
        document.getElementById('add-product-form').onsubmit = async function(event) {
    event.preventDefault(); // Empêcher la soumission normale du formulaire

    const name = document.getElementById('name').value.trim();
    const description = document.getElementById('description').value.trim();
    const price = parseFloat(document.getElementById('price').value);
    const stock = parseInt(document.getElementById('stock').value);
    const image = document.getElementById('image').files[0];  // Récupérer l'image téléchargée

    if (!name || !description || isNaN(price) || isNaN(stock)) {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('price', price);
    formData.append('stock', stock);

    if (image) {
        formData.append('image', image); // Ajouter l'image si elle est présente
    }

    // On va maintenant envoyer les données via POST sans utiliser JSON.stringify()
    const response = await fetch('save_products.php', {
        method: 'POST',
        body: formData,  // Envoyer via FormData
    });

    const data = await response.json();
    if (data.message) {
        alert(data.message); // Affiche un message de succès
        displayProducts(); // Rafraîchir la liste des produits
    } else {
        alert(data.error); // Affiche un message d'erreur
    }
};


        // Charger les produits à partir de la base de données
        async function loadProducts() {
            const response = await fetch('save_products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})  // Pas de paramètres, juste une requête pour obtenir tous les produits
            });

            const data = await response.json();
            return data.error ? [] : data;  // Si erreur, renvoyer un tableau vide
        }

        // Mettre à jour l'affichage des produits dans le tableau
        async function displayProducts() {
            const products = await loadProducts();
            const tableBody = document.querySelector('#product-table tbody');
            tableBody.innerHTML = ''; // Vider le tableau avant de le remplir

            products.forEach(product => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.description}</td>
                    <td>${product.price} €</td>
                    <td>${product.stock}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})">Modifier</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Supprimer</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Modifier un produit
        async function editProduct(productId) {
            const products = await loadProducts();
            const product = products.find(p => p.id === productId);

            if (product) {
                // Pré-remplir le formulaire avec les données du produit
                document.getElementById('name').value = product.name;
                document.getElementById('description').value = product.description;
                document.getElementById('price').value = product.price;
                document.getElementById('stock').value = product.stock;

                // Change the submit behavior to update the product
                document.getElementById('add-product-form').onsubmit = async function(event) {
                    event.preventDefault(); // Empêcher la soumission normale du formulaire

                    const name = document.getElementById('name').value.trim();
                    const description = document.getElementById('description').value.trim();
                    const price = parseFloat(document.getElementById('price').value);
                    const stock = parseInt(document.getElementById('stock').value);

                    if (!name || !description || isNaN(price) || isNaN(stock)) {
                        alert("Veuillez remplir tous les champs.");
                        return;
                    }

                    const updatedProduct = {
                        id: productId, 
                        name: name,
                        description: description,
                        price: price,
                        stock: stock
                    };

                    // Envoi des données via AJAX à PHP
                    const response = await fetch('save_products.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(updatedProduct)
                    });

                    const data = await response.json();
                    if (data.message) {
                        alert(data.message); // Affiche un message de succès
                        displayProducts(); // Rafraîchir la liste des produits
                    } else {
                        alert(data.error); // Affiche un message d'erreur
                    }
                };
            }
        }

        // Supprimer un produit
        async function deleteProduct(productId) {
            const confirmDelete = confirm('Êtes-vous sûr de vouloir supprimer ce produit?');
            if (confirmDelete) {
                const response = await fetch('save_products.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ deleteId: productId }) // Passer l'ID du produit à supprimer
                });

                const data = await response.json();
                if (data.message) {
                    displayProducts();  // Réafficher les produits après suppression
                    alert('Produit supprimé avec succès!');
                } else {
                    alert(data.error); // Afficher une erreur si la suppression échoue
                }
            }
        }

        // Charger et afficher les produits dès que la page est prête
        window.onload = displayProducts;
    </script>
</body>
</html>
