<?php
include("db_conn/db_conn.php");

// Requête pour récupérer les tickets avec les informations des entreprises
$query = "
    SELECT 
        t.ID_Ticket, 
        t.ID_Entreprise, 
        t.ID_Employe, 
        t.Sujet_Ticket, 
        e.Nom_Entreprise 
    FROM 
        SLY_Ticket t
    JOIN 
        SLY_Entreprises e ON t.ID_Entreprise = e.ID_Entreprise
";
$statement = $db->query($query);
$tickets = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Tickets</title>
</head>
<body>
    <h1>Liste des Tickets</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID Entreprise</th>
                <th>Nom Entreprise</th>
                <th>ID Employe</th>
                <th>ID Ticket</th>
                <th>Sujet Ticket</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo $ticket['ID_Entreprise']; ?></td>
                    <td><?php echo $ticket['Nom_Entreprise']; ?></td>
                    <td><?php echo $ticket['ID_Employe']; ?></td>
                    <td><?php echo $ticket['ID_Ticket']; ?></td>
                    <td><?php echo $ticket['Sujet_Ticket']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Fermeture de la connexion à la base de données
$db = null;
?>
