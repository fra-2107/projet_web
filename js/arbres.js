'use strict'

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

function afficheArbres(data){
    console.log(data);
}