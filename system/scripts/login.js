checkRegister();

function checkRegister() {
    var submitButton = document.getElementById("registerButton");
    var password1 = document.getElementsByName("passwordRegister1")[0].value;
    var password2 = document.getElementsByName("passwordRegister2")[0].value;
    var nbError = 0;


    var tooltip1 = document.getElementById("errorMessageRegisterP1");
    if (password1 != 0 && password1.length < 10) {
        tooltip1.style.display = "inline-block";
        tooltip1.childNodes[3].innerText = "Please use a password of at least 10 characters";
        nbError++;
    } else {
        tooltip1.style.display = "none";
        tooltip1.childNodes[3].innerText = "";
    }

    var tooltip2 = document.getElementById("errorMessageRegisterP2");
    if (password1 && password2 && password1 != password2) {
        tooltip2.style.display = "inline-block";
        tooltip2.childNodes[3].innerText = "Passwords are different";
        nbError++;
    } else {
        tooltip2.style.display = "none";
        tooltip2.childNodes[3].innerText = "";
    }

    if (nbError) {
        submitButton.disabled = true;
    } else {
        submitButton.disabled = false;
    }
}