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
        <style>
        #image-frame {
            width: 300px;
            height: 300px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        #image-frame img {
            max-width: 100%;
            max-height: 100%;
            display: none;
        }
        #image-frame .placeholder {
            position: absolute;
            text-align: center;
        }
    </style>

    </head>
    <body>
        <h1>Création de Ticket</h1>
         <!-- Date / Heure / Semaine -->
         <div class="TimeBlock">
                <label>Semaine</label>
                <span id="semaine"></span>

                <label></label>
                <span id="date"></span>

                <label></label>
                <span id="heure"></span><br><br>

        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">

            <div class="wrap-in">
                    <label for="searchInput">Filtre à troupeau</label>
                    <input type="text" id="searchInput" class="beau_gros_champ">

                    <label for="entreprise" class="margin15">Troupeau :</label>
                    <select class="combo-form" name="entreprise" id="entreprise" required onchange="updateEmploye();">
                        <?php foreach ($entreprises as $entreprise): ?>
                            <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo $entreprise['Nom_Entreprise']; ?></option>
                        <?php endforeach; ?>
                    </select><br><br><br>
            </div>

            <div class="wrap-in">
                    <label for="employe">Mouton :</label>
                    <select name="employe" id="employe" onchange="updateEmploye_Data();" class="combo-form">
                        <!-- options ajoutées dynamiquement via JavaScript -->
                    </select><br><br>
                    
                    <!--<label for="ID_Employe">ID :</label>
                    <input type="ID" id="ID_Employe" name="ID_Employe" readonly class="beau_gros_champ">-->

                    <label for="telephone">Numéro de téléphone :</label>
                    <input type="tel" id="telephone" name="telephone" readonly class="beau_gros_champ"><br><br>

                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" readonly class="beau_gros_champ" size="30px"><br><br>
            </div>

            <div>
            <!-- Formulaire de collage d'image intégré -->
            <div id="image-frame">
                <div class="placeholder">Image</div>
                <img id="pastedImage" src="" alt="Pasted Image">
            </div>
            <input type="hidden" name="imageData" id="imageData">
        </div>

            <label for="demande">Sujet :</label>
            <textarea id="demande" name="demande" rows="8" cols="70" required></textarea>
            <br><br>

            <input type="submit" name="submit" value="Créér">
        </form>

        <script>
              document.addEventListener('paste', function (event) {
            var items = event.clipboardData.items;
            for (var i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    var blob = items[i].getAsFile();
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        var img = document.getElementById('pastedImage');
                        var placeholder = document.querySelector('#image-frame .placeholder');
                        if (img) {
                            img.src = event.target.result;
                            img.style.display = 'block';
                            document.getElementById('imageData').value = event.target.result;
                            if (placeholder) {
                                placeholder.style.display = 'none';
                            }
                        } else {
                            console.error("Element with id 'pastedImage' not found.");
                        }
                    };
                    reader.readAsDataURL(blob);
                }
            }
        });
        </script>
        <script>
            function copyTableContents() 
            {
                var table = document.querySelector('table');
                var range = document.createRange();
                range.selectNode(table);
                window.getSelection().removeAllRanges(); // Clear any previous selections
                window.getSelection().addRange(range);

                try {
                    var successful = document.execCommand('copy');
                    if (successful) {
                        alert('Le contenu du tableau a été copié.');
                    } else {
                        alert('La copie du contenu a échoué.');
                    }
                } catch (err) {
                    alert('La commande de copie a échoué.');
                }

                window.getSelection().removeAllRanges(); // Clear the selection
            }
        </script>
    </body>
    <script>
        
                        // Mettre à jour Temps heure dans SaisirTicket.php
                // ---------------------------- Obtenir date, heure et numéro de semaine en temps réel ----------------------------

                function updateDateTime() {
                var now = new Date();
                var dateElement = document.getElementById('date');
                var heureElement = document.getElementById('heure');
                var semaineElement = document.getElementById('semaine');

                // Affichage de la date
                dateElement.textContent = now.toLocaleDateString('fr-FR');

                // Affichage de l'heure
                heureElement.textContent = now.toLocaleTimeString('fr-FR');

                // Calcul du numéro de semaine
                var firstDayOfYear = new Date(now.getFullYear(), 0, 1);
                var pastDaysOfYear = (now - firstDayOfYear) / 86400000;
                semaineElement.textContent = Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
            }

            // Appel initial et mise à jour périodique de la fonction pour obtenir la date, l'heure et le numéro de semaine
            updateDateTime();
            setInterval(updateDateTime, 1000); // Mise à jour chaque seconde

    </script>
    <script src="scripts/Filtre_Entreprise.js"></script>
    <script src="scripts/UpdateEmployees.js"></script>
    <script src="scripts/UpdateEmployeData.js"></script>
</html>
<?php
} else {
    // Traitement du formulaire soumis
    try {
        // Traiter les données du ticket
        $Sujet_Ticket = filter_input(INPUT_POST, 'demande', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ID_Entreprise = filter_input(INPUT_POST, 'entreprise', FILTER_SANITIZE_NUMBER_INT);
        $ID_Employe = filter_input(INPUT_POST, 'employe', FILTER_SANITIZE_NUMBER_INT);

        // Traiter les données de l'image
        $imageData = $_POST['imageData'];
        $fileName = null;

        if (!empty($imageData)) 
        {
            // Récupérer le nom de l'entreprise
            $query = "SELECT Nom_Entreprise FROM SLY_Entreprises WHERE ID_Entreprise = :ID_Entreprise";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':ID_Entreprise', $ID_Entreprise, PDO::PARAM_INT);
            $stmt->execute();
            $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);
            $Nom_Entreprise = $entreprise['Nom_Entreprise'];

            // Extraire le type de l'image (par exemple "image/png")
            $imageParts = explode(";base64,", $imageData);
            $imageTypeAux = explode("image/", $imageParts[0]);
            $imageType = $imageTypeAux[1];

            // Décoder les données base64
            $imageBase64 = base64_decode($imageParts[1]);

            // Créer un nom de fichier valide
            $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $Nom_Entreprise) . '-' . date("YmdHis") . '.' . $imageType;

            // Définir le chemin de sauvegarde
            $filePath = 'images/' . $fileName;

            // Sauvegarder le fichier sur le serveur
            file_put_contents($filePath, $imageBase64);
        }

        // Insérer les données du ticket dans la base de données
        $sql = "INSERT INTO SLY_Ticket ( Sujet_Ticket, ID_Entreprise, ID_employe, DateCreationTicket, Statut_Ticket, Nom_Image ) 
                VALUES ( :demande, :ID_Entreprise, :ID_Employe, DATE_ADD(NOW(), INTERVAL 2 HOUR), 1, :Nom_Image )";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':demande', $Sujet_Ticket, PDO::PARAM_STR);
        $stmt->bindValue(':ID_Entreprise', $ID_Entreprise, PDO::PARAM_INT);
        $stmt->bindValue(':ID_Employe', $ID_Employe, PDO::PARAM_INT);
        $stmt->bindValue(':Nom_Image', $fileName, PDO::PARAM_STR);

        $success = $stmt->execute();

        if ($success) 
        {
            // Récupérer les informations du ticket créé
            $ticketID = $db->lastInsertId();
            $query = "SELECT * FROM SLY_Ticket WHERE ID_Ticket = :ID_Ticket";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':ID_Ticket', $ticketID, PDO::PARAM_INT);
            $stmt->execute();
            $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

            // Afficher les informations du ticket dans un tableau HTML
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>ID Ticket</th>";
            echo "<th>Sujet</th>";
            echo "<th>ID Entreprise</th>";
            echo "<th>ID Employé</th>";
            echo "<th>Date de Création</th>";
            echo "<th>Statut</th>";
            if ($fileName) 
            {
                echo "<th>Nom de l'image</th>";
            }
            echo "</tr>";
            echo "<tr>";
            echo "<td>" . htmlspecialchars($ticket['ID_Ticket']) . "</td>";
            echo "<td>" . htmlspecialchars($ticket['Sujet_Ticket']) . "</td>";
            echo "<td>" . htmlspecialchars($ticket['ID_Entreprise']) . "</td>";
            echo "<td>" . htmlspecialchars($ticket['ID_Employe']) . "</td>";
            echo "<td>" . htmlspecialchars($ticket['DateCreationTicket']) . "</td>";
            echo "<td>" . htmlspecialchars($ticket['Statut_Ticket']) . "</td>";
            if ($fileName) 
            {
                echo "<td><a href='$filePath'>" . htmlspecialchars($ticket['Nom_Image']) . "</a></td>";
            }
            echo "</tr>";
            echo "</table>";

            // Boutons
            echo "<button onclick=\"copyTableContents();\">Copier le contenu</button>";
            echo "<button onclick=\"window.location.href='SaisirTicket.php';\">Nouveau ticket</button>";

            echo "<p id='successMessage'>Le ticket a bien été créé.</p>";
            exit();
            // echo "<script>
            //     setTimeout(function() {
            //         window.location.href = 'SaisirTicket.php';
            //     }, 1500);
            // </script>";
        } 
        else 
        {
            echo "Une erreur s'est produite lors de la création du ticket.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la création du ticket : " . $e->getMessage();
    } finally {
        // Fermeture de la connexion à la base de données
        $db = null;
    }
}
?>