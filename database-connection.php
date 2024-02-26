<?php
global $databaseConnection;

/* A COMPLETER : remplacer les paramètres pour se connecter à votre base de données de pokémon */
$databaseConnection = mysqli_connect("localhost", "root", "", "pokemontp2") or die("Erreur de connexion: " . mysqli_error($databaseConnection));

if (!$databaseConnection) {
    // en cas d'erreur, la fonction mysqli_connect_error() indique la cause de l'échec de la connexion
    throw new RuntimeException("Cannot connect to the database. Cause : " . mysqli_connect_error());
   }
?>