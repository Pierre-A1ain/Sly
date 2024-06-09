<?php
include("db_conn/db_conn.php");

header('Content-Type: application/json');

if (isset($_GET['id_employe'])) {
    $id_employe = intval($_GET['id_employe']);

    // Requête SQL pour récupérer mail / tel de l'employé
    $sql = "SELECT Num_Employe, Mail_Employe
            FROM SLY_Employes
            WHERE ID_Employe = :id_employe";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_employe', $id_employe, PDO::PARAM_INT);
    $stmt->execute();
    $employe_data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($employe_data);
} else {
    echo json_encode([]);
}
?>
