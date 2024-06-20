var url = new URL(window.location.href);

// Récupération de la valeur de l'ID à partir des paramètres de l'URL
let id = url.searchParams.get("id");

ajaxRequest('GET', 'php/request.php/preds?age&id=' + id, (response) => {
    var jsonString = response;

    // Convertir la chaîne JSON en un tableau d'objets JavaScript
    var jsonData = JSON.parse(jsonString);

    // Accéder à la première (et unique, dans ce cas) entrée du tableau
    var ageEstim = jsonData[0].age_estim;

    // Utiliser la valeur récupérée comme nécessaire
    // console.log("Valeur de age_estim :", ageEstim);

    // Afficher le résultat
    document.getElementById('valueage').innerHTML = ageEstim;
});

ajaxRequest('GET', 'php/request.php/preds?risque&id=' + id, (response) => {
    // console.log('response' + response);
    
    if (response == 'false') {
        document.getElementById('valuerisque').innerHTML = "Pas de risque estimé pour cet arbre";
        return;
    }
    else if(response == 'true') 
    {
        document.getElementById('valuerisque').innerHTML = "attention cet arbre peux tomber";
    }

});

ajaxRequest('GET', 'php/request.php/preds?map&id=' + id, (response) => {
    console.log('Cette reponse')
    console.log(response);
    
    // Initialiser la carte centrée sur Saint-Quentin
    var map = L.map('map').setView([response.longi, response.lat], 13);

    // Ajouter les tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ajouter les marqueurs sur la carte
    response.forEach(arbre => {
        L.marker([arbre.longi, arbre.lat]).addTo(map)
        .bindPopup(`
            <b>Arbre</b><br>
            <b>Espèce:</b> ${arbre.espece}<br>
            <b>Hauteur:</b> ${arbre.haut_tronc} m<br>
            <b>Coordonnées:</b> ${arbre.lat}, ${arbre.longi}
        `);
    });
});

