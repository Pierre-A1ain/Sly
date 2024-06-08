<?php
include("db_conn/db_conn.php");

    // Récupérer les noms des entreprises et leurs identifiants
    $query = "SELECT ID_Entreprise, Nom_Entreprise FROM SLY_Entreprises";
    $statement = $db->query($query);
    $entreprises = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si le formulaire a été soumis
    if (!isset($_POST['submit'])) {
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Créer Ticket</title>
    </head>
    <body>
        <h1>Création de Ticket: ajout sujet / id etr / redirection </h1>
        <h3> <a href="json.php">Générer un json</a> </h3>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">

            <div class="wrap-in">
                    <label for="searchInput">Filtre à troupeau</label>
                    <input type="text" id="searchInput" class="beau_gros_champ">

                    <label for="entreprise" class="margin15">Troupeau :</label>
                    <select class="combo-form" name="entreprise" id="entreprise" required onchange="updateEmployees()">
                        <?php foreach ($entreprises as $entreprise): ?>
                            <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo $entreprise['Nom_Entreprise']; ?></option>
                        <?php endforeach; ?>
                    </select><br><br><br>
            </div>

            <div class="wrap-in">
                    <label for="employe">Mouton :</label>
                    <select name="employe" id="employe" onchange="DataEmploye();" class="combo-form">
                        <!-- options ajoutées dynamiquement via JavaScript -->
                    </select><br><br>
                    
                    <!--<label for="ID_Employe">ID :</label>
                    <input type="ID" id="ID_Employe" name="ID_Employe" readonly class="beau_gros_champ">-->

                    <label for="telephone">Numéro de téléphone :</label>
                    <input type="tel" id="telephone" name="telephone" readonly class="beau_gros_champ"><br><br>

                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" readonly class="beau_gros_champ" size="30px"><br><br>
                </div>

            <label for="demande">Sujet :</label>
            <textarea id="demande" name="demande" rows="8" cols="70" required></textarea>
            <br><br>

            <input type="submit" name="submit" value="Créér">
        </form>
        <script src="scripts/FORM_MAJ_Employe.js"></script>
        <script>
            function DataEmploye() {
                        var select = document.getElementById("employe");
                        var input_telephone = document.getElementById("telephone");
                            input_telephone.value = select.options[select.selectedIndex].dataset.telephone;
                        var input_email = document.getElementById("email");
                            input_email.value = select.options[select.selectedIndex].dataset.email;
                    }
        </script>
    </body>
</html>

<?php
    } else {
        // Traitement du formulaire soumis
        try {

            // Requête d'insertion dans la base de données
            $sql = "INSERT INTO SLY_Ticket ( Sujet_Ticket,
                                             ID_Entreprise,
                                             ID_employe
                                            ) 
                                    VALUES ( :demande, 
                                            :ID_Entreprise,
                                            :ID_Employe
                                            )
                    ";
            $stmt = $db->prepare($sql); 

        // Liaison des paramètres
        $Sujet_Ticket = filter_input(INPUT_POST, 'demande', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $stmt->bindValue(':demande', $Sujet_Ticket, PDO::PARAM_STR);

        $ID_Entreprise = filter_input(INPUT_POST, 'entreprise', FILTER_SANITIZE_NUMBER_INT);
            $stmt->bindValue(':ID_Entreprise', $ID_Entreprise, PDO::PARAM_INT);

        $ID_Employe = filter_input(INPUT_POST, 'employe', FILTER_SANITIZE_NUMBER_INT);
            $stmt->bindValue(':ID_Employe', $ID_Employe, PDO::PARAM_INT);

        $db = null;

        $success = $stmt->execute();
        if ($success) {
            echo "<p id='successMessage'>Le ticket a bien été créé.</p>";
            echo "<script>
            setTimeout(function() {
                window.location.href = 'putaindeticket.php';
            }, 1500);
        </script>";

             // Redirection après création
             //header("Location: putaindeticket.php"); 
             exit();
        } else {
            echo "Une erreur s'est produite lors de la création du ticket.";
        }

        } catch (PDOException $e) {
            // En cas d'erreur, affichez un message d'erreur.
            echo "Erreur lors de la création du ticket : " . $e->getMessage();
        } finally {
            // Fermeture de la connexion à la base de données
            $db = null;
        }
    }
?>