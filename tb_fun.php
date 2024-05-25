<?php
    include("header.php");
    include("db_conn/db_conn.php");

    // Récupérer la liste des entreprises depuis la base de données
    $sql = "SELECT * FROM SLY_Entreprises";
    $stmt_etr = $db->query($sql);
    $entreprises = $stmt_etr->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Tb fun</title>
</head>
<body>
    <div>
        <?php
            echo "<table border=1 class='EtrTbl'>";
            echo "<td>&nbsp;<strong> ID <strong>&nbsp;</td>";
            echo "<td>&nbsp;<strong> Entreprise </strong></td>";
            echo "<td>&nbsp;<strong> Mdf </strong>&nbsp;</td>";
            echo "<td>&nbsp;<strong> Del </strong>&nbsp;</td>";
            foreach ($entreprises as $row_etr) {
                echo "<tr>";
                echo '<td>' . "&nbsp;" . $row_etr['ID_Entreprise'] . '</td>';
                echo '<td>' . "&nbsp;" . $row_etr['Nom_Entreprise'] . '</td>';
                echo '<td>' . "&nbsp;" . $row_etr['Nom_Entreprise'] . '</td>';
                echo '<td>' . "&nbsp;" . $row_etr['Nom_Entreprise'] . '</td>';
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </div>
    <div>
        <table class='TestTbl'>
            <tr>
                <th class='TestTbl'>ID</th>
                <th class='TestTbl'>Nom de l'enclos</th>
                <th class='TestTbl'>Action</th>
            </tr>
            <?php foreach ($entreprises as $entreprise): ?>
            <tr>
                <td class='TestTbl'><?php echo htmlspecialchars($entreprise['ID_Entreprise'], ENT_QUOTES); ?></td>
                <td class='TestTbl'><?php echo htmlspecialchars($entreprise['Nom_Entreprise'], ENT_QUOTES); ?></td>
                <td class='TestTbl'>
                    <!-- Formulaire pour modifier le nom de l'entreprise -->
                    <form method="post" action="" class="formTestTbl">
                        <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($entreprise['ID_Entreprise'], ENT_QUOTES); ?>">
                        <input type="hidden" name="new_nom" value="<?php echo htmlspecialchars($entreprise['Nom_Entreprise'], ENT_QUOTES); ?>" required>
                        <input type="submit" value="Modifier">
                    </form>
                    <!-- Formulaire pour supprimer l'entreprise -->
                    <form method="post" action="" class="formTestTbl">
                        <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($entreprise['ID_Entreprise'], ENT_QUOTES); ?>">
                        <input type="submit" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enclos ?');">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
<?php
// Fermer la connexion à la base de données
$db = null;
?>