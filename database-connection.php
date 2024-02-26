<?php
global $databaseConnection;

/* A COMPLETER : remplacer les paramètres pour se connecter à votre base de données de pokémon */
$databaseConnection = mysqli_connect("localhost", "root", "", "pokemontp2") or die("Erreur de connexion: " . mysqli_error($connexion));

$result = mysqli_query($connexion, "Select * contact (name, email, message) VALUES ('$nom', '$email', '$message')");