<?php
require_once("head.php");
require_once("database-connection.php");
$databaseConnection;
$query = $databaseConnection->query("SELECT 
    pokemon.*,
    type1.libelleType AS firstType,
    type2.libelleType AS secondType
FROM pokemon LEFT JOIN typepokemon AS type1 ON type1.IdType = pokemon.IdTypePokemon 
LEFT JOIN typepokemon AS type2 ON type2.IdType = pokemon.IdSecondTypePokemon ORDER BY IdPokemon ASC;");
if (!$query) {
    throw new RuntimeException("Cannot execute query. Cause : " . mysqli_error($databaseConnection));
} else {
    $result = $query->fetch_all(MYSQLI_ASSOC);
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pokemon Table</title>
            
    </head>

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
                        <td><?php echo $row['NomPokemon']; ?></td>
                        <td><img src='<?php echo $row["urlPhoto"]; ?>' alt="Pokemon Photo"></td>
                        <td><?php echo $row["firstType"]; ?></td>
                        <td><?php echo $row["secondType"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>

    </html>
<?php
}
require_once("footer.php");
?>