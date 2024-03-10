/*
TypePokemon(IdTypePokemon, libelleType)

Pokemon(Id_Pokemon, nomPokemon, urlPhoto, PV, Attaque, Defense,
        Vitesse, Special, #IdTypePokemon, #IdSecondTypePokemon)

EvolutionPokemon(#idPokemon, #idEvolution)

User(idUser, firstName, lastName, login, passwordHash)

User_Pokemon(#idUser, #idPokemon, captureDate) 
*/

CREATE TABLE users (
    idUser INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    login VARCHAR(50) UNIQUE,
    passwordHash VARCHAR(255)
);

CREATE TABLE user_pokemon (
    idUser INT,
    idPokemon INT,
    captureDate DATE,
    PRIMARY KEY (idUser, idPokemon),
    FOREIGN KEY (idUser) REFERENCES users(idUser),
    FOREIGN KEY (idPokemon) REFERENCES Pokemon(idPokemon)
);

ALTER TABLE user_pokemon DROP PRIMARY KEY;
ALTER TABLE user_pokemon ADD COLUMN id INT AUTO_INCREMENT PRIMARY KEY FIRST;