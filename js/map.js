'use strict'

ajaxRequest('POST', 'php/request.php/map/', (response) => {
    console.log('Arbre ajouté avec succès:', response);
});