<?php
require_once("database-connection.php");
require_once("head.php");

function insertUser($firstName, $lastName, $login, $password) //fonction pour créer un utilisateur et le placer dans la table users
{
    global $databaseConnection;

    $queryCheckUser = $databaseConnection->prepare("SELECT idUser FROM Users WHERE login = ?");
    $queryCheckUser->bind_param("s", $login);
    $queryCheckUser->execute();
    $resultCheckUser = $queryCheckUser->get_result();

    if ($resultCheckUser->num_rows > 0) {
        echo "Cet utilisateur existe déjà. Veuillez vous connecter.";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $queryInsertUser = $databaseConnection->prepare("INSERT INTO Users (firstName, lastName, login, passwordHash) VALUES (?, ?, ?, ?)");
    $queryInsertUser->bind_param("ssss", $firstName, $lastName, $login, $hashedPassword);

    if (!$queryInsertUser->execute()) {
        echo "Erreur lors de l'inscription : " . $databaseConnection->error;
        exit;
    }

    echo "<script>alert('Inscription réussie. Vous pouvez maintenant vous connecter.');</script>";
}

function authenticateUser() // fonction pour se connecter avec un utilisateur
{
    global $databaseConnection;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        // Récupérer les informations utilisateur depuis la base de données
        $queryGetUser = $databaseConnection->prepare("SELECT idUser, firstName, lastName, passwordHash FROM Users WHERE login = ?");
        $queryGetUser->bind_param("s", $login);
        $queryGetUser->execute();
        $resultUser = $queryGetUser->get_result()->fetch_assoc();

        if ($resultUser) {
            // Vérifier le mot de passe
            if (password_verify($password, $resultUser['passwordHash'])) {
                // Mot de passe correct, connectez l'utilisateur
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['idUser'] = $resultUser['idUser'];
                $_SESSION['firstName'] = $resultUser['firstName'];
                $_SESSION['lastName'] = $resultUser['lastName'];
                echo "<script>alert('Vous êtes maintenant connecté.');</script>";
                exit;
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Utilisateur non trouvé.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firstName'])) {
    insertUser($_POST['firstName'], $_POST['lastName'], $_POST['login'], $_POST['password']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    authenticateUser();
}

$databaseConnection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
</head>

<body>
    <h2>Inscription</h2>

    <form action="login.php" method="post">
        <label for="firstName">Prénom:</label>
        <input type="text" id="firstName" name="firstName" required><br>

        <label for="lastName">Nom:</label>
        <input type="text" id="lastName" name="lastName" required><br>

        <label for="login">Login:</label>
        <input type="text" id="login" name="login" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="S'inscrire">
    </form>

    <h2>Connexion</h2>

    <form action="login.php" method="post">
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Se connecter">
    </form>
</body>

</html>
<?php
require_once("footer.php");
?>