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

// Récupérer les informations sur les pré-évolutions du Pokémon
$queryPreviousEvolutions = $databaseConnection->prepare("SELECT p1.nomPokemon AS prevEvolution FROM evolutionpokemon pe JOIN Pokemon p1 ON pe.idPokemon = p1.idPokemon WHERE pe.idEvolution = ?");
if ($queryPreviousEvolutions === false) {
    trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
}

$queryPreviousEvolutions->bind_param("i", $idPokemon);
$queryPreviousEvolutions->execute();
$resultPreviousEvolutions = $queryPreviousEvolutions->get_result();

// Récupérer les informations sur les évolutions du Pokémon
$queryEvolutions = $databaseConnection->prepare("SELECT p2.nomPokemon AS nextEvolution FROM evolutionpokemon pe JOIN Pokemon p2 ON pe.idEvolution = p2.idPokemon WHERE pe.idPokemon = ?");
if ($queryEvolutions === false) {
    trigger_error('Erreur de préparation de la requête SQL: ' . $databaseConnection->error, E_USER_ERROR);
}

$queryEvolutions->bind_param("i", $idPokemon);
$queryEvolutions->execute();
$resultEvolutions = $queryEvolutions->get_result();
?>


<body>
    <div class="pokeCard">
        <h1><?php echo $resultPokemon["NomPokemon"]; ?></h1>
        
        <div class="pokeInfo">
            <div class="imgUnique"><img src='<?php echo $resultPokemon["urlPhoto"]; ?>' alt="Pokemon Photo"></div>
            
            <div class="infoDetails">
                <div class="characteristiques">
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
                </div>

                <div class="types">
                    <p>Type 1 : <?php echo $resultType1["libelleType"]; ?></p>
                    <?php
                    if ($resultType2 !== null) {
                        echo "<p>Type 2 : " . $resultType2["libelleType"] . "</p>";
                    }
                    ?>
                </div>

                <div class="evolution">
    <?php
    if ($resultPreviousEvolutions->num_rows > 0) {
        echo "<p>Pré-évolution de " . $resultPokemon["NomPokemon"] . " :</p>";
        echo "<ul>";
        while ($rowPreviousEvolution = $resultPreviousEvolutions->fetch_assoc()) {
            echo "<li>" . $rowPreviousEvolution["prevEvolution"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Pas de pré-évolution.</p>";
    }

    if ($resultEvolutions->num_rows > 0) {
        echo "<p>Évolutions de " . $resultPokemon["NomPokemon"] . " :</p>";
        echo "<ul>";
        while ($rowEvolution = $resultEvolutions->fetch_assoc()) {
            echo "<li>" . $rowEvolution["nextEvolution"] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Pas d'évolution.</p>";
    }
    ?>
</div>
            </div>
        </div>
    </div>
</body>


<?php
require_once("footer.php");
?>