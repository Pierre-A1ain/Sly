<?php
    include("db_conn/db_conn.php");
    include("header.php");

    // Vérifier si la table SLY_Entreprises existe, sinon la créer
        $tableExists = $db->query("SHOW TABLES LIKE 'SLY_Entreprises'")->rowCount() > 0;
        if (!$tableExists) {
            try {
                $sqlCreateTable = "CREATE TABLE SLY_Entreprises (
                                    ID_Entreprise INT(11) AUTO_INCREMENT PRIMARY KEY,
                                    Nom_Entreprise VARCHAR(255) NOT NULL
                                )";
                $db->exec($sqlCreateTable);
                echo "Table Entreprise créée avec succès.<br>";
            } catch (PDOException $e) {
                echo "Erreur lors de la création de la table Entreprise: " . $e->getMessage() . "<br>";
            }
        }

    // Vérifier si la table SLY_Employes existe, sinon la créer
    $tableExists = $db->query("SHOW TABLES LIKE 'SLY_Employes'")->rowCount() > 0;
    if (!$tableExists) {
        try {
            $sqlCreateTable = "CREATE TABLE SLY_Employes (
                                ID_Employe INT AUTO_INCREMENT PRIMARY KEY,
                                Prenom_Employe VARCHAR(255) NOT NULL,
                                Nom_Employe VARCHAR(255),
                                Num_Employe VARCHAR(50),
                                Mail_Employe VARCHAR(255),
                                ID_Entreprise  INT,
                                FOREIGN KEY (ID_Entreprise) REFERENCES SLY_Entreprises (ID_Entreprise)
                            )";
            $db->exec($sqlCreateTable);
            echo "Table employé créée avec succès.<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de la création de la table employé: " . $e->getMessage() . "<br>";
        }
    }

    // Récupérer la liste des entreprises depuis la base de données
    $sql = "SELECT * FROM SLY_Entreprises";
    $stmt_etr = $db->query($sql);
    $entreprises = $stmt_etr->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer la liste des employes depuis la base de données
    $sql = "SELECT e.ID_Employe, e.Prenom_Employe, e.Nom_Employe, e.Num_Employe, e.Mail_Employe, ent.Nom_Entreprise 
    FROM SLY_Employes e
    JOIN SLY_Entreprises ent ON e.ID_Entreprise = ent.ID_Entreprise";
    $stmt_empl = $db->query($sql);
    $employes = $stmt_empl->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'ajout d'entreprise
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Nom_Entreprise"])) {
    try {
        // Requête d'insertion dans la base de données
        $sql = "INSERT INTO SLY_Entreprises (Nom_Entreprise) 
                VALUES (:Nom_Entreprise)";
        $stmt = $db->prepare($sql);

        // Liaison des paramètres
        $Nom_Entreprise = filter_input(INPUT_POST, 'Nom_Entreprise', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $stmt->bindValue(':Nom_Entreprise', $Nom_Entreprise, PDO::PARAM_STR);

        $success = $stmt->execute();
        if ($success) {
            echo "Enclos ajouté avec succès";
        } else {
            echo "Une erreur s'est produite lors de l'ajout de l'enclos.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de l'enclos : " . $e->getMessage();
    }
}

// Traitement de la modification de nom d'entreprise
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_id"]) && isset($_POST["new_nom"])) {
    try {
        // Récupérer l'identifiant de l'entreprise à modifier et le nouveau nom
        $entreprise_id = $_POST["update_id"];
        $new_nom = $_POST["new_nom"];

        // Requête de mise à jour dans la base de données
        $sql = "UPDATE SLY_Entreprises SET Nom_Entreprise = :new_nom WHERE ID_Entreprise = :entreprise_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':entreprise_id', $entreprise_id, PDO::PARAM_INT);
        $stmt->bindValue(':new_nom', $new_nom, PDO::PARAM_STR);
        $success = $stmt->execute();

        if ($success) {
            echo "Cheptel mis à jour avec succès";
        } else {
            echo "Une erreur s'est produite lors de la mise à jour du nom du Cheptel.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour du nom du Cheptel : " . $e->getMessage();
    }
}

// Traitement de la suppression d'entreprise
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    try {
        // Récupérer l'identifiant de l'entreprise à supprimer
        $entreprise_id = $_POST["delete_id"];

        // Requête de suppression dans la base de données
        $sql = "DELETE FROM SLY_Entreprises WHERE ID_Entreprise = :entreprise_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':entreprise_id', $entreprise_id, PDO::PARAM_INT);
        $success = $stmt->execute();

        if ($success) {
            echo "Enclos supprimé avec succès";
        } else {
            echo "Une erreur s'est produite lors de la suppression de l'enclos.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de l'enclos : " . $e->getMessage();
    }
}

// Traitement de l'ajout d'un employé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Prenom_Employe"])) {
    try {
        // Requête d'insertion dans la base de données
        $sql = "INSERT INTO SLY_Employes (Prenom_Employe, Nom_Employe, Num_Employe, Mail_Employe, ID_Entreprise) 
                VALUES (:Prenom_Employe, :Nom_Employe, :Num_Employe, :Mail_Employe, :ID_Entreprise)";
        $stmt_empl = $db->prepare($sql); 

        // Liaison des paramètres
        $Prenom_Employe = filter_input(INPUT_POST, 'Prenom_Employe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $stmt_empl->bindValue(':Prenom_Employe', $Prenom_Employe, PDO::PARAM_STR);

        $Nom_Employe = filter_input(INPUT_POST, 'Nom_Employe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $stmt_empl->bindValue(':Nom_Employe', $Nom_Employe, PDO::PARAM_STR);

        $Num_Employe = filter_input(INPUT_POST, 'Num_Employe', FILTER_SANITIZE_NUMBER_INT);
        $stmt_empl->bindValue(':Num_Employe', $Num_Employe, PDO::PARAM_INT);

        $Mail_Employe = filter_input(INPUT_POST, 'Mail_Employe', FILTER_SANITIZE_EMAIL);
        $stmt_empl->bindValue(':Mail_Employe', $Mail_Employe, PDO::PARAM_STR);

        $ID_Entreprise = filter_input(INPUT_POST, 'ID_Entreprise', FILTER_SANITIZE_NUMBER_INT);
        $stmt_empl->bindValue(':ID_Entreprise', $ID_Entreprise, PDO::PARAM_INT);

        $success = $stmt_empl->execute();
        if ($success) {
            echo "Ce mouton a bien été ajouté";
        } else {
            echo "Une erreur s'est produite lors de l'ajout de ce mouton.";
        };

    } 
    catch (PDOException $e) {
        // En cas d'erreur, affichez un message d'erreur.
        echo "Erreur lors de l'ajout de ce mouton : " . $e->getMessage();
    }
}

// Traitement de la modification des data d'1 employé

// Traitement de la suppression d'1 employé
    // Récupérer l'identifiant de l'employé à supprimer
    // Requête de suppression dans la base de données

?>
<!-- ------------------------------------------------------------------------------------------------------------------------------------------ -->
<!--                                                                Section HTML                                                                -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------ -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter, supprimer et modifier</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<main>
    <h1>Gérer ses enclos, ses moutons, ...</h1>
    
    <!-- Formulaire d'ajout d'entreprise -->
    <div class="MainDataManagementWrap">
        <div class="EtrWrap">
            <h2 class="corner">Enclos Corner</h2>
            <div class="margin15">
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                    <label for="Nom_Entreprise"></label>
                    <input type="text" name="Nom_Entreprise" placeholder="Ajouter un enclos" size="45" required>
                    <input type="submit" value="Ajouter">
                </form>
            </div>

            <!-- Formulaire de modification de nom d'entreprise -->
            <div class="margin15">
                <h3>Modifier un enclos</h3>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                    <!--<label for="update_id">Entreprise à modifier :</label>-->
                    <select name="update_id" id="update_id" onchange="updateNomModifierEtr()" class="degage">
                        <?php foreach ($entreprises as $entreprise): ?>
                            <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo $entreprise['Nom_Entreprise']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!--<label for="new_nom">Nouveau nom :</label>-->
                    <br class="degage">
                    <input type="text" name="new_nom" id="new_nom" placeholder=" Nouveau nom" size="45" required>
                    <input type="submit" value="Modifier">
                </form>
            </div>

            <!-- Formulaire de suppression d'entreprise -->
            <div class="margin15">
                <h3>Supprimer un enclos</h3>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                    <!--<label for="delete_id">Cheptel à supprimer :</label>-->
                    <select name="delete_id" id="delete_id">
                        <?php foreach ($entreprises as $entreprise): ?>
                            <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo $entreprise['Nom_Entreprise']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" value="Supprimer">
                </form>
            </div>
        </div>    

    <!-- ********************************************  Formulaires employé  ************************************************* -->

        <div class="EmplWrap">
            <h2 class="corner">Sheep Corner</h2>
            <!-- ********************************************  Formulaires employé : ajouter ************************************************* -->
            <h3> Ajouter un nouveau mouton </h3>
            <div id="message" style="display: none;">Ce mouton a bien été ajouté</div>

            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" class="degage">

                <label for="Prenom_Employe"></label>
                <input type="text" name="Prenom_Employe" placeholder=" Prénom" class="beau_gros_champ"required>

                <label for="Nom_Employe"></label>
                <input type="text" name="Nom_Employe" placeholder=" Nom" class="beau_gros_champ">
                
                <label for="Num_Employe"></label>
                <input type="text" name="Num_Employe" placeholder=" Numéro" class="beau_gros_champ">

                <label for="Mail_Employe"></label>
                <input type="email" name="Mail_Employe" placeholder=" Mail" class="beau_gros_champ">

                <label for="searchInput"></label>
                <br><input type="text" id="searchInput" placeholder=" Filtre enclos" class="beau_gros_champ">

                <label for="ID_Entreprise">Enclos</label>
                    <select name="ID_Entreprise" id="ID_Entreprise" class="beau_gros_champ" required>
                        <?php foreach ($entreprises as $entreprise): ?>
                            <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo "&nbsp;&nbsp;" . $entreprise['Nom_Entreprise']; ?></option>
                        <?php endforeach; ?>
                    </select>
                <input type="submit" name="submit" value="Ajouter" class="beau_gros_champ">
            </form>
            <!-- ********************************************  Formulaires employé : modifier / supprimer ************************************************* -->
             <h3 class="degage10"> Modifier / Supprimer un mouton </h3>
            <div id="message" style="display: none;">Ce mouton a bien été modifié</div>
        </div>


    </div>
    <!-- ********************************************  Tableaux  ************************************************* -->
    <div class="DataCheckingWrap">
        <div>
            <?php
                echo "<table border=1 class='EtrTbl'>";
                echo "<td>&nbsp;<strong> ID <strong>&nbsp;</td>";
                echo "<td>&nbsp;<strong> Entreprise </strong></td>";
                foreach ($entreprises as $row_etr) {
                    echo "<tr>";
                    echo '<td>' . "&nbsp;" . $row_etr['ID_Entreprise'] . '</td>';
                    echo '<td>' . "&nbsp;" . $row_etr['Nom_Entreprise'] . '</td>';
                    echo "</tr>";
                }
                echo "</table>";
            ?>
        </div>
        <div>
            <?php
                echo "<table border=1 class='EmplTbl' >";
                echo "<th>&nbsp;<strong>ID</strong>&nbsp;</th>";
                echo "<td>&nbsp;<strong> Prénom </strong></td>";
                echo "<td>&nbsp;<strong> Nom </strong></td>";
                echo "<td>&nbsp;<strong> Numéro </strong></td>";
                echo "<td>&nbsp;<strong> Mail </strong></td>";
                echo "<td>&nbsp;<strong> Entreprise </strong></td>";
                foreach ($employes as $row_epl) {
                    echo "<tr>";
                    echo '<td>' . "&nbsp;" . htmlspecialchars($row_epl['ID_Employe']) . '</td>';
                    echo '<td>' . "&nbsp;" . htmlspecialchars($row_epl['Prenom_Employe'], ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</td>';
                    echo '<td>' . "&nbsp;" . htmlspecialchars($row_epl['Nom_Employe'], ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</td>';
                    echo '<td>' . "&nbsp;" . htmlspecialchars($row_epl['Num_Employe']) . '</td>';
                    echo '<td>' . "&nbsp;" . htmlspecialchars($row_epl['Mail_Employe']) . '</td>';
                    echo '<td>' . "&nbsp;" . htmlspecialchars($row_epl['Nom_Entreprise'], ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</td>';
                    echo "</tr>";
                }
                echo "</table>";
            ?>
        </div>
    </div>
    <script>
        function updateNomModifierEtr() {
            var select = document.getElementById("update_id");
            var inputNom = document.getElementById("new_nom");
            inputNom.value = select.options[select.selectedIndex].text;
        }
    </script>
</main>    
</body>
</html>
<?php
// Fermer la connexion à la base de données
$db = null;
?>
