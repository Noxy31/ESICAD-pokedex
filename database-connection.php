<?php
global $databaseConnection;
$databaseConnection = mysqli_connect("localhost", "root", "", "pokemontp2") or die("Erreur de connexion: " . mysqli_error($databaseConnection));
if (!$databaseConnection) {
    throw new RuntimeException("Cannot connect to the database. Cause : " . mysqli_connect_error());
   }
?>