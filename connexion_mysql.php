<?php

// Informations de connexion à MySQL
$host = '127.0.0.1';
$dbname = 'sly';
$username = 'root';
$password = '';

// Connexion à MySQL via PDO
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configuration supplémentaire si nécessaire
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Autres configurations possibles...
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
}

?>