document.addEventListener('DOMContentLoaded', function() {
    console.log("Script chargé!");
    document.getElementById('ajoutArbre').addEventListener('submit', function(event) {
        event.preventDefault(); // Empêche la soumission normale du formulaire

        const popup = document.getElementById('popup');
        popup.classList.remove('hidden'); // Affiche la popup

        setTimeout(() => {
            popup.classList.add('hidden'); // Cache la popup après 3 secondes
            // Optionnel : Tu peux également soumettre le formulaire ici si nécessaire
            // event.target.submit();
        }, 2000);
    });
});