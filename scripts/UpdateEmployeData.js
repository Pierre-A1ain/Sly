           
           //updateEmploye_Data.js : 
                // 1. Mettre à jour mail / tel en fonction employé dans SaisirTicket.php
                // 2. Utiliser get_employees_data.php pr récupérer data en AJAX

            // Mettre à jour mail / tel en fonction EMPLOYE
            function updateEmploye_Data() {
                var employeSelect = document.getElementById("employe");
                var id_employe = employeSelect.value;
                var telephoneInput = document.getElementById("telephone");
                var emailInput = document.getElementById("email");

            // Requête AJAX pour récupérer tel/mail de l'employé sélectionnée
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var employee = JSON.parse(xhr.responseText);

                    // Mettre à jour les champs téléphone et email
                    telephoneInput.value = employee.Num_Employe || '';
                    emailInput.value = employee.Mail_Employe || '';
                     } else { 
                        console.error('Erreur de requête : ' + xhr.status); }
                }
            };
            xhr.open("GET", "get_employees_data.php?id_employe=" + encodeURIComponent(id_employe), true);
            xhr.send();
        }

        // Appel initial de la fonction updateEmploye_Data() au changement de l'employé
        document.getElementById("employe").addEventListener("change", updateEmploye_Data);