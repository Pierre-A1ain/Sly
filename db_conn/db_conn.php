<?php

//Sheepshapedcloud MySQL
$host = '34.76.47.16';
$dbname = 'SLY';
$username = 'sheepshelter';
$password = '9%B}<Ysf6;fj:/D`';

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