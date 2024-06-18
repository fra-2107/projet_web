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
  

function afficheArbres(data){
    console.log(data);
}

// Récupérer l'élément select
let selectElement = document.getElementById('fk_stadedev');

// Fonction pour récupérer les options depuis l'API
async function fetchOptionsFromDB() {
    try {
        let response = await fetch('php/request.php/stadedev'); // Remplacez par l'URL de votre API PHP
        let data = await response.json();

        // Ajouter les options récupérées au select
        data.forEach(optionData => {
            let option = document.createElement('option');
            option.value = optionData.id;
            option.textContent = optionData.nom_option;
            selectElement.appendChild(option);
        });
    } catch (error) {
        console.error('Erreur lors de la récupération des options :', error);
    }
}

// Appeler la fonction pour récupérer et ajouter les options au chargement de la page
fetchOptionsFromDB();