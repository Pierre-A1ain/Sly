<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Fantasy V1</title>
</head>
<body>
    <div class="header">
        <img src="NightSheep.png" id="sheep" alt="" href="lambing_page_test.php">
        <h1> &#127925; Is it the real life ? <br> Is just a fantasy ? &#127925; </h1>
        <div class="SLYLogo-container">
            <img src="SLYLogo1.jpeg" id="ticket" alt="" class="SLYLogo">
        </div>
    </div>
    
    <nav class="navbar">
        <ul>
            <li><a href="lambing_page.php">Accueil</a></li>
            <li><a href="CreerTicket2.php">Créer un ticket</a></li>
            <li><a href="gestion_entreprise.php">Gérer ses enclos</a></li>
            <li><a href="gestion_entreprise2.php">Gérer ses enclos 2</a></li>
            <li><a href="tb_fun.php">Tableau test</a></li>
            <li><a href="TableauDire.php">Tableau dire</a></li>

        </ul>
    </nav>

        <!-- Fenêtre modale -->
        <div id="modal" class="modal" style=display:none>
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Conception</h2><br>
                <p class="myself">Pierre-Alain Martignoles</p>
                <div class="modal-btn-line">
                    <p class="txt-modal-btn-line">Animation ticket</p>
                    <input type="checkbox" name="toggleBtn" id="toggleBtn" class="toggleBtn" checked>
                    <label for="toggleBtn" class="labelToggleBtn"></label>
                </div>
                <div class="modal-btn-line">
                    <p class="txt-modal-btn-line">Fond Musical</p>
                    <input type="checkbox" name="toggleBtn" id="toggleBtn" class="toggleBtn" checked>
                    <label for="toggleBtn" class="labelToggleBtn"></label>
                </div>
            </div>
        </div>

    <script>
        // Récupérer l'élément de l'image ticket
        var ticketImg = document.getElementById('ticket');
        // Récupérer l'élément de la fenêtre modale
        var modal = document.getElementById('modal');
        // Récupérer l'élément de fermeture de la fenêtre modale
        var closeModal = document.getElementsByClassName('close')[0];

        // Ajouter un événement de clic à l'image ticket
        ticketImg.onclick = function() {
            modal.style.display = 'block'; // Afficher la fenêtre modale lorsque l'image est cliquée
        }

        // Ajouter un événement de clic pour fermer la fenêtre modale
        closeModal.onclick = function() {
            modal.style.display = 'none'; // Cacher la fenêtre modale lorsque l'élément de fermeture est cliqué
        }

        // Fermer la fenêtre modale si l'utilisateur clique en dehors de celle-ci
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

</body>
</html>