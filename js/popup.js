console.log("Script chargé!");
document.getElementById('monFormulaire').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission normale du formulaire
    
    const popup = document.getElementById('popup');
    popup.classList.remove('hidden'); // Affiche la popup
    
    setTimeout(() => {
        popup.classList.add('hidden'); // Cache la popup après 5 secondes
        alert('Formulaire envoyé !'); // Affiche un message de confirmation (optionnel)
        // Tu peux également soumettre le formulaire ici si nécessaire
        // event.target.submit();
    }, 5000);
});