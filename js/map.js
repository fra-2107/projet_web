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
        L.circle([arbre.longi, arbre.lat], {
            color: 'green',
            fillColor: '#3f0',
            fillOpacity: 0.5,
            radius: 5 // Rayon en mètres
        }).addTo(map)
        .bindPopup(`Arbre: ${arbre.lat}, ${arbre.longi}`);
    });
});


$('#predClusterBtn').click(() => {
    console.log('Prédiction de clusters');
    ajaxRequest('POST', 'php/request.php/predictClust/', (response) => {
        console.log('Prédiction reçue:', response);
    }, document.getElementById('nb_clust').value);
});