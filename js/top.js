function NavbarComponent() {
    return `
        <nav class="navbar navbar-expand-lg bg-body-secondary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                    
                    <!-- Formulaire de recherche -->
                    <form class="d-flex me-3" role="search" onsubmit="search(event)">
                        <input id="searchInput" class="form-control me-2" type="search" placeholder="Rechercher..." aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Rechercher</button>
                    </form>

                    <!-- Boutons Connexion / Inscription -->
                    <div class="d-flex">
                        <button class="btn btn-outline-primary me-2" onclick="login()">Se connecter</button>
                        <button class="btn btn-primary" onclick="register()">S'inscrire</button>
                    </div>
                </div>
            </div>
        </nav>
    `;
}

// Injecte la navbar dans le HTML
document.getElementById("navbar").innerHTML = NavbarComponent();

// Fonctions pour gérer la connexion et l'inscription
function login() {
    alert("Redirection vers la page de connexion...");
    window.location.href = "login.html"; // Décommente pour une vraie redirection
}

function register() {
    alert("Redirection vers la page d'inscription...");
    window.location.href = "register.html"; // Décommente pour une vraie redirection
}

// Fonction de recherche
function search(event) {
    event.preventDefault(); // Empêche le rechargement de la page
    let query = document.getElementById("searchInput").value;
    if (query.trim() !== "") {
        console.log("Recherche : " + query); // Affiche la recherche dans la console
        alert("Vous avez recherché : " + query);
        // window.location.href = "search.html?q=" + encodeURIComponent(query); // Décommente pour rediriger vers une page de résultats
    }
}
