<?php
include("db_conn/db_conn.php");

// Vérifier si la table SLY_Images existe, sinon la créer
$tableExists = $db->query("SHOW TABLES LIKE 'SLY_Images'")->rowCount() > 0;
if (!$tableExists) {
    try {
        $sqlCreateTable = "CREATE TABLE SLY_Images (
                            ID_Image INT(11) AUTO_INCREMENT PRIMARY KEY,
                            Nom_Image VARCHAR(255) NOT NULL,
                            Taille_Image INT,
                            Bin_Image BLOB
                          )";
        $db->exec($sqlCreateTable);
        echo "Table Images créée avec succès.<br>";
    } catch (PDOException $e) {
        echo "Erreur lors de la création de la table Images: " . $e->getMessage() . "<br>";
    }
}

// Récupérer les noms des entreprises et leurs identifiants
$query = "SELECT ID_Entreprise, Nom_Entreprise FROM SLY_Entreprises";
$statement = $db->query($query);
$entreprises = $statement->fetchAll(PDO::FETCH_ASSOC);

// Gérer les images
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['submit'])) {
    try {
        // Chemin vers le dossier où les images seront téléchargées
        $target_dir = "images/";

        // Vérifier si le dossier image existe
        if (!is_dir($target_dir)) {
            // Créer le dossier s'il n'existe pas
            if (!mkdir($target_dir, 0777, true)) {
                throw new Exception("Échec de la création du dossier images.");
            }
        }

        // Générer un nom de fichier unique basé sur la date et l'heure actuelles
        $image_extension = strtolower(pathinfo($_FILES["Nom_Image"]["name"], PATHINFO_EXTENSION));
        $target_file = $target_dir . date("YmdHis") . "." . $image_extension;

        // Obtenir la taille de l'image
        $image_size = $_FILES["Nom_Image"]["size"];

        // Requête d'insertion dans la base de données
        $sql = "INSERT INTO SLY_Images (Nom_Image, Taille_Image) VALUES (:Nom_Image, :Taille_Image)";
        $stmt = $db->prepare($sql);

        // Liaison des paramètres
        $Nom_Image = date("YmdHis") . "." . $image_extension; // Nouveau nom de fichier avec date et heure
        $stmt->bindValue(':Nom_Image', $Nom_Image, PDO::PARAM_STR);
        $stmt->bindValue(':Taille_Image', $image_size, PDO::PARAM_INT);

        // Déplacer le fichier téléchargé vers le dossier spécifié
        if (move_uploaded_file($_FILES["Nom_Image"]["tmp_name"], $target_file)) {
            $success = $stmt->execute();
            if ($success) {
                echo "Image ajoutée";
            } else {
                echo "Une erreur s'est produite lors de l'ajout de l'image.";
            }
        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } catch (PDOException $e) {
        // En cas d'erreur, affichez un message d'erreur.
        echo "Erreur lors de l'ajout de l'image : " . $e->getMessage();
    }
}

// Vérifier si le formulaire a été soumis
if (!isset($_POST['submit'])) {
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Création de Ticket</title>
</head>
<body>
    <h1>Création de Ticket pour de vrai &#128526;</h1>
    <h3> <a href="CheckJson.php">Générer un json</a> </h3>
    
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" onsubmit="prepareImage(event)">
        <!-- Ajout du champ hidden pour envoyer la variable submit --> 
        <input type="hidden" name="submit" value="true">

        <!-- Ajout d'un champ hidden pour stocker le nom de l'entreprise sélectionnée -->
        <input type="hidden" id="Nom_Entreprise" name="Nom_Entreprise">

        <!-- Date / Heure / Semaine -->
        <div class="TimeBlock">
            <label>Semaine</label>
            <span id="semaine"></span>

            <label></label>
            <span id="date"></span>

            <label></label>
            <span id="heure"></span>
        </div>

        <div class="wrapper">
            <div class="wrap-in">
                <label for="searchInput">Filtre à troupeau</label>
                <input type="text" id="searchInput">

                <label for="entreprise">Troupeau :</label>
                <select class="combo-form" name="entreprise" id="entreprise" required onchange="updateEmployees()">
                    <?php foreach ($entreprises as $entreprise): ?>
                        <option value="<?php echo $entreprise['ID_Entreprise']; ?>"><?php echo $entreprise['Nom_Entreprise']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        
            <div class="wrap-in">
                <label for="employe">Mouton :</label>
                <select name="employe" id="employe" required onchange="updatePhoneNumber(); updateMail();"class="combo-form">
                    <!-- options ajoutées dynamiquement via JavaScript -->
                </select>

                <label for="telephone">Numéro de téléphone :</label>
                <input type="tel" id="telephone" name="telephone" readonly>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" readonly size="30">
            </div>
            <div class="wrap-in">
                <label for="Nom_Image"></label>
                <input type="file" id="hiddenImageInput" name="Nom_Image" accept="image/*">
                <input type="text" id="imagePasteArea" placeholder="Collez votre image ici" onpaste="handlePaste(event)">
            </div>
        </div>
    <br>

        <div class="wrapper">                
            <div class="wrap-in">
                <label for="demande">Sujet :</label>
                <textarea id="demande" name="demande" rows="8" cols="100" required></textarea>
            </div>
            <div class="wrap-in">
                <label for="resolution">Résolution :</label>
                <textarea id="resolution" name="resolution" rows="8" cols="100"></textarea>
            </div>
        
            <div class="wrap-in">
                <input type="submit" value="Créér">
            </div>
        </div>
    </form>

    <script>
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

        // -------------------------------------------------------- Actualiser les champs du formulaire --------------------------------------------------------

        // Filtre ENTREPRISE
        const searchInput = document.getElementById('searchInput');
        const selectMenu = document.getElementById('entreprise');
                
        searchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();
            for (let option of selectMenu.options) {
                const optionText = option.textContent.toLowerCase();
                option.style.display = optionText.includes(searchText) ? '' : 'none';
            }
        });

        // Fonction pour mettre à jour le champ hidden avec le nom de l'entreprise sélectionnée
        function updateEntrepriseNom() {
            var entrepriseSelect = document.getElementById("entreprise");
            var entrepriseNomInput = document.getElementById("Nom_Entreprise");
            entrepriseNomInput.value = entrepriseSelect.options[entrepriseSelect.selectedIndex].text;
        }

        // Ajouter un écouteur d'événement pour mettre à jour le champ hidden lorsque l'entreprise est sélectionnée
        document.getElementById("entreprise").addEventListener("change", updateEntrepriseNom);

        // Fonction pour actualiser les employés en fonction de l'entreprise sélectionnée
        function updateEmployees() {
            var id_entreprise = document.getElementById('entreprise').value;
            var employeSelect = document.getElementById('employe');
            employeSelect.innerHTML = ''; // Réinitialiser la liste des employés

            // Appel AJAX pour obtenir les employés de l'entreprise sélectionnée
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_employees.php?id_Entreprise=' + id_entreprise, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var employees = JSON.parse(this.responseText);
                    employees.forEach(function(employee) {
                        var option = document.createElement('option');
                        option.value = employee.ID_Employe;
                        option.textContent = employee.Prenom_Employe + ' ' + employee.Nom_Employe;
                        employeSelect.appendChild(option);
                    });
                    // Mise à jour des autres champs en fonction du premier employé
                    updatePhoneNumber();
                    updateMail();
                }
            };
            xhr.send();
        }

        // Fonction pour mettre à jour le numéro de téléphone en fonction de l'employé sélectionné
        function updatePhoneNumber() {
            var employeId = document.getElementById('employe').value;

            // Appel AJAX pour obtenir le numéro de téléphone de l'employé sélectionné
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_employees.php?ID_Employe=' + employeId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var employee = JSON.parse(this.responseText);
                    document.getElementById('telephone').value = employee.Telephone_Employe;
                }
            };
            xhr.send();
        }

        // Fonction pour mettre à jour l'email en fonction de l'employé sélectionné
        function updateMail() {
            var employeId = document.getElementById('employe').value;

            // Appel AJAX pour obtenir l'email de l'employé sélectionné
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_employees.php?ID_Employe=' + employeId, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    var employee = JSON.parse(this.responseText);
                    document.getElementById('email').value = employee.Mail_Employe;
                }
            };
            xhr.send();
        }

        // Gestion du collage d'image
        function handlePaste(event) {
            const items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (let item of items) {
                if (item.kind === 'file' && item.type.startsWith('image/')) {
                    const file = item.getAsFile();
                    document.getElementById('hiddenImageInput').files = new DataTransfer().items.add(file).files;
                    document.getElementById('imagePasteArea').value = 'Image collée';
                }
            }
        }

        // Fonction pour vérifier si une image a été collée avant de soumettre le formulaire
        function prepareImage(event) {
            const imageInput = document.getElementById('hiddenImageInput');
            const pasteArea = document.getElementById('imagePasteArea');
            if (pasteArea.value === 'Image collée' && imageInput.files.length === 0) {
                event.preventDefault();
                alert('Veuillez coller l\'image à nouveau.');
            }
        }

    </script>
</body>
</html>

<?php
} else {
    // Traitement du formulaire soumis pour créer un ticket
    try {
        $ID_Entreprise = $_POST['entreprise'];
        $ID_Employe = $_POST['employe'];
        $Nom_Entreprise = $_POST['Nom_Entreprise'];
        $Prenom_Employe = $_POST['employe'];  // Mise à jour pour récupérer le prénom
        $Nom_Employe = $_POST['employe'];     // Mise à jour pour récupérer le nom
        $Telephone_Employe = $_POST['telephone'];
        $Mail_Employe = $_POST['email'];
        $Sujet_Ticket = $_POST['demande'];
        $Resolution_Ticket = $_POST['resolution'];
        $Nom_Image = $_FILES['Nom_Image']['name'];

        $stmt = $db->prepare("INSERT INTO SLY_Ticket (ID_Entreprise, ID_Employe, Nom_Entreprise, Prenom_Employe, Nom_Employe, Telephone_Employe, Mail_Employe, Sujet_Ticket, Resolution_Ticket, Nom_Image) VALUES (:ID_Entreprise, :ID_Employe, :Nom_Entreprise, :Prenom_Employe, :Nom_Employe, :Telephone_Employe, :Mail_Employe, :Sujet_Ticket, :Resolution_Ticket, :Nom_Image)");

        // Liaison des paramètres
        $stmt->bindParam(':ID_Entreprise', $ID_Entreprise);
        $stmt->bindParam(':ID_Employe', $ID_Employe);
        $stmt->bindParam(':Nom_Entreprise', $Nom_Entreprise);
        $stmt->bindParam(':Prenom_Employe', $Prenom_Employe);
        $stmt->bindParam(':Nom_Employe', $Nom_Employe);
        $stmt->bindParam(':Telephone_Employe', $Telephone_Employe);
        $stmt->bindParam(':Mail_Employe', $Mail_Employe);
        $stmt->bindParam(':Sujet_Ticket', $Sujet_Ticket);
        $stmt->bindParam(':Resolution_Ticket', $Resolution_Ticket);
        $stmt->bindParam(':Nom_Image', $Nom_Image);

        if ($stmt->execute()) {
            echo "Ticket créé avec succès.";
        } else {
            echo "Erreur lors de la création du ticket.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
