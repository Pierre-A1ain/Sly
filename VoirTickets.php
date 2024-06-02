<?php
include("db_conn/db_conn.php");
include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir Tickets</title>
</head>
<body>
    <?php

    // Vérifier si la table SLY_Tickets existe, sinon la créer
    $tableExists = $db->query("SHOW TABLES LIKE 'SLY_Ticket'")->rowCount() > 0;
    if (!$tableExists) {
        try {
            $sqlCreateTable = "CREATE TABLE SLY_Ticket (
                                ID_Ticket             INT AUTO_INCREMENT PRIMARY KEY,
                                Sujet_Ticket          VARCHAR(255),
                                Resolution_Ticket     VARCHAR(255),
                                DateCreationTicket    NUMERIC,
                                SemaineCreationTicket INT,
                                Nom_Entreprise        VARCHAR(255),
                                Prenom_Employe        VARCHAR(255),
                                Nom_Employe           VARCHAR(255),
                                Num_Employe           VARCHAR(255),
                                Mail_Employe          VARCHAR(255),
                                ID_Entreprise         INT,
                                Statut_Ticket         NUMERIC,
                                DateClotureTicket     NUMERIC,
                                Nom_Image             VARCHAR(255)
                            )";
                            
            $db->exec($sqlCreateTable);
            echo "Table ticket créée avec succès.<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de la création de la table ticket: " . $e->getMessage() . "<br>";
        }
    }

// Requête SQL pour sélectionner les tickets
$sql = "SELECT * FROM SLY_Ticket";
$stmt = $db->query($sql);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Fantasy V1</title>
</head>
<style>
        /* Styles CSS pour le tableau */
        table {
            border-collapse: collapse; /* Fusionner les bordures de cellules */
            width: 100%; /* Largeur du tableau */
            border: 1px solid #000; /* Bordure de 1 pixel */
        }

        th, td {
            border: 1px solid #000; /* Bordure de 1 pixel pour les cellules */
            padding: 8px; /* Espacement à l'intérieur des cellules */
            text-align: left; /* Alignement du texte à gauche */
        }

          /* Style pour les lignes de ticket clos */
          .ticket-clos {
            background-color: #f2f2f2; /* Couleur de fond grise */
            color: #999; /* Couleur de texte grise */
        }
</style>
    <body>
        <div></div>
            <h1 class="underheader">Liste des Tickets</h1>
            <!--<h3><a href="VoirTickets2.php">Le turfu</a></h3>-->
            <br>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom Employé</th>
                    <th>Numéro Employé</th>
                    <th>Email Employé</th>
                    <th>ID Etr</th>
                    <th>Entreprise</th>
                    <th>Sujet</th>
                    <th>Date de Création</th>
                    <th>Date de Clôture</th>
                    <th>Temps de résolution</th>
                    <th>Semaine</th>
                </tr>
                <?php foreach ($tickets as $ticket): ?>
                <tr <?php if ($ticket['Statut_Ticket'] == 2) echo 'class="ticket-clos"'; ?>> <!-- Ajoute classe ticket-clos si ticket clos -->
                    <td><?php echo $ticket['ID_Ticket']; ?></td>
                    <td><?php echo $ticket['Nom_Employe']; ?></td>
                    <td><?php echo $ticket['Num_Employe']; ?></td>
                    <td><?php echo $ticket['Mail_Employe']; ?></td>
                    <td><?php echo $ticket['ID_Entreprise']; ?></td>
                    <td><?php echo $ticket['Nom_Entreprise']; ?></td>
                    <td><?php echo $ticket['Sujet_Ticket']; ?></td>
                    <td><?php echo $ticket['DateCreationTicket']; ?></td>
                    <td><?php echo $ticket['DateClotureTicket']; ?></td>
                    <td>
                        <?php
                            // Calcul du temps de résolution si le ticket est clos
                            if ($ticket['Statut_Ticket'] == 2 && !empty($ticket['DateClotureTicket'])) {
                                $date_creation = new DateTime($ticket['DateCreationTicket']);
                                $date_cloture = new DateTime($ticket['DateClotureTicket']);
                                $temps_resolution = $date_creation->diff($date_cloture)->format('%a jours %h heures %i minutes');
                                echo $temps_resolution;
                            } else {
                                echo "Non résolu";
                            }
                        ?>
                    </td>
                    <td><?php echo $ticket['SemaineCreationTicket']; ?></td>
                    <td>
                        <?php if ($ticket['Statut_Ticket'] == 1): ?> <!-- Affichage du bouton de clôture uniquement si le ticket est ouvert -->
                        <form action="clore_ticket.php" method="post"> <!-- Formulaire pour clore le ticket -->
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['ID_Ticket']; ?>"> <!-- Champ caché pour envoyer l'ID du ticket -->
                            <button type="submit">Clore</button> <!-- Bouton pour clore le ticket -->
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        
    </body>
</html>

<?php
// Fermer la connexion à la base de données
$db = null;
?>
    
</body>
</html>
