<?php
global $databaseConnection;

/* A COMPLETER : remplacer les paramètres pour se connecter à votre base de données de pokémon */
$databaseConnection = mysqli_connect("localhost", "root", "", "portfolio_form") or die("Erreur de connexion: " . mysqli_error($connexion));
