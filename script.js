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
        window.location.href = "index.html";
    } else {
        alert("Identifiants incorrects !");
    }
}

// Vérification de l'admin
function checkAdmin() {
    const user = JSON.parse(sessionStorage.getItem("user"));
    if (!user || user.role !== "admin") {
        alert("Accès refusé !");
        window.location.href = "index.html";
    }
}

// Charger les produits
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
                            <p><strong>${product.price}€</strong> | Stock: ${product.stock}</p>
                        </div>
                    </div>
                `;
            });
        });
}

// Ajouter un produit (Admin)
function addProduct(event) {
    event.preventDefault();

    const newProduct = {
        id: Date.now(),
        name: document.getElementById("name").value,
        description: document.getElementById("description").value,
        price: parseFloat(document.getElementById("price").value),
        stock: parseInt(document.getElementById("stock").value),
        image: document.getElementById("image").value
    };

    alert("Produit ajouté (simulation) !");
    console.log(newProduct);
}
