<?php

include("connexion_mysql.php");

// Exécution d'une requête de test
try {
    // Exemple de requête de test (remplacez "table_test" par le nom d'une table existante dans votre base de données)
    $query = $db->query("SELECT * FROM SLY_test LIMIT 1");
    // Si la requête s'exécute sans erreur, la connexion fonctionne correctement
    echo "Connexion à la base de données réussie.";
} catch (PDOException $e) {
    // En cas d'erreur, afficher le message d'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}

?>