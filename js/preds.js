var url = new URL(window.location.href);

// Récupération de la valeur de l'ID à partir des paramètres de l'URL
var id = url.searchParams.get("id");

// Vérification de la valeur récupérée
if (id !== null) {
    console.log("Valeur de l'ID : " + id);
} else {
    console.error("Paramètre 'id' non trouvé dans l'URL.");
}

ajaxRequest('GET', 'php/request.php/preds?id='+id, (response) => {
    console.log('Prédicats ajoutés avec succès:', response);
});