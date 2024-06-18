'use strict';

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

function afficheArbres(data){
    let tableArbre=document.getElementById('arbres');
    data.forEach(el => {
        tableArbre.document.createElement('tr');
        tableArbre.document.createElement('td_id').textContent=el.id;
        tableArbre.document.createElement('td_espece').textContent=el.espece;
        tableArbre.document.createElement('td_htot').textContent=el.haut_tot;
        tableArbre.document.createElement('td_htr').textContent=el.haut_tronc;
        tableArbre.document.createElement('td_dtr').textContent=el.diam_tronc;
        tableArbre.document.createElement('td_etat').textContent=el.fk_arb_etat;
        tableArbre.document.createElement('td_stadedev').textContent=el.fk_stadedev;
        tableArbre.document.createElement('td_port').textContent=el.fk_port;
        tableArbre.document.createElement('td_pied').textContent=el.fk_pied;
        tableArbre.document.createElement('td_rem').textContent=el.remarquable;
        tableArbre.document.createElement('td_lat').textContent=el.lat;
        tableArbre.document.createElement('td_lon').textContent=el.longi;

        tableArbre.appendChild('tr');
        tableArbre.appendChild('td_id');
        tableArbre.appendChild('td_espece');
        tableArbre.appendChild('td_htot');
        tableArbre.appendChild('td_htr');
        tableArbre.appendChild('td_dtr');
        tableArbre.appendChild('td_etat');
        tableArbre.appendChild('td_stadedev');
        tableArbre.appendChild('td_port');
        tableArbre.appendChild('td_pied');
        tableArbre.appendChild('td_rem');
        tableArbre.appendChild('td_lat');
        tableArbre.appendChild('td_lon');
    });
}