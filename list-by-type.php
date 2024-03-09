<?php
require_once("head.php");
require_once("database-connection.php");

// Initialiser la connexion à la base de données (utilisez votre propre configuration)
$databaseConnection;

// Récupérer la liste des types de Pokémon présents dans la base de données
$queryTypes = $databaseConnection->query("SELECT DISTINCT libelleType FROM TypePokemon");

if (!$queryTypes) {
    trigger_error('Erreur lors de la récupération des types: ' . $databaseConnection->error, E_USER_ERROR);
}
?>

<body>
    <div>
    <div class="button-container">
    <?php while ($row = $queryTypes->fetch_assoc()) : ?>
        <?php $type = $row['libelleType']; ?>
        <form method="get" action="list-by-type.php" class="type-button-form">
            <button type="submit" name="type" value="<?php echo $type; ?>"><?php echo $type; ?></button>
        </form>
    <?php endwhile; ?>
</div>
        <?php
        

        // Récupérer le type de Pokémon à afficher
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        if ($type) {
            // Récupérer l'ID du type spécifié depuis la base de données
            $queryTypeId = $databaseConnection->prepare("SELECT idType FROM TypePokemon WHERE libelleType = ?");
            $queryTypeId->bind_param("s", $type);
            $queryTypeId->execute();
            $resultTypeId = $queryTypeId->get_result();

            if (!$resultTypeId) {
                die('Erreur lors de la récupération de l\'ID du type : ' . $databaseConnection->error);
            }

            $typeRow = $resultTypeId->fetch_assoc();

            // Récupérer les Pokémon du type spécifié depuis la base de données en utilisant l'ID du type
            $queryPokemonsParType = $databaseConnection->prepare("
                SELECT p.*, t1.libelleType AS Type1, t2.libelleType AS Type2
                FROM Pokemon p
                LEFT JOIN TypePokemon t1 ON p.IdTypePokemon = t1.idType
                LEFT JOIN TypePokemon t2 ON p.IdSecondTypePokemon = t2.idType
                WHERE p.IdTypePokemon = ? OR p.IdSecondTypePokemon = ?
            ");
            $queryPokemonsParType->bind_param("ii", $typeRow['idType'], $typeRow['idType']);
            $queryPokemonsParType->execute();
            $resultPokemonsParType = $queryPokemonsParType->get_result();

            if ($resultPokemonsParType->num_rows > 0) {

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
                        <?php while ($row = $resultPokemonsParType->fetch_assoc()) : ?>
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