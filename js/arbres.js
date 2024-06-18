'use strict'

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

$('#ajoutArbre').submit((event) =>{
    event.preventDefault();
    ajaxRequest('POST', 'php/request.php/arbres/', () =>
      {
        ajaxRequest('GET', 'php/request.php/arbres/', afficheArbres);
      });
})

function afficheArbres(data){
    console.log(data);
}