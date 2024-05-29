<?php
include("db_conn/db_conn.php");;
include("header.php");

// Récupérer les noms des entreprises et leurs identifiants
$query = "SELECT ID_Entreprise, Nom_Entreprise FROM SLY_Entreprises";
$statement = $db->query($query);
$entreprises = $statement->fetchAll(PDO::FETCH_ASSOC);

//Gérer les images
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Chemin vers le dossier où les images seront téléchargées
        $target_dir = "images/";

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

<!-- ------------------------------------------------------------------------------------------------------------------------------------------ -->
<!--                                                                Section HTML                                                                -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------ -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Ticket</title>
</head>
<body>
<main>
    <h1>Créer un ticket &#128526;</h1>
    <h3 class="margin15"> <a href="CheckJson.php">Générer un json</a> </h3>
    
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" onsubmit="prepareImage(event)">
        <!-- Ajout du champ hidden pour envoyer la variable submit --> 
            <input type="hidden" name="submit" value="true">

         <!-- Ajout d'un champ hidden pour stocker le nom de l'entreprise sélectionnée -->
            <input type="hidden" id="Nom_Entreprise" name="Nom_Entreprise">

        <!-- Date / Heure / Semaine -->
            <div class="TimeBlock">
                <label class="padR7">Semaine</label>
                <span id="semaine" class="padR20"></span>

                <label></label>
                <span id="date" class="padR7"></span>

                <label></label>
                <span id="heure" class="padR7"></span>

            </div>

            <div class="wrapper">
                <div class="wrap-in">
                    <label for="searchInput">Filtre à troupeau</label>
                    <input type="text" id="searchInput" class="beau_gros_champ"><br>

                    <label for="entreprise" class="margin15">Troupeau :</label>
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
                    </select><br>

                    <label for="telephone">Numéro de téléphone :</label>
                    <input type="tel" id="telephone" name="telephone" readonly class="beau_gros_champ"><br>

                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" readonly class="beau_gros_champ" size="30px">
                </div>
                <div class="wrap-in entravo">
                    <div>&#x2192; boutons tel/mail</div><br>
                    <div>&#x2192; choix degré urgence </div><br>
                    <div>&#x2192; affecter à ... </div>
                </div>
                <!-- Cadre capture image html -->
                <div class="wrap-in entravo">
                    <label for="Nom_Image"></label><br>
                    <input type="file" id="hiddenImageInput" name="Nom_Image" accept="image/*"><br>
                    <div class="margin15"></div>
                    <input type="text" id="imagePasteArea" placeholder="Collez votre image ici" onpaste="handlePaste(event)" size="30px" rows="8">
                </div>
                <!-- Fin cadre capture image html -->
            </div>
        <br>

            <div class="wrapper">                
                <div class="wrap-in">
                    <label for="demande">Sujet :</label>
                    <textarea id="demande" name="demande" rows="8" cols="70" required></textarea>
                </div>
                <div class="wrap-in">
                    <label for="resolution">Résolution :</label>
                    <textarea id="resolution" name="resolution" rows="8" cols="70"></textarea>
                </div>
            
                <div class="wrap-in">
                    <input type="submit" value="Créér et clore" class="beau_gros_champ">
                    <div class="degage30"></div>
                    <input type="submit" value="Créér" class="beau_gros_champ">
                </div>
            </div>
    </form>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------ -->
<!--                                                                SCRIPTS                                                                -->
<!-- ------------------------------------------------------------------------------------------------------------------------------------------ -->

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
            var selectedEntreprise = entrepriseSelect.options[entrepriseSelect.selectedIndex].text;
            entrepriseNomInput.value = selectedEntreprise;
        }

        // Ajout d'un gestionnaire d'événements pour l'événement "change" de l'entreprise
        var entrepriseSelect = document.getElementById("entreprise");
        entrepriseSelect.addEventListener("change", updateEmployees);

        // Ajout d'un événement onchange pour appeler la fonction lorsqu'une entreprise est sélectionnée
        var entrepriseSelect = document.getElementById("entreprise");
        entrepriseSelect.addEventListener("change", updateEntrepriseNom);

        // Mettre à jour EMPLOYE en fonction ENTREPRISE
        function updateEmployees() {
            var entrepriseSelect = document.getElementById("entreprise");
            var id_entreprise = entrepriseSelect.value;
            var employeSelect = document.getElementById("employe");

            // Effacer les options précédentes si besoin
            if (employeSelect.options.length > 0) {
                    employeSelect.innerHTML = "";
            }

            // Requête AJAX pour récupérer les employés de l'entreprise sélectionnée
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var employees = JSON.parse(xhr.responseText);

                    // Ajouter les options des employés
                    employees.forEach(function(employee) {
                        var option = document.createElement("option");
                        option.text = employee.Prenom_Employe + " " + employee.Nom_Employe;
                        option.dataset.telephone = employee.Num_Employe;
                        option.dataset.email = employee.Mail_Employe; 
                        employeSelect.appendChild(option);
                    });
                } else {
                    console.error('Erreur de requête : ' + xhr.status);
                }
            }
            };
            xhr.open("GET", "get_employees.php?id_entreprise=" + encodeURIComponent(id_entreprise), true);
            xhr.send();
        }

        // Appel initial de la fonction updateEmployees() au chargement de la page
        updateEmployees();


        //Mettre à jour TELEPHONE en fonction de EMPLOYE
        function updatePhoneNumber() {
            var employeSelect = document.getElementById("employe");
            var selectedEmployee = employeSelect.options[employeSelect.selectedIndex];
            var telephoneInput = document.getElementById("telephone");

            // Récupérer le numéro de téléphone de l'employé sélectionné
            var numeroTelephone = selectedEmployee.dataset.telephone;
            
            // Mettre à jour le champ numéro de téléphone
            telephoneInput.value = numeroTelephone;
            
        }

        // Mettre à jour l'EMAIL en fonction de l'EMPLOYE
        function updateMail() {
            var employeSelect = document.getElementById("employe");
            var selectedEmployee = employeSelect.options[employeSelect.selectedIndex];
            var eMailInput = document.getElementById("email");

            // Récupérer l'e-mail de l'employé sélectionné à partir des données attribuées
            var eMail = selectedEmployee.dataset.email;
            
            // Mettre à jour le champ e-mail
            eMailInput.value = eMail;
        }
            //Script gestion image
        function handlePaste(event) {
        var items = (event.clipboardData || event.originalEvent.clipboardData).items;
        for (index in items) {
            var item = items[index];
            if (item.kind === 'file') {
                var blob = item.getAsFile();
                var reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('imagePasteArea').style.backgroundImage = 'url(' + event.target.result + ')';
                    document.getElementById('hiddenImageInput').files = [blob];
                };
                reader.readAsDataURL(blob);
            }
        }
    }

    function prepareImage(event) {
        // If an image was pasted, prevent the default form submission
        if (document.getElementById('hiddenImageInput').files.length === 0) {
            event.preventDefault();
            alert('Veuillez coller une image ou choisir un fichier.');
        }
    }

    </script>
</main>
</body>
</html>

<?php
} else {
    // Traitement du formulaire soumis
    try {
        //Calcul numéro semaine
        $SemaineCreationTicket = date('W');

        // Requête d'insertion dans la base de données
        $sql = "INSERT INTO SLY_Ticket (Prenom_Employe, Nom_Employe, Num_Employe, Mail_Employe, 
                                        ID_Entreprise, Nom_Entreprise, 
                                        Nom_Image,
                                        Sujet_Ticket, Resolution_Ticket, DateCreationTicket, 
                                        SemaineCreationTicket, Statut_Ticket) 

                                VALUES (:Prenom_Employe, :Nom_Employe, :Num_Employe, :Mail_Employe,
                                        :ID_Entreprise, :Nom_Entreprise, 
                                        :Nom_Image,
                                        :demande, :resolution, CURRENT_DATE,
                                        :SemaineCreationTicket, 1)";
        $stmt = $db->prepare($sql); 

        // Liaison des paramètres
            $Prenom_Employe = filter_input(INPUT_POST, 'employe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $stmt->bindValue(':Prenom_Employe', $Prenom_Employe, PDO::PARAM_STR);

            $Nom_Employe = filter_input(INPUT_POST, 'employe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $stmt->bindValue(':Nom_Employe', $Nom_Employe, PDO::PARAM_STR);

            $Num_Employe = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_NUMBER_INT);
            $stmt->bindValue(':Num_Employe', $Num_Employe, PDO::PARAM_INT);

            $Mail_Employe = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $stmt->bindValue(':Mail_Employe', $Mail_Employe, PDO::PARAM_STR);

            $ID_Entreprise = filter_input(INPUT_POST, 'entreprise', FILTER_SANITIZE_NUMBER_INT);
            $stmt->bindValue(':ID_Entreprise', $ID_Entreprise, PDO::PARAM_INT);

            $Nom_Entreprise = filter_input(INPUT_POST, 'Nom_Entreprise', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $stmt->bindValue(':Nom_Entreprise', $Nom_Entreprise, PDO::PARAM_STR);

            $Sujet_Ticket = filter_input(INPUT_POST, 'demande', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $stmt->bindValue(':demande', $Sujet_Ticket, PDO::PARAM_STR);

            $Resolution_Ticket = filter_input(INPUT_POST, 'resolution', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $stmt->bindValue(':resolution', $Resolution_Ticket, PDO::PARAM_STR);

            $stmt->bindValue(':SemaineCreationTicket', $SemaineCreationTicket, PDO::PARAM_INT);


        $success = $stmt->execute();
        if ($success) {
            echo "Le ticket a bien été créé";
        } else {
            echo "Une erreur s'est produite lors de la création du ticket.";
        }

    } catch (PDOException $e) {
        // En cas d'erreur, affichez un message d'erreur.
        echo "Erreur lors de la création du ticket : " . $e->getMessage();
    }
}
// Fermer la connexion à la base de données
$db = null;
?>
