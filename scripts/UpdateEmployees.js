           
           //UpdateEmployees.js : Mettre à jour EMPLOYE en fonction ENTREPRISE dans créer ticket

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
                            option.value = employee.ID_Employe;
                            option.text = employee.Prenom_Employe + " " + employee.Nom_Employe;
                            option.dataset.ID = employee.ID_Employe;
                            option.dataset.telephone = employee.Num_Employe;
                            option.dataset.email = employee.Mail_Employe; 
                            employeSelect.appendChild(option);
                        });
                     } else { console.error('Erreur de requête : ' + xhr.status); }
                }
            };
            xhr.open("GET", "get_employees.php?id_entreprise=" + encodeURIComponent(id_entreprise), true);
            xhr.send();
        }

        // Appel initial de la fonction updateEmployees() au chargement de la page
        //updateEmployees();