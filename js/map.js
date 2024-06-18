'use strict'

ajaxRequest('POST', 'php/request.php/map/', (response) => {
    console.log('Arbre ajouté avec succès:', response);

    // Extraire les coordonnées de la réponse
    const latitudes = response.map(arbre => arbre.lat);
    const longitudes = response.map(arbre => arbre.longi);

    // Créer les traces pour Plotly
    const trace = {
        type: 'scattergeo',
        lat: latitudes,
        lon: longitudes,
        mode: 'markers',
        marker: {
            size: 10,
            color: 'rgb(255, 0, 0)',
            opacity: 0.7
        },
        text: 'Arbre'
    };

    const layout = {
        geo: {
            projection: {
                type: 'natural earth'
            },
            showland: true,
            landcolor: 'rgb(217, 217, 217)',
            subunitwidth: 1,
            subunitcolor: 'rgb(255,255,255)'
        },
        margin: { r: 0, t: 0, b: 0, l: 0 }
    };

    // Tracer la carte
    Plotly.newPlot('map', [trace], layout);
});