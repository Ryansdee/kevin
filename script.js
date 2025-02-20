document.addEventListener("DOMContentLoaded", () => {
    // Vérifie sur quelle page on est
    if (document.getElementById("login-form")) {
        document.getElementById("login-form").addEventListener("submit", login);
    }

    if (document.getElementById("add-product-form")) {
        checkAdmin();
        document.getElementById("add-product-form").addEventListener("submit", addProduct);
    }

    if (document.getElementById("products-container")) {
        loadProducts();
    }

    if (document.getElementById("produits-container")) {
        loadProductsList();
    }
});

// Utilisateurs fictifs
const users = {
    admin: { password: "admin123", role: "admin" },
    user: { password: "user123", role: "user" }
};

// Fonction de connexion
function login(event) {
    event.preventDefault();
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if (users[username] && users[username].password === password) {
        sessionStorage.setItem("user", JSON.stringify(users[username]));
        alert("Connexion réussie !");
        window.location.href = "admin.html"; // Page admin
    } else {
        alert("Identifiants incorrects !");
    }
}

// Vérification de l'admin
function checkAdmin() {
    const user = JSON.parse(sessionStorage.getItem("user"));
    if (!user || user.role !== "admin") {
        alert("Accès refusé !");
        window.location.href = "index.html"; // Redirection vers la page d'accueil
    }
}

// Charger les produits pour le carousel
function loadProducts() {
    fetch("products.json")
        .then(response => response.json())
        .then(products => {
            const container = document.getElementById("products-container");
            container.innerHTML = "";
            products.forEach((product, index) => {
                const activeClass = index === 0 ? "active" : "";
                container.innerHTML += `
                    <div class="carousel-item ${activeClass}">
                        <img src="${product.image}" class="d-block w-100 " alt="${product.name}">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>${product.name}</h5>
                            <p>${product.description}</p>
                            <p><strong>${product.price}€</strong></p>
                        </div>
                    </div>
                `;
            });
        });
}

// Charger la liste des produits
function loadProductsList() {
    fetch("products.json")
        .then(response => response.json())
        .then(produits => {
            const container = document.getElementById("produits-container");
            container.innerHTML = ""; // Nettoyer avant d'ajouter les produits

            produits.forEach(produit => {
                const isBestSeller = produit.tags && produit.tags.includes('meilleure vente');
                let produitHTML = `
                    <div class="col-md-4 mt-5">
                        <div class="card" data-aos="fade-up">
                            <img src="${produit.image}" class="card-img-top" alt="${produit.name}">
                            <div class="card-body">
                                <h5 class="card-title">${produit.name}</h5>
                                <p class="card-text">${produit.description}</p>
                                ${isBestSeller ? '<span class="badge bg-warning text-dark">Meilleure Vente</span>' : ''}
                                <a href="#" class="btn btn-primary">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += produitHTML;
            });
        })
        .catch(error => console.error("Erreur lors du chargement des produits:", error));
}

// Ajouter un produit (Admin)
function addProduct(event) {
    event.preventDefault();

    const newProduct = {
        name: document.getElementById("name").value,
        description: document.getElementById("description").value,
        price: parseFloat(document.getElementById("price").value),
        stock: parseInt(document.getElementById("stock").value),
        image: document.getElementById("image").value
    };

    fetch("add_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(newProduct)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || data.error);
        if (!data.error) {
            window.location.href = "index.html"; // Retour à la liste des produits
        }
    })
    .catch(error => console.error("Erreur:", error));
}
