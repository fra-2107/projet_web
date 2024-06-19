ajaxRequest('GET', 'php/request.php/preds', (response)=>
{
    console.log('Prédicats ajoutés avec succès:', response);
});