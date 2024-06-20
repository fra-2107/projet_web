document.getElementById('ajoutArbre').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Afficher la popup
    const popup = document.getElementById('popup');
    popup.classList.remove('hidden');
    
    // Masquer la popup après 5 secondes
    setTimeout(() => {
        popup.classList.add('hidden');
        // Soumettre le formulaire ici si nécessaire
        // event.target.submit();
    }, 5000);
});