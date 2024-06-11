                // Mettre à jour Tems heure dans SaisirTicket.php
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