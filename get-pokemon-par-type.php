<?php
header('Content-Type: application/json');

require_once("database-connection.php");

$type = $_GET['type'];

// Récupérer les données des Pokémon par type depuis la base de données
$queryPokemonParType = $databaseConnection->prepare("SELECT idPokemon, urlPhoto, nomPokemon FROM Pokemon WHERE IdTypePokemon = ?");
if ($queryPokemonParType === false) {
    die('Erreur de préparation de la requête SQL: ' . $databaseConnection->error);
}

$queryPokemonParType->bind_param("s", $type);
$queryPokemonParType->execute();
$resultPokemonParType = $queryPokemonParType->get_result();

if ($resultPokemonParType === false) {
    die('Erreur lors de la récupération des Pokémon par type: ' . $databaseConnection->error);
}

$pokemonParType = $resultPokemonParType->fetch_all(MYSQLI_ASSOC);

echo json_encode($pokemonParType);

$databaseConnection->close();
?>