'use strict'

// ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

$('#ajoutArbre').submit((event) => {
    event.preventDefault(); // Empêcher la soumission par défaut du formulaire
    
    // Collecter les données du formulaire
    let formData = {
      espece: $('#espece').val(),
      haut_tot: $('#haut_tot').val(),
      haut_tronc: $('#haut_tronc').val(),
      diam_tronc: $('#diam_tronc').val(),
      lat: $('#lat').val(),
      longi: $('#longi').val(),
      fk_arb_etat: $('#fk_arb_etat').val(),
      fk_stadedev: $('#fk_stadedev').val(),
      fk_port: $('#fk_port').val(),
      fk_pied: $('#fk_pied').val(),
      remarquable : $('#remarquable').val()
    };
  
    console.log('Form Data:', formData);

    // Convert formData to URL-encoded string
    let urlEncodedData = new URLSearchParams(formData).toString();

    // Envoyer les données via AJAX
    ajaxRequest('POST', 'php/request.php/arbres/', (response) => {
        console.log('Arbre ajouté avec succès:', response);
        // Mettre à jour l'affichage des arbres
        ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);
    }, urlEncodedData);
});
  

function afficheArbres(data){
    console.log(data);
}