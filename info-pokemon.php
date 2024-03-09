<?php
require_once("head.php");
require_once("database-connection.php");

$idPokemon = $_GET["id"];

// Récupérer les informations du Pokémon
$queryPokemon = $databaseConnection->prepare("SELECT * FROM Pokemon WHERE idPokemon = ?");
if ($queryPokemon === false) {
    trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
}

$queryPokemon->bind_param("i", $idPokemon);
$queryPokemon->execute();
$resultPokemon = $queryPokemon->get_result();

if ($resultPokemon === false || $resultPokemon->num_rows === 0) {
    echo "Pokémon introuvable.";
    exit;
}

$resultPokemon = $resultPokemon->fetch_assoc();


$type1Id = $resultPokemon["IdTypePokemon"]; // On recupère l'id du type 1
$queryType1 = $databaseConnection->prepare("SELECT libelleType FROM TypePokemon WHERE idType = ?");
if ($queryType1 === false) {
    trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
}

$queryType1->bind_param("i", $type1Id);
$queryType1->execute();
$resultType1 = $queryType1->get_result()->fetch_assoc();


$type2Id = $resultPokemon["IdSecondTypePokemon"]; // On récupère l'id du deuxième type si y'en a un
$resultType2 = null;
if ($type2Id !== null) {
    $queryType2 = $databaseConnection->prepare("SELECT libelleType FROM TypePokemon WHERE idType = ?");
    if ($queryType2 === false) {
        trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
    }

    $queryType2->bind_param("i", $type2Id);
    $queryType2->execute();
    $resultType2 = $queryType2->get_result()->fetch_assoc();
}


$typeId = $resultPokemon["IdTypePokemon"];
$queryType = $databaseConnection->prepare("SELECT libelleType FROM TypePokemon WHERE idType = ?");
if ($queryType === false) {
    trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
}

$queryType->bind_param("i", $typeId);
$queryType->execute();
$resultType = $queryType->get_result()->fetch_assoc();

$queryPreviousEvolution = $databaseConnection->prepare("SELECT p1.nomPokemon AS prevEvolution FROM evolutionpokemon pe JOIN Pokemon p1 ON pe.idPokemon = p1.idPokemon WHERE pe.idEvolution = ?");
if ($queryPreviousEvolution === false) {
    trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
}

$queryPreviousEvolution->bind_param("i", $idPokemon);
$queryPreviousEvolution->execute();
$resultPreviousEvolution = $queryPreviousEvolution->get_result()->fetch_assoc();

// Récupérer les informations sur l'évolution du Pokémon
$queryNextEvolution = $databaseConnection->prepare("SELECT p2.nomPokemon AS nextEvolution FROM evolutionpokemon pe JOIN Pokemon p2 ON pe.idEvolution = p2.idPokemon WHERE pe.idPokemon = ?");
if ($queryNextEvolution === false) {
    trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
}

$queryNextEvolution->bind_param("i", $idPokemon);
$queryNextEvolution->execute();
$resultNextEvolution = $queryNextEvolution->get_result()->fetch_assoc();
?>

<body>
    <div class="pokeCard">
        <h1><?php echo $resultPokemon["NomPokemon"]; ?></h1>
        <div class="imgUnique"><img src='<?php echo $resultPokemon["urlPhoto"]; ?>' alt="Pokemon Photo"></div>
        <div class="pokeInfo">
            <h2>Caractéristiques :</h2>
            <table>
                <tr>
                    <td>Points de vie (PV)</td>
                    <td><?php echo $resultPokemon["PV"]; ?></td>
                </tr>
                <tr>
                    <td>Attaque</td>
                    <td><?php echo $resultPokemon["Attaque"]; ?></td>
                </tr>
                <tr>
                    <td>Défense</td>
                    <td><?php echo $resultPokemon["Defense"]; ?></td>
                </tr>
                <tr>
                    <td>Vitesse</td>
                    <td><?php echo $resultPokemon["Vitesse"]; ?></td>
                </tr>
                <tr>
                    <td>Spécial</td>
                    <td><?php echo $resultPokemon["Special"]; ?></td>
                </tr>
            </table>

            <?php
            echo "<p>Type 1 : " . $resultType1["libelleType"] . "</p>";

            if ($resultType2 !== null) {
                echo "<p>Type 2 : " . $resultType2["libelleType"] . "</p>";
            }
            ?>

            <?php
            if ($resultPreviousEvolution != null) {
                echo "<p>Pré-évolution : " . $resultPreviousEvolution["prevEvolution"] . "</p>";
            } else {
                echo "<p>Pas de pré-évolution.</p>";
            }

            if ($resultNextEvolution != null) {
                echo "<p>Évolution : " . $resultNextEvolution["nextEvolution"] . "</p>";
            } else {
                echo "<p>Pas d'évolution connue.</p>";
            }
            ?>
        </div>
    </div>
</body>

<?php
require_once("footer.php");
?>