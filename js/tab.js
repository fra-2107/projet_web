'use strict';

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

function afficheArbres(data) {
    let tableArbre = document.getElementById('arbres').getElementsByTagName('tbody')[0];
    tableArbre.innerHTML = ''; // Clear existing table rows

    data.forEach(el => {
        let tr = document.createElement('tr');

        let td_id = document.createElement('td');
        td_id.textContent = el.id;
        tr.appendChild(td_id);

        let td_espece = document.createElement('td');
        td_espece.textContent = el.espece;
        tr.appendChild(td_espece);

        let td_htot = document.createElement('td');
        td_htot.textContent = el.haut_tot;
        tr.appendChild(td_htot);

        let td_htr = document.createElement('td');
        td_htr.textContent = el.haut_tronc;
        tr.appendChild(td_htr);

        let td_dtr = document.createElement('td');
        td_dtr.textContent = el.diam_tronc;
        tr.appendChild(td_dtr);

        let td_etat = document.createElement('td');
        td_etat.textContent = el.fk_arb_etat;
        tr.appendChild(td_etat);

        let td_stadedev = document.createElement('td');
        td_stadedev.textContent = el.fk_stadedev;
        tr.appendChild(td_stadedev);

        let td_port = document.createElement('td');
        td_port.textContent = el.fk_port;
        tr.appendChild(td_port);

        let td_pied = document.createElement('td');
        td_pied.textContent = el.fk_pied;
        tr.appendChild(td_pied);

        let td_rem = document.createElement('td');
        td_rem.textContent = el.remarquable;
        tr.appendChild(td_rem);

        let td_lat = document.createElement('td');
        td_lat.textContent = el.lat;
        tr.appendChild(td_lat);

        let td_lon = document.createElement('td');
        td_lon.textContent = el.longi;
        tr.appendChild(td_lon);

        let td_btn = document.createElement('td');
        let predictionButton = document.createElement('button');
        predictionButton.textContent = 'Prediction';
        predictionButton.addEventListener('click', () => {
            // Call the function for prediction
            console.log('prediction : ' + el.id);
            window.open('prediction.html?id=' + el.id);
        });
        td_btn.appendChild(predictionButton);
        tr.appendChild(td_btn);

        let td_del = document.createElement('td');
        let delButton = document.createElement('button');
        delButton.textContent = 'Prediction';
        delButton.addEventListener('click', () => {
            // Call the function for prediction
            console.log('delete element : ' + el.id);
        });
        td_del.appendChild(delButton);
        tr.appendChild(td_del);

        tableArbre.appendChild(tr);
    });
}

function affichePagination(total, limit, page) {
    let paginationDiv = document.getElementById('pagination');
    if (!paginationDiv) {
        console.error('Pagination element not found');
        return;
    }
    paginationDiv.innerHTML = ''; // Clear existing pagination buttons

    let totalPages = Math.ceil(total / limit);

    let fisrtButton = document.createElement('button');
    if (page > 1) {
        fisrtButton.textContent = 'Première Page';
        fisrtButton.disabled = page === 1;
        fisrtButton.addEventListener('click', () => {
            fetchArbres(page = 1);
        });
    }

    let prevButton = document.createElement('button');
    if (page > 1) {
        prevButton.textContent = 'Page précédente';
        prevButton.disabled = page === 1;
        prevButton.addEventListener('click', () => {
            fetchArbres(page - 1);
        });
    }

    let nextButton = document.createElement('button');
    if (page < totalPages) {
        nextButton.textContent = 'Page suivante';
        nextButton.disabled = page === totalPages;
        nextButton.addEventListener('click', () => {
            fetchArbres(page + 1);
        });
    }

    let LastButton = document.createElement('button');
    if (page < totalPages) {
        LastButton.textContent = 'Dernière Page';
        LastButton.disabled = page === totalPages;
        LastButton.addEventListener('click', () => {
            fetchArbres(page = totalPages);
        });
    }

    let currentPageSpan = document.createElement('span');
    currentPageSpan.id = 'current-page';
    currentPageSpan.textContent = page;

    paginationDiv.appendChild(fisrtButton);
    paginationDiv.appendChild(prevButton);
    paginationDiv.appendChild(currentPageSpan);
    paginationDiv.appendChild(nextButton);
    paginationDiv.appendChild(LastButton);
}

// Fonction pour récupérer les arbres avec pagination et filtres
function fetchArbres(page = 1, filterEtat = '') {
    let limit = 20; // Nombre d'éléments par page
    let url = `php/request.php/arbres?limit=${limit}&page=${page}`;
    
    if (filterEtat !== '') {
        url += `&etat=${encodeURIComponent(filterEtat)}`;
    }

    console.log('URL de requête:', url); // Vérifier l'URL de la requête

    ajaxRequest('GET', url, (response) => {
        console.log('Réponse de la requête:', response); // Vérifier la réponse du serveur

        if (response.error) {
            console.error('Erreur du serveur:', response.error);
        } else {
            afficheArbres(response.data);
            affichePagination(response.total, limit, page);
        }
    });
}

// Déclencher la récupération des arbres au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // Initialisation des éléments de filtre
    // Récupérer et afficher les arbres avec la pagination au chargement initial
    fetchArbres();
});

let selectEtat = document.getElementById('fk_arb_etat');


selectEtat.addEventListener('change', () => {
    fetchArbres(1, selectEtat.value);
    console.log('Etat : ' + selectEtat.value);
});

// Fonction pour récupérer les options depuis l'API
async function fetchOptionsFromDB(selectName) {
    try {
        let urlapi = 'php/request.php/' + selectName;
        let selectElement = document.getElementById(selectName);

        ajaxRequest('GET', urlapi, (data) => {
            // Ajouter les options récupérées au select
            data.forEach(optionData => {
                let option = document.createElement('option');
                option.value = optionData[selectName]; // Assurez-vous que l'index ici correspond aux données récupérées
                option.textContent = optionData[selectName];
                selectElement.appendChild(option);
            });
        });
    } catch (error) {
        console.error('Erreur lors de la récupération des options :', error);
    }
}


fetchOptionsFromDB('fk_arb_etat');