<aside id="side-menu">
    <ul>
        <a href="list.php">
            <li>
                Liste des pokémons
            </li>
        </a>
        <a href="list-by-type.php">
            <li>
                Pokémons par type
            </li>
        </a>
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
            // Déconnexion de l'utilisateur
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit;
        }
        ?>
        <a href="login.php">
            <li>
                Page utilisateurs
            </li>
        </a>
        <?php
        if (isset($_SESSION['idUser'])) {
            echo '<a href="pokedex-utilisateur.php"><li>Pokedex Utilisateur</li></a>';

            // Affiche le bandeau de bienvenue en bas du menu
            echo "<div class='welcome-banner'>Bienvenue, " . $_SESSION['firstName'] . " " . $_SESSION['lastName'] . " !</div>";

            // Ajoute le bouton de déconnexion
            echo '<form method="post" id="logoutForm">
                      <button type="submit" name="logout" id="logoutButton">Déconnexion</button>
                  </form>';
        }
        ?>
    </ul>
</aside>