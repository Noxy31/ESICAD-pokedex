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
            session_start(); // session start si c'est pas deja le cas
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit; // prend en charge la deconexion avec session_destroy
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
            echo "<div class='welcome-banner'>Bienvenue, " . $_SESSION['firstName'] . " " . $_SESSION['lastName'] . " !</div>"; // bandeau de bienvenue
            echo '<form method="post" id="logoutForm">
                      <button type="submit" name="logout" id="logoutButton">Déconnexion</button>
                  </form>'; // bouton de deconexxion
        }
        ?>
    </ul>
</aside>