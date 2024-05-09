<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            <li><a href="Gestion_entreprise.php">Gérer ses cheptels</a></li>
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