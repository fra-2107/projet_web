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
        remarquable: $('#remarquable').is(':checked') ? 'Oui' : 'Non'
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

    // Vider le formulaire
    $('#ajoutArbre')[0].reset();
});



function afficheArbres(data) {}

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


$(document).ready(function(){
    // Liste d'espèces d'arbres (exemple statique, remplacez par vos données réelles)
    var speciesList = [
        "Chêne",
        "Érable",
        "Hêtre",
        "Pin",
        "Sapin",
        "Figuier",
        "Acajou",
        "Cèdre",
        "Châtaignier",
        "Cyprès",
        "Peuplier",
        "Tilleul",
        "Orme"
    ];

    // Fonction pour gérer l'autocomplétion
    $("#espece").on("input", function() {
        var input = $(this).val().toLowerCase();
        var autocompleteItems = $(".autocomplete-items");
        autocompleteItems.empty(); // Vider les anciennes suggestions

        // Filtrer et afficher les suggestions correspondantes
        speciesList.forEach(function(species) {
            if (species.toLowerCase().startsWith(input)) {
                var item = $("<div class='autocomplete-item'>" + species + "</div>");
                item.on("click", function() {
                    $("#espece").val(species); // Remplacer la valeur du champ avec l'espèce sélectionnée
                    autocompleteItems.empty(); // Vider la liste des suggestions après sélection
                });
                autocompleteItems.append(item);
            }
        });

        // Afficher la liste de suggestions si des suggestions existent
        if (autocompleteItems.children().length > 0) {
            autocompleteItems.show();
        } else {
            autocompleteItems.hide();
        }
    });

    // Cacher la liste de suggestions au clic en dehors du champ
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#espece, .autocomplete-items").length) {
            $(".autocomplete-items").hide();
        }
    });
});