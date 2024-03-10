<?php
require_once("head.php");
require_once("database-connection.php");
?>

<?php
if (isset($_GET["q"])) {
    $query = "%" . $_GET["q"] . "%";
    $stmt = $databaseConnection->prepare("SELECT idPokemon, nomPokemon, urlPhoto FROM Pokemon WHERE nomPokemon LIKE ?");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Résultats de la recherche :</h2>";
        echo "<table class='tabList'>";
        echo "<tr><th>Pokemon correspondant à la recherche : </th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr><td><a href='info-pokemon.php?id=" . $row['idPokemon'] . "' class='results'>" . $row['nomPokemon'] . "</a></td></tr>";
        }

        echo "</table>";
    } else {
        echo "<p>Aucun résultat trouvé.</p>";
    }
}
?>

<?php
require_once("footer.php");
?>