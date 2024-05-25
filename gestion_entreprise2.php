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
            echo "Enclos mis à jour avec succès";
        } else {
            echo "Une erreur s'est produite lors de la mise à jour du nom de l'enclos.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour du nom de l'enclos : " . $e->getMessage();
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

// Récupérer la liste des entreprises depuis la base de données
$sql = "SELECT * FROM SLY_Entreprises";
$stmt_etr = $db->query($sql);
$entreprises = $stmt_etr->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des employes depuis la base de données
$sql = "SELECT *
    FROM SLY_Employes e
    JOIN SLY_Entreprises ent ON e.ID_Entreprise = ent.ID_Entreprise";
$stmt_empl = $db->query($sql);
$employes = $stmt_empl->fetchAll(PDO::FETCH_ASSOC);

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
        }
    } catch (PDOException $e) {
        // En cas d'erreur, affichez un message d'erreur.
        echo "Erreur lors de l'ajout de ce mouton : " . $e->getMessage();
    }
}

// Traitement de la modification des données d'un employé
if (isset($_GET['ID_Employe'])) {
    // Récupérer l'ID de l'employé à partir de l'URL
    $id_employe = $_GET['ID_Employe'];

    // Sélectionner les données de l'employé à modifier
    $stmt = $db->prepare("SELECT * FROM SLY_Employes WHERE ID_Employe = ?");
    $stmt->execute([$id_employe]);
    $employe = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $prenom = htmlspecialchars($_POST['Prenom_Employe'], ENT_QUOTES);
        $nom = htmlspecialchars($_POST['Nom_Employe'], ENT_QUOTES);
        $num = htmlspecialchars($_POST['Num_Employe'], ENT_QUOTES);
        $mail = htmlspecialchars($_POST['Mail_Employe'], ENT_QUOTES);
        $id_entreprise = htmlspecialchars($_POST['ID_Entreprise'], ENT_QUOTES);

        // Mise à jour des données de l'employé
        $stmt = $db->prepare("UPDATE SLY_Employes SET 
                                Prenom_Employe = :prenom, 
                                Nom_Employe = :nom, 
                                Num_Employe = :num, 
                                Mail_Employe = :mail, 
                                ID_Entreprise = :id_entreprise 
                                WHERE ID_Employe = :id");
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':num', $num);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':id_entreprise', $id_entreprise);
        $stmt->bindParam(':id', $id_employe);

        if ($stmt->execute()) {
            echo "Les informations de l'employé ont été mises à jour avec succès.";
        } else {
            echo "Une erreur s'est produite lors de la mise à jour des informations de l'employé.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des ressources vivantes</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <!-- Formulaire pour ajouter une entreprise -->
    <h2>Ajouter un enclos</h2>
    <form method="post" action="">
        <label for="Nom_Entreprise">Nom de l'enclos :</label>
        <input type="text" name="Nom_Entreprise" id="Nom_Entreprise" required>
        <input type="submit" value="Ajouter">
    </form>

    <!-- Liste des entreprises existantes -->
    <h2>Enclos existants</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom de l'enclos</th>
            <th>Action</th>
        </tr>
        <?php foreach ($entreprises as $entreprise): ?>
        <tr>
            <td><?php echo htmlspecialchars($entreprise['ID_Entreprise'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($entreprise['Nom_Entreprise'], ENT_QUOTES); ?></td>
            <td>
                <!-- Formulaire pour modifier le nom de l'entreprise -->
                <form method="post" action="">
                    <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($entreprise['ID_Entreprise'], ENT_QUOTES); ?>">
                    <input type="text" name="new_nom" value="<?php echo htmlspecialchars($entreprise['Nom_Entreprise'], ENT_QUOTES); ?>" required>
                    <input type="submit" value="Modifier">
                </form>
                <!-- Formulaire pour supprimer l'entreprise -->
                <form method="post" action="">
                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($entreprise['ID_Entreprise'], ENT_QUOTES); ?>">
                    <input type="submit" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet enclos ?');">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Formulaire pour ajouter un employé -->
    <h2>Ajouter un mouton</h2>
    <form method="post" action="">
        <label for="Prenom_Employe">Numéro d'identification :</label>
        <input type="text" name="Prenom_Employe" id="Prenom_Employe" required>
        <label for="Nom_Employe">Nom :</label>
        <input type="text" name="Nom_Employe" id="Nom_Employe">
        <label for="Num_Employe">Age :</label>
        <input type="text" name="Num_Employe" id="Num_Employe">
        <label for="Mail_Employe">Etat de santé :</label>
        <input type="text" name="Mail_Employe" id="Mail_Employe">
        <label for="ID_Entreprise">ID de l'enclos :</label>
        <input type="text" name="ID_Entreprise" id="ID_Entreprise" required>
        <input type="submit" value="Ajouter">
    </form>

    <!-- Liste des employes existants -->
    <h2>Les moutons existants</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Numéro d'identification</th>
            <th>Nom</th>
            <th>Age</th>
            <th>Etat de santé</th>
            <th>Enclos</th>
            <th>Action</th>
        </tr>
        <?php foreach ($employes as $employe): ?>
        <tr>
            <td><?php echo htmlspecialchars($employe['ID_Employe'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($employe['Prenom_Employe'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($employe['Nom_Employe'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($employe['Num_Employe'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($employe['Mail_Employe'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($employe['Nom_Entreprise'], ENT_QUOTES); ?></td>
            <td>
                <!-- Lien pour modifier les informations de l'employé -->
                <a href="?ID_Employe=<?php echo htmlspecialchars($employe['ID_Employe'], ENT_QUOTES); ?>">Modifier</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>