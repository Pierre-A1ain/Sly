<?php
// Connexion à la base de données
include("db_conn/db_conn.php");

// Requête SQL pour récupérer les données de la table sly_entreprise
$sql = "SELECT * FROM SLY_Entreprises";
    $stmt = $db->query($sql);
    $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Requête SQL pour récupérer les données de la table sly_employes
$sql = "SELECT * FROM SLY_Employes";
$stmt = $db->query($sql);
$employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Affichage des résultats
        
        echo "<table border=1>";
        echo "<td> ID </td>";
        echo "<td> Entreprise </td>";
        foreach ($entreprises as $row) {
            echo "<tr>";
            echo '<td>' . $row['ID_Entreprise'] . '</td>';
            echo '<td>' . $row['Nom_Entreprise'] . '</td>';
            echo "</tr>";
        }
        echo "</table>";

        echo "<table border=1>";
        echo "<td> ID </td>";
        echo "<td> Prenom </td>";
        echo "<td> Nom </td>";
        echo "<td> Entreprise </td>";
        foreach ($employes as $row) {
            echo "<tr>";
            echo '<td>' . $row['ID_Employe'] . '</td>';
            echo '<td>' . $row['Prenom_Employe'] . '</td>';
            echo '<td>' . $row['Nom_Employe'] . '</td>';
            echo '<td>' . $row['ID_Entreprise'] . '</td>';
            echo "</tr>";
        }
        echo "</table>";

// Fermer la connexion à la base de données
$db = null;
?>
