
<!DOCTYPE html>

    <?php
        include("header.php");
    ?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style.css" rel="stylesheet">
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
        <main id="poeme"><br>
            <!-- Bouton pour basculer entre les styles -->
            <button id="toggleButton" onclick="toggleStyles()" class="beau_gros_champ">Ceci n'est pas un Dashboard</button><br><br>
        
            <h3>Mouton</h3><br><br>
            <p>Oh, doux mouton, bête de laine,<br>
                Comme ta présence m'entraîne,<br>
                Dans de verts pâturages tu m'amène,<br>
                Et par dessus les barrières, a en perdre haleine.<br>
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
