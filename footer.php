<!-- 
    Ce fichier permet d'afficher un footer et de fermer les balises body et HTML de la page
-->
<script>
function afficherPokemonParType(type) {
    // Récupérer les données des Pokémon par type depuis la base de données
    fetch('get-pokemon-par-type.php?type=' + encodeURIComponent(type))
        .then(response => response.json())
        .then(data => {
            // Afficher les données sous forme de tableau (similaire à la page existante)
            // Remplacez ceci par votre propre code d'affichage
            console.log(data);
            alert("Afficher les Pokémon de type " + type);
        })
        .catch(error => {
            console.error('Erreur de récupération des données:', error);
        });
}
</script>
</main>
</div>
<footer>
    <p>Tous droits réservés, ESICAD BTS SIO 1ere année, Développement Web 2023-2024</p>
</footer>
</body>

</html>