function CardComponent(title, content) {
    return `
        <div style="border:1px solid #ccc; padding:10px; margin:10px; border-radius:5px;">
            <h2>${title}</h2>
            <p>${content}</p>
        </div>
    `;
}

document.getElementById("app").innerHTML = CardComponent("Titre du composant", "Ceci est un exemple de composant.");