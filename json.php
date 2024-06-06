<?php
include("db_conn/db_conn.php");

$id_entreprise = filter_input(INPUT_GET, 'id_entreprise', FILTER_SANITIZE_NUMBER_INT);

$query = "SELECT ID_Employe, Prenom_Employe, Nom_Employe, Num_Employe, Mail_Employe FROM SLY_Employes WHERE ID_Entreprise = :id_entreprise";
$stmt = $db->prepare($query);
$stmt->bindValue(':id_entreprise', $id_entreprise, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($employees);
?>