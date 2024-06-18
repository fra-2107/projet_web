'use strict'

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

$('#ajouterbtn').onclick(() => {
    ajaxRequest('POST', 'php/request.php/arbres', afficheArbres);
})

function afficheArbres(data){
    console.log(data);
}