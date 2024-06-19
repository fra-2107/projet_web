'use strict'

// ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

$('#ajoutArbre').submit((event) => {
    event.preventDefault(); // Empêcher la soumission par défaut du formulaire
    
    // Collecter les données du formulaire
    let formData = {
        espece: $('#espece').val(),
        haut_tot: $('#haut_tot').val(),
        haut_tronc: $('#haut_tronc').val(),
        diam_tronc: $('#diam_tronc').val(),
        lat: $('#lat').val(),
        longi: $('#longi').val(),
        fk_arb_etat: $('#fk_arb_etat').val(),
        fk_stadedev: $('#fk_stadedev').val(),
        fk_port: $('#fk_port').val(),
        fk_pied: $('#fk_pied').val(),
        remarquable : $('#remarquable').val()
    };

    console.log('Form Data:', formData);

    // Convert formData to URL-encoded string
    let urlEncodedData = new URLSearchParams(formData).toString();

    // Envoyer les données via AJAX
    ajaxRequest('POST', 'php/request.php/arbres/', (response) => {
        console.log('Arbre ajouté avec succès:', response);
        // Mettre à jour l'affichage des arbres
        ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);
    }, urlEncodedData);
});
  
function setRemarkableValue(event) {
    // Prevent the default form submission
    event.preventDefault();

    // Get the checkbox and hidden input elements
    const checkbox = document.getElementById('remarquable');
    const hiddenInput = document.getElementById('remarquableHidden');

    // Set the value of the hidden input based on the checkbox state
    hiddenInput.value = checkbox.checked ? 'Oui' : 'Non';

    // For demonstration purposes, logging the form data to the console
    const formData = new FormData(document.getElementById('treeForm'));
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
}

function afficheArbres(data){
    console.log(data);
}

// Récupérer l'élément select

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

// Appeler la fonction pour récupérer et ajouter les options au chargement de la page
fetchOptionsFromDB('fk_arb_etat');
fetchOptionsFromDB('fk_stadedev');
fetchOptionsFromDB('fk_pied');
fetchOptionsFromDB('fk_port');