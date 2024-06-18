'use strict';

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

function afficheArbres(data) {
    let tableArbre = document.getElementById('arbres').getElementsByTagName('tbody')[0];
    tableArbre.innerHTML = ''; // Clear existing table rows

    data.forEach(el => {
        let tr = document.createElement('tr');
        
        let td_id = document.createElement('td');
        td_id.textContent = el.id;
        tr.appendChild(td_id);

        let td_espece = document.createElement('td');
        td_espece.textContent = el.espece;
        tr.appendChild(td_espece);

        let td_htot = document.createElement('td');
        td_htot.textContent = el.haut_tot;
        tr.appendChild(td_htot);

        let td_htr = document.createElement('td');
        td_htr.textContent = el.haut_tronc;
        tr.appendChild(td_htr);

        let td_dtr = document.createElement('td');
        td_dtr.textContent = el.diam_tronc;
        tr.appendChild(td_dtr);

        let td_etat = document.createElement('td');
        td_etat.textContent = el.fk_arb_etat;
        tr.appendChild(td_etat);

        let td_stadedev = document.createElement('td');
        td_stadedev.textContent = el.fk_stadedev;
        tr.appendChild(td_stadedev);

        let td_port = document.createElement('td');
        td_port.textContent = el.fk_port;
        tr.appendChild(td_port);

        let td_pied = document.createElement('td');
        td_pied.textContent = el.fk_pied;
        tr.appendChild(td_pied);

        let td_rem = document.createElement('td');
        td_rem.textContent = el.remarquable;
        tr.appendChild(td_rem);

        let td_lat = document.createElement('td');
        td_lat.textContent = el.lat;
        tr.appendChild(td_lat);

        let td_lon = document.createElement('td');
        td_lon.textContent = el.longi;
        tr.appendChild(td_lon);

        tableArbre.appendChild(tr);
    });
}