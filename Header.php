<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Fantasy V1</title>
</head>
<body>
    <div class="header">
    <img src="NightSheep.png" id="sheep" alt="">
    <h1> &#127925; Is it the real life ? <br> Is just a fantasy ? &#127925; </h1>
    <img src="SLYLogo1.jpeg" id="ticket" alt="">
    </div>
    
    <nav class="navbar">
        <ul>
            <li><a href="LambingPage.php">Accueil</a></li>
            <li><a href="CreerTicket2.php">Aider un mouton</a></li>
            <li><a href="VoirTickets.php">Tickets</a></li>
            <li><a href="VoirMoutons.php">Compter ses moutons</a></li>
            <li><a href="Entreprise_Modifier.php">Gérer ses cheptels</a></li>
            <li><a href="AjouterClient3.php">Gérer ses moutons</a></li>
            <li><a href="AjouterImage3.php">Dessiner un mouton</a></li>
            <li><a href="Gallery.php">Mes dessins</a></li>
            <li><a href="Index.php">Se déconnecter</a></li>

        </ul>
    </nav>

        <!-- Fenêtre modale -->
        <div id="modal" class="modal" style=display:none>
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Conception Pierre-Alain Martignoles</p>
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