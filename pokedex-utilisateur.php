<?php
require_once("head.php");
require_once("database-connection.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['idUser'])) {
    $idUser = $_SESSION['idUser'];
    $queryUserPokemon = $databaseConnection->prepare("SELECT p.idPokemon, p.nomPokemon, p.urlPhoto, up.captureDate FROM user_pokemon up JOIN Pokemon p ON up.idPokemon = p.idPokemon WHERE up.idUser = ?");
    $queryUserPokemon->bind_param("i", $idUser);
    $queryUserPokemon->execute();
    $resultUserPokemon = $queryUserPokemon->get_result();
?>
    <div id="main-wrapper">
        <main id="main">
            <h2>Pokedex Utilisateur</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Image</th>
                        <th>Date de capture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($rowUserPokemon = $resultUserPokemon->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $rowUserPokemon['nomPokemon']; ?></td>
                            <td><img src="<?php echo $rowUserPokemon['urlPhoto']; ?>" alt="Pokemon Photo"></td>
                            <td><?php echo $rowUserPokemon['captureDate']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </div>
    </body>

<?php
} else {
    echo "Vous devez être connecté pour accéder à cette page.";
}
require_once("footer.php");
?>