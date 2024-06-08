function MajDataEmploye() {
    var select = document.getElementById("employe");
    var input_telephone = document.getElementById("telephone");
        input_telephone.value = select.options[select.selectedIndex].dataset.telephone;
    var input_email = document.getElementById("email");
        input_email.value = select.options[select.selectedIndex].dataset.email;
}