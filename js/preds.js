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
    console.log("Valeur de age_estim :", ageEstim);

    // Afficher le résultat
    document.getElementById('valueage').innerHTML = ageEstim;
});

ajaxRequest('GET', 'php/request.php/preds?risque&id=' + id, (response) => {
    console.log('response' + response);
    
    if (response == 'false') {
        document.getElementById('valuerisque').innerHTML = "Pas de risque estimé pour cet arbre";
        return;
    }
    elseif(response == 'true') 
    {
        document.getElementById('valuerisque').innerHTML = "attention cet arbre peux tomber";
    }

});

