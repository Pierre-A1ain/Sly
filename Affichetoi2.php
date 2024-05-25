<?php

include("db_conn/db_conn.php");
include("header.php");
// Requête SQL pour récupérer les employés avec le nom de leur entreprise
    $sql = "SELECT SLY_Employes.*, SLY_Entreprises.Nom_Entreprise 
        FROM SLY_Employes 
        INNER JOIN SLY_Entreprises 
        ON SLY_Employes.ID_Entreprise = SLY_Entreprises.ID_Entreprise";

    $stmt = $db->query($sql);
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer la liste des entreprises depuis la base de données
    $sql = "SELECT * FROM SLY_Entreprises";
    $stmt_etr = $db->query($sql);
    $entreprises = $stmt_etr->fetchAll(PDO::FETCH_ASSOC);

    // Requête SQL pour récupérer les entreprises avec le nombre d'employés
    $sql_entreprises = "SELECT SLY_Entreprises.*, COUNT(SLY_Employes.ID_Employe) AS Nombre_Employes 
    FROM SLY_Entreprises 
    LEFT JOIN SLY_Employes ON SLY_Entreprises.ID_Entreprise = SLY_Employes.ID_Entreprise 
    GROUP BY SLY_Entreprises.ID_Entreprise";

    $stmt_etr = $db->query($sql_entreprises);
    $entreprises = $stmt_etr->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau Dire</title>
    <script>
        function fetchEmployees(companyId) {
            fetch('get_employees.php?id=' + companyId)
                .then(response => response.json())
                .then(data => {
                    let employeeTable = document.getElementById('employeeTable');
                    employeeTable.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(employee => {
                            let row = `<tr>
                                <td>${employee.ID_Employe}</td>
                                <td>${employee.Prenom_Employe}</td>
                                <td>${employee.Nom_Employe}</td>
                                <td>${employee.Num_Employe}</td>
                                <td>${employee.Mail_Employe}</td>
                                <td>${employee.Nom_Entreprise}</td>
                            </tr>`;
                            employeeTable.innerHTML += row;
                        });
                    } else {
                        employeeTable.innerHTML = '<tr><td colspan="6">Aucun employé trouvé.</td></tr>';
                    }
                });
        }
    </script>
</head>
<body>
<main>

    <div>
        <?php
            echo "<table border=1 class='EtrTbl'>";
            echo "<td>&nbsp;<strong> ID <strong>&nbsp;</td>";
            echo "<td>&nbsp;<strong> Entreprise </strong></td>";
            echo "<th>&nbsp;<strong> Nombre d'employés </strong>&nbsp;</th>";
            foreach ($entreprises as $row_etr) {
                echo "<tr>";
                echo '<td>' . "&nbsp;" . $row_etr['ID_Entreprise'] . '</td>';
                echo '<td>' . "&nbsp;" . $row_etr['Nom_Entreprise'] . '</td>';
                echo '<td onclick="fetchEmployees(' . htmlspecialchars($row_etr['ID_Entreprise']) . ')" style="cursor:pointer;">' . "&nbsp;" . htmlspecialchars($row_etr['Nombre_Employes']) . '</td>';
                
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </div>
        <table class='EmplTbl'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Numéro</th>
                    <th>Mail</th>
                    <th>Entreprise</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody id="employeeTable">
                <!-- Les employés sélectionnés seront affichés ici -->
            </tbody>
        </table>
    </div>
   
</main>
</body>
</html>
