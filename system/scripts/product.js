checkPeriod();


function checkPeriod() {
    var currentDate = new Date(Date.now());
    var errorMessage = document.getElementById("errorMessage");
    var submitButton = document.getElementById("submitButton")
    var dateFrom = document.getElementsByName("fromDate")[0].value;
    var dateTo = document.getElementsByName("toDate")[0].value;
    var myDateFrom, myDateTo;
    var nbError = 0;

    if (dateFrom) {
        var parts = dateFrom.split('-');
        myDateFrom = new Date(parts[0], parts[1] - 1, parts[2]);
        if (myDateFrom < currentDate) {
            errorMessage.innerText = "Please select a correct beginning date."
            errorMessage.style.display = "block";
            nbError++;
        }
    }

    if (dateTo) {
        var parts = dateTo.split('-');
        myDateTo = new Date(parts[0], parts[1] - 1, parts[2]);
        if (myDateTo < currentDate) {
            errorMessage.innerText = "Please select a correct ending date."
            errorMessage.style.display = "block";
            nbError++;
        }
    }

    if (dateTo && dateFrom) {
        if (myDateFrom > myDateTo) {
            errorMessage.innerText = "Please select a correct period."
            errorMessage.style.display = "block";
            nbError++;
        }
    }

    if (!dateFrom || !dateTo) {
        nbError++;
    }

    if (nbError == 0) {
        errorMessage.innerText += "";
        errorMessage.style.display = "none";
        submitButton.disabled = false;
    } else {
        submitButton.disabled = true;
    }
}