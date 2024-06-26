'use strict'

// Fonction pour ajouter les arbres à la base de donnée 
$('#ajoutArbre').submit((event) => {
    event.preventDefault(); // Empêcher la soumission par défaut du formulaire
    console.log("espece" + $('#espece').val())
    // Collecter les données du formulaire
    let formData = {
        espece: $('#autocomplete-input').val(),
        haut_tot: $('#haut_tot').val(),
        haut_tronc: $('#haut_tronc').val(),
        diam_tronc: $('#diam_tronc').val(),
        lat: $('#lat').val(),
        longi: $('#longi').val(),
        fk_arb_etat: $('#fk_arb_etat').val(),
        fk_stadedev: $('#fk_stadedev').val(),
        fk_port: $('#fk_port').val(),
        fk_pied: $('#fk_pied').val(),
        remarquable: $('#remarquable').is(':checked') ? 'Oui' : 'Non'
    };

    console.log('Form Data:', formData);

    // Convert formData to URL-encoded string
    let urlEncodedData = new URLSearchParams(formData).toString();

    // Envoyer les données via AJAX
    ajaxRequest('POST', 'php/request.php/arbres/', (response) => {
        console.log('Arbre ajouté avec succès:', response);
    }, urlEncodedData);

    // Vider le formulaire
    $('#ajoutArbre')[0].reset();
});


// Fonction pour récupérer les options des selects depuis l'API
async function fetchOptionsFromDB(selectName) {
    try {
        // Récupérer l'élément select
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


// fonction autocompletion pour la selection de l'espece
$(document).ready(function () {
    // Liste d'espèces d'arbres (exemple statique, remplacez par vos données réelles)
    var speciesList = [];
    // Récupérer la liste des espèces d'arbres via une requête AJAX
    ajaxRequest('GET', 'php/request.php/especes', function (response) {
        speciesList = response.map(function (item) {
            return item.espece; // Extraire seulement le nom de l'espèce
        });
        console.log('Liste des espèces:', speciesList); // Afficher la liste dans la console pour vérification

        // Initialiser les éléments DOM après récupération des données
        var input = $("#autocomplete-input");
        var suggestionsContainer = $("#autocomplete-suggestions");

        // Fonction pour mettre à jour les suggestions
        function updateSuggestions(inputText) {
            var filteredSpecies = speciesList.filter(function (species) {
                return typeof species === 'string' && species.toLowerCase().startsWith(inputText.toLowerCase());
            });

            var suggestionsHtml = "";
            filteredSpecies.forEach(function (species) {
                suggestionsHtml += "<li>" + inputText + "<span style='color: grey;'>" + species.substring(inputText.length) + "</span></li>";
            });

            suggestionsContainer.html("<ul>" + suggestionsHtml + "</ul>");

            // Afficher les suggestions si elles existent, sinon cacher
            if (filteredSpecies.length > 0) {
                suggestionsContainer.show();
            } else {
                suggestionsContainer.hide();
            }
        }

        // Événement input sur le champ de saisie
        input.on("input", function () {
            var inputText = $(this).val();
            updateSuggestions(inputText);
        });

        // Sélectionner une suggestion au clic
        suggestionsContainer.on("click", "li", function () {
            var selectedSpecies = $(this).text().trim(); // Récupérer le texte complet
            input.val(selectedSpecies.toUpperCase()); // Remplacer la valeur du champ avec l'espèce sélectionnée
            suggestionsContainer.hide(); // Cacher les suggestions après sélection
        });

        // Cacher les suggestions au clic en dehors du champ de saisie et des suggestions
        $(document).on("click", function (e) {
            if (!$(e.target).closest("#autocomplete-container").length) {
                suggestionsContainer.hide();
            }
        });
    });

});