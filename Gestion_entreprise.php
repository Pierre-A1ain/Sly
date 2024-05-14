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
                            ID_Employe INT(11) AUTO_INCREMENT PRIMARY KEY,
                            Prenom_Employe VARCHAR(255) NOT NULL,
                            Nom_Employe VARCHAR(255),
                            Num_Employe VARCHAR(50),
                            Mail_Employe VARCHAR(255),
                            FOREIGN KEY (ID_Entreprise) REFERENCES SLY_Entreprises(ID_Entreprise) ON DELETE CASCADE
                          )";
        $db->exec($sqlCreateTable);
        echo "Table employé créée avec succès.<br>";
    } catch (PDOException $e) {
        echo "Erreur lors de la création de la table employé: " . $e->getMessage() . "<br>";
    }
}

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
            echo "Cheptel ajouté avec succès";
        } else {
            echo "Une erreur s'est produite lors de l'ajout du Cheptel.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout du Cheptel : " . $e->getMessage();
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
            echo "Cheptel supprimé avec succès";
        } else {
            echo "Une erreur s'est produite lors de la suppression du Cheptel.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression du Cheptel : " . $e->getMessage();
    }
}

// Récupérer la liste des entreprises depuis la base de données
$sql = "SELECT * FROM SLY_Entreprises";
$stmt = $db->query($sql);
$entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des employes depuis la base de données
// $sql = "SELECT * FROM SLY_Employes";
// $stmt = $db->query($sql);
// $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'ajout d'1 employé
    // Requête d'insertion dans la base de données
    // Liaison des paramètres

// Traitement de la modification des data d'1 employé
    // Récupérer l'identifiant de l'employé à modifier et nouvelles data
    // Requête de mise à jour de la DB

// Traitement de la suppression d'1 employé
    // Récupérer l'identifiant de l'employé à supprimer
    // Requête de suppression dans la base de données

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter, supprimer et modifier des Cheptels</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <h1>Gérer ses cheptels, ses moutons, ...</h1>
    
    <!-- Formulaire d'ajout d'entreprise -->
    <div class="MainDataManagementWrap">
        <div class="EtrWrap">
            <h2 class="corner">Cheptel Corner</h2>
            <div class="margin15">
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                    <label for="Nom_Entreprise"></label>
                    <input type="text" name="Nom_Entreprise" placeholder="Ajouter un cheptel" size="45" required>
                    <input type="submit" value="Ajouter">
                </form>
            </div>

            <!-- Formulaire de modification de nom d'entreprise -->
            <div class="margin15">
                <h3>Modifier un cheptel</h3>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                    <!--<label for="update_id">Entreprise à modifier :</label>-->
                    <select name="update_id" id="update_id" onchange="updateNewNom()" class="degage">
                        <?php foreach ($entreprises as $entreprise): ?>
                            <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo $entreprise['Nom_Entreprise']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!--<label for="new_nom">Nouveau nom :</label>-->
                    <br class="degage">
                    <input type="text" name="new_nom" id="new_nom" placeholder="Nouveau nom" size="45" required>
                    <input type="submit" value="Modifier">
                </form>
            </div>

            <!-- Formulaire de suppression d'entreprise -->
            <div class="margin15">
                <h3>Supprimer un cheptel</h3>
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
        <div class="EmplWrap">
            <h2 class="corner">Sheep Corner</h2>
        </div>
    </div>
    <script>
        function updateNewNom() {
            var select = document.getElementById("update_id");
            var inputNom = document.getElementById("new_nom");
            inputNom.value = select.options[select.selectedIndex].text;
        }
    </script>
</body>
</html>
<?php
// Fermer la connexion à la base de données
$db = null;
?>
