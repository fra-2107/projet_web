'use strict'

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

$('#ajoutArbre').submit((event) =>{
    console.log('ajouter');
    console.log(event);
    // ajaxRequest('POST', 'php/request.php/arbres', afficheArbres);
})

function afficheArbres(data){
    console.log(data);
}