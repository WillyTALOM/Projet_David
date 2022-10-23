var checkboxUser = document.querySelector('#registration_form_showPassword');
var passwordFieldUser = document.querySelector('#registration_form_plainPassword_first');
if (checkboxUser != null) {
    checkboxUser.addEventListener('click', function (e) {
        if (passwordFieldUser.type === "plainPassword") {
            passwordFieldUser.type = "text";
        } else {
            passwordFieldUser.type = "plainPassword";
        }
    });
}

