<?php
include("db_conn/db_conn.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id_employe = intval($_GET['id']);

    // Requête SQL pour récupérer les informations de l'employé
    $sql = "SELECT ID_Employe, Num_Employe FROM SLY_Employes WHERE ID_Employe = :id_employe";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id_employe', $id_employe, PDO::PARAM_INT);
    $stmt->execute();
    $employe = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($employe);
} else {
    echo json_encode([]);
}
?>
