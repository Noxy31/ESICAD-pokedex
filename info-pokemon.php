<?php
require_once("head.php");
require_once("database-connection.php");

$idPokemon = $_GET["id"];
$query = $databaseConnection->query("SELECT * FROM Pokemon WHERE idPokemon = $idPokemon");
$result = $query->fetch_assoc();

?>
    <body>
        <div class="pokeCard">
            <h1><?php echo $result["NomPokemon"]; ?></h1>
            <div class="imgUnique"><img src='<?php echo $result["urlPhoto"]; ?>' alt="Pokemon Photo"></div>
            <div class="pokeInfo"></div>

        </div>


    </body>
<?php
require_once("footer.php");
?>