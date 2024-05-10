
<!DOCTYPE html>

<?php
        include("db_conn/db_conn.php");
        include("header.php");
        ?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Lambing Page</title>
        <style>
            #mouton {
                position: absolute;
                bottom: 30px;
                left: -100px; /* Position initiale à gauche */
                transition: opacity 1s ease-in-out, left 5s linear; /* Transition d'opacité et de position */
            }
        </style>
    </head>
    <body>
        
        <main id="poeme"><br><br>
            <h3>Mouton</h3><br><br>
            <p>Oh, doux mouton, bête de laine,<br>
                Comme ta présence nous entraîne,<br>
                Dans de verts pâturages je te suis,<br>
                Et par dessus les barrières au plus profond de la nuit.<br>
                <br>
                Insouciant mouton, blanc de pureté,<br>
                Dans nos cœurs, tu as su te nicher.<br>
                Oh, comme il fait bon de te chérir,<br>
                Mouton, tu es notre avenir.</p><br><br>
        </main>

        <img id="mouton" src="JumpingSheep.png" alt="Mouton" style="opacity: 0;"> <!-- Image de mouton -->
        
        <script>
            // Récupération de l'image
            var mouton = document.getElementById('mouton');
            
            // Fonction pour animer le déplacement
            function deplacerImage() {
                mouton.style.opacity = '1'; // Rendre l'image visible
            
                // Déplacer l'image de gauche à droite
                var positionActuelle = -100; // Position initiale à gauche
                var interval = setInterval(function() {
                    positionActuelle += 10; // Déplacement de 10 pixels à chaque intervalle
                    mouton.style.left = positionActuelle + 'px'; // Appliquer la nouvelle position
                    
                    // Si l'image dépasse la bordure droite de l'écran, réinitialiser sa position
                    if (positionActuelle > window.innerWidth + mouton.width) {
                        mouton.style.opacity = '0'; // Rendre l'image invisible
                        mouton.style.left = '-100px'; // Réinitialiser la position à gauche
                        clearInterval(interval); // Arrêter l'animation
                    }
                }, 100); // Interval de 100 millisecondes
            }
            
            // Appeler la fonction pour démarrer l'animation
            deplacerImage();
        </script>
    </body>
</html>
