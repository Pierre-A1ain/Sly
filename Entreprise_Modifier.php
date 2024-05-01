<?php
include("connexion_mysql.php");
include("header.php");

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
            echo "Entreprise ajoutée avec succès";
        } else {
            echo "Une erreur s'est produite lors de l'ajout de l'entreprise.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'ajout de l'entreprise : " . $e->getMessage();
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
            echo "Entreprise supprimée avec succès";
        } else {
            echo "Une erreur s'est produite lors de la suppression de l'entreprise.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de l'entreprise : " . $e->getMessage();
    }
}

// Récupérer la liste des entreprises depuis la base de données
$sql = "SELECT * FROM SLY_Entreprises";
$stmt = $db->query($sql);
$entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter et supprimer des entreprises</title>
    <style>
        label, input { display: block; }
        input { padding: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Ajouter et supprimer des entreprises</h1>
    
    <!-- Formulaire d'ajout d'entreprise -->
    <h2>Ajouter une nouvelle entreprise</h2>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="Nom_Entreprise">Nom de l'entreprise</label>
        <input type="text" name="Nom_Entreprise" required>
        <input type="submit" value="Ajouter">
    </form>

    <!-- Formulaire de suppression d'entreprise -->
    <h2>Supprimer une entreprise</h2>
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="entreprise_id">Choisissez une entreprise à supprimer :</label>
        <select name="delete_id" id="entreprise_id">
            <?php foreach ($entreprises as $entreprise): ?>
                <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo $entreprise['Nom_Entreprise']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Supprimer">
    </form>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$db = null;
?>
