<?php
require_once("head.php");
require_once("database-connection.php");
$databaseConnection;
$query = $databaseConnection->query("SELECT 
    pokemon.*,
    type1.libelleType AS firstType,
    type2.libelleType AS secondType
FROM pokemon JOIN typepokemon AS type1 ON type1.IdType = pokemon.IdTypePokemon 
LEFT JOIN typepokemon AS type2 ON type2.IdType = pokemon.IdSecondTypePokemon ORDER BY IdPokemon ASC;");
if (!$query) {
    throw new RuntimeException("Cannot execute query. Cause : " . mysqli_error($databaseConnection));
} else {
    $result = $query->fetch_all(MYSQLI_ASSOC);
?>
    <body>
        <table class="tabList">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Photo</th>
                    <th>Type 1</th>
                    <th>Type 2</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($result as $row) : ?> 
                    <tr>
                        <td><?php echo $row["IdPokemon"]; ?></td>
                        <td><a class="linesList" href="info-pokemon.php?id=<?php echo $row["IdPokemon"]; ?>"><?php echo $row['NomPokemon']; ?></a></td>
                        <td><a class="linesList" href="info-pokemon.php?id=<?php echo $row["IdPokemon"]; ?>"><img src='<?php echo $row["urlPhoto"]; ?>' alt="Pokemon Photo"></a></td>
                        <td><?php echo $row["firstType"]; ?></td>
                        <td><?php echo $row["secondType"]; ?></td>
                    </tr>
                <?php endforeach; 
                ?>
                
            </tbody>
        </table>
    </body>

    </html>
<?php
}
require_once("footer.php");
?>