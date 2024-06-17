'use strict'


ajaxRequest('GET', 'php/request.php', true, function (reponse) {
    var arbres = JSON.parse(reponse);
    console.log(arbres);
});