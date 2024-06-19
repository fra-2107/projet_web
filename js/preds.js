var url = new URL(window.location.href);

// Récupération de la valeur de l'ID à partir des paramètres de l'URL
let id = url.searchParams.get("id");

ajaxRequest('GET', 'php/request.php/preds?id='+id, (response) => {
    console.log('Prédicats ajoutés avec succès:', response);
});