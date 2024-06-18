'use strict'

ajaxRequest('POST', 'php/request.php/map/', (response) => {
    console.log('Données reçues:', response);

    // Initialiser la carte centrée sur Saint-Quentin
    var map = L.map('map').setView([49.848, 3.287], 13);

    // Ajouter les tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ajouter les marqueurs sur la carte
    response.forEach(arbre => {
        console.log(arbre.lat)
        L.marker([arbre.longi, arbre.lat]).addTo(map)
            .bindPopup(`Arbre: ${arbre.lat}, ${arbre.longi}`);
    });
});