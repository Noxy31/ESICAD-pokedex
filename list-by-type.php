<?php
require_once("head.php");
require_once("database-connection.php");

$databaseConnection;
$queryTypes = $databaseConnection->query("SELECT DISTINCT libelleType FROM TypePokemon");

?>
<body>
    <div>
    <div class="button-container">
    <?php while ($row = $queryTypes->fetch_assoc()) : ?> <!-- boucle while pour créer boutons autant qu'il y a de types -->
        <?php $type = $row['libelleType']; ?>
        <form method="get" action="list-by-type.php" class="type-button-form"> <!-- form avec methode get pour récupérer les types dans l'url et donc changer la page -->
            <button type="submit" name="type" value="<?php echo $type; ?>"><?php echo $type; ?></button>
        </form>
    <?php endwhile; ?>
</div>
        <?php
        
        $type = isset($_GET['type']) ? $_GET['type'] : null; // on récupère le type a afficher

        if ($type) {
            //on initie la requete, encore une fois avec prepare, bind et execute (pour eviter une injection sql sur le pokedex du prof Chen, sinon il sera pas content), 
            // pour recuperer les libelle de type par rapport a leurs id dans la table de types             
            $queryTypeId = $databaseConnection->prepare("SELECT idType FROM TypePokemon WHERE libelleType = ?"); // on recupere l'ID du type spécifié depuis la base de données
            $queryTypeId->bind_param("s", $type); // on indique que les valeurs des parametres attendu sont de types "s", donc string, puisqu'on recupère les libelle
            $queryTypeId->execute();

            $resultTypeId = $queryTypeId->get_result();
            $typeRow = $resultTypeId->fetch_assoc();

            //on initie la requete, encore une fois avec prepare, bind et execute (pour eviter une injection sql sur le pokedex du prof Chen, sinon il sera pas content), 
            // pour recuperer les libelle de type par rapport a leurs id dans la table de types
            $queryPokemonsParType = $databaseConnection->prepare(" 
                SELECT p.*, t1.libelleType AS Type1, t2.libelleType AS Type2
                FROM Pokemon p
                LEFT JOIN TypePokemon t1 ON p.IdTypePokemon = t1.idType
                LEFT JOIN TypePokemon t2 ON p.IdSecondTypePokemon = t2.idType
                WHERE p.IdTypePokemon = ? OR p.IdSecondTypePokemon = ?
            ");
            $queryPokemonsParType->bind_param("ii", $typeRow['idType'], $typeRow['idType']); // cette fois on indique "ii", car les deux valeurs attendus des deux variables sont des entiers (ici les deux types)
            $queryPokemonsParType->execute();
            $resultPokemonsParType = $queryPokemonsParType->get_result();

            if ($resultPokemonsParType->num_rows > 0) { // si les resultats sont non null, on affiche le tableau

        ?>
                <table class="tabList">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Photo</th>
                            <th>Type 1</th>
                            <th>Type 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultPokemonsParType->fetch_assoc()) : ?> <!-- boucle pour implémenter les valeurs dans un tableau -->
                            <tr>
                                <td><?php echo $row["IdPokemon"]; ?></td>
                                <td><a class="linesList" href="info-pokemon.php?id=<?php echo $row["IdPokemon"]; ?>"><?php echo $row['NomPokemon']; ?></a></td>
                                <td><a class="linesList" href="info-pokemon.php?id=<?php echo $row["IdPokemon"]; ?>"><img src='<?php echo $row["urlPhoto"]; ?>' alt="Pokemon Photo"></a></td>
                                <td><?php echo $row["Type1"] ?? $row["IdTypePokemon"]; ?></td>
                                <td><?php echo $row["Type2"] ?? $row["IdSecondTypePokemon"]; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
        <?php
            } else {
                echo "Aucun Pokémon trouvé.";
            }
        }
        ?>
    </div>
</body>

<?php
require_once("footer.php");
?>