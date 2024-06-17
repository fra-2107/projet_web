'use strict'

ajaxRequest('GET', 'php/request.php/arbres', afficheArbres);

function afficheArbres(data){
    console.log("io");
    let arbres = JSON.parse(data);
    console.log('non');
    let table = document.getElementById('tableArbres');
    for (let arbre of arbres){
        let tr = document.createElement('tr');
        let td = document.createElement('td');
        td.innerHTML = arbre.id;
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = arbre.nom;
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = arbre.age;
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = arbre.espece;
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = arbre.taille;
        tr.appendChild(td);
        td = document.createElement('td');
        td.innerHTML = arbre.date_plantation;
        tr.appendChild(td);
        table.appendChild(tr);
    }
}