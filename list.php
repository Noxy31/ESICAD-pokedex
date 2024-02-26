<!-- 
    Ce fichier représente la page de liste de tous les pokémons.
-->
<
<?php
require_once("head.php");
require_once("database-connection.php");
$databaseConnection;
$query = $databaseConnection->query("SELECT * from `pokemon`");

if (!$query) {
    throw new RuntimeException("Cannot execute query. Cause : " . mysqli_error($databaseConnection));
} else {
    $result = $query->fetch_all(MYSQLI_ASSOC);
    foreach ($result as $row) {
        echo "<tr><td>" . $row["IdPokemon"] . "</td><td>" . $row['NomPokemon'] . "</td><td><img src='" . $row["urlPhoto"] . "'></td><td>" . $row["IdTypePokemon"] . "</td></tr>";
    }
}

?>
<pre>
    </pre>
<?php
require_once("footer.php");
?>