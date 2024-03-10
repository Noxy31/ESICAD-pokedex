<?php
require_once("head.php");
require_once("database-connection.php");

$idPokemon = $_GET["id"]; // on récupere l'id présent dans l'url

$queryPokemon = $databaseConnection->prepare("SELECT * FROM Pokemon WHERE idPokemon = ?");
// prepare permet de compiler,verifier la syntaxe et reserver de la place pour la requete, sans directement inclure les valeurs des parametres (on inclue les valeurs avec bind)
$queryPokemon->bind_param("i", $idPokemon); // ici un entier est attendu en valeur de parametre
$queryPokemon->execute(); // on execute le tout
$resultPokemon = $queryPokemon->get_result();

if ($resultPokemon === false || $resultPokemon->num_rows === 0) { // gestion d'erreur si l'id ne correspond a aucun pokemon
    echo "Pokémon introuvable.";
    exit;
}

$resultPokemon = $resultPokemon->fetch_assoc(); // on recupere un tableau sur les valeurs de notre requete

$type1Id = $resultPokemon["IdTypePokemon"]; // on recupère l'id du type 1
$queryType1 = $databaseConnection->prepare("SELECT libelleType FROM TypePokemon WHERE idType = ?");
$queryType1->bind_param("i", $type1Id); // on attend un entier en valeur de type1Id
$queryType1->execute(); // on execute le tout
$resultType1 = $queryType1->get_result()->fetch_assoc();


$type2Id = $resultPokemon["IdSecondTypePokemon"]; // on récupère l'id du deuxième type si y'en a un
$resultType2 = null;
if ($type2Id !== null) {
    $queryType2 = $databaseConnection->prepare("SELECT libelleType FROM TypePokemon WHERE idType = ?");
    $queryType2->bind_param("i", $type2Id);
    $queryType2->execute();
    $resultType2 = $queryType2->get_result()->fetch_assoc();
}
$typeId = $resultPokemon["IdTypePokemon"]; // on récupère l'id du premier type
$queryType = $databaseConnection->prepare("SELECT libelleType FROM TypePokemon WHERE idType = ?");
$queryType->bind_param("i", $typeId);
$queryType->execute();
$resultType = $queryType->get_result()->fetch_assoc();

// récupère les infos sur si le pokemon a des ancetres ou non, grace a "prepare" qui stock les valeurs de la requete
$queryPreviousEvolutions = $databaseConnection->prepare("SELECT p1.nomPokemon AS prevEvolution FROM evolutionpokemon pe JOIN Pokemon p1 ON pe.idPokemon = p1.idPokemon WHERE pe.idEvolution = ?");
$queryPreviousEvolutions->bind_param("i", $idPokemon); // bind permet de lier des valeurs aux parametres de la requete, "i" spécifie l'attente d'un entier (puisqu'ici on select et join avec des id)
$queryPreviousEvolutions->execute(); // execute la requete
$resultPreviousEvolutions = $queryPreviousEvolutions->get_result();

// récupère les infos sur si le pokemon a des evolutions ou non
$queryEvolutions = $databaseConnection->prepare("SELECT p2.nomPokemon AS nextEvolution FROM evolutionpokemon pe JOIN Pokemon p2 ON pe.idEvolution = p2.idPokemon WHERE pe.idPokemon = ?");

$queryEvolutions->bind_param("i", $idPokemon);
$queryEvolutions->execute();
$resultEvolutions = $queryEvolutions->get_result();
?>


<body>
    <div class="pokeCard">
        <h1><?php echo $resultPokemon["NomPokemon"]; ?></h1>

        <div class="pokeInfo">
            <div class="imgUnique"><img src='<?php echo $resultPokemon["urlPhoto"]; ?>' alt="Pokemon Photo"></div>

            <div class="infoDetails"> <!-- on crée le tableau, on affiche les types et les info d'evolutions -->
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
                    if ($resultPreviousEvolutions->num_rows > 0) { // si ancêtre non egale a zero, indique lequel
                        echo "<p>Ancêtre :</p>";
                        echo "<ul>";
                        while ($rowPreviousEvolution = $resultPreviousEvolutions->fetch_assoc()) {
                            echo "<li>" . $rowPreviousEvolution["prevEvolution"] . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Pas d'ancêtre.</p>"; // sinon, affiche ceci
                    }

                    if ($resultEvolutions->num_rows > 0) { // si evolution non egale a zero, indique lequel
                        echo "<p>Evolue en :</p>";
                        echo "<ul>";
                        while ($rowEvolution = $resultEvolutions->fetch_assoc()) {
                            echo "<li>" . $rowEvolution["nextEvolution"] . "</li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "<p>Pas d'évolution.</p>"; // sinon, affiche cela
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
<div class="capture-info"> <!-- on récupère les infos du pokemon affiché pour les ajouter a la table user_pokemon dans la BDD -->
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['idUser'])) { // on affiche le champ d'ajout de date et le bouton pour ajouter le pokemon au pokedex de l'user connecté
        echo '<form id="captureForm" method="post">';
        echo '<label for="captureDate">Date de capture :</label>';
        echo '<input type="date" id="captureDate" name="captureDate" required><br>';
        echo '<button type="submit">Ajouter au Pokedex Utilisateur</button>';
        echo '<input type="hidden" name="idPokemon" value="' . $idPokemon . '">';
        echo '</form>';
    }
    ?>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idPokemon']) && isset($_POST['captureDate'])) {
    $idPokemon = $_POST['idPokemon'];
    $captureDate = $_POST['captureDate'];
    $queryAddToPokedex = $databaseConnection->prepare("INSERT INTO user_pokemon (idUser, idPokemon, captureDate) VALUES (?, ?, ?)");
    $idUser = $_SESSION['idUser'];
    $queryAddToPokedex->bind_param("iis", $idUser, $idPokemon, $captureDate);
    if ($queryAddToPokedex->execute()) {
        echo '<script>alert("Pokemon ajouté au Pokedex Utilisateur !");</script>'; 
    }
}
?>
<?php
require_once("footer.php");
?>