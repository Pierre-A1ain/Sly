<?php
include("db_conn/db_conn.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id_entreprise = intval($_GET['id']);

    // Requête SQL pour récupérer les employés de l'entreprise donnée
    $sql = "SELECT SLY_Employes.*, SLY_Entreprises.Nom_Entreprise 
            FROM SLY_Employes 
            INNER JOIN SLY_Entreprises 
            ON SLY_Employes.ID_Entreprise = SLY_Entreprises.ID_Entreprise
            WHERE SLY_Employes.ID_Entreprise = :id_entreprise";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
    $stmt->execute();
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($employes);
} else {
    echo json_encode([]);
}
?>
