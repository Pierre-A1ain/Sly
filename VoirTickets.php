<?php
try {
    include("db_conn/db_conn.php");

    // Obtenir les colonnes de la table
    $stmt = $db->query("SHOW COLUMNS FROM SLY_Ticket");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($columns)) {
        echo "<table border='1'><tr>";

        // Afficher les en-têtes de colonnes
        foreach ($columns as $column) {
            echo "<th>" . $column . "</th>";
        }
        echo "</tr>";

        // Obtenir les données de la table
        $stmt = $db->query("SELECT * FROM SLY_Ticket");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            // Afficher les données des colonnes
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $column) {
                    echo "<td>" . $column . "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='" . count($columns) . "'>Aucune donnée trouvée</td></tr>";
        }
        echo "</table>";
    } else {
        echo "La table n'a pas de colonnes";
    }
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}

$db = null;
?>