function validate() {
    var u = document.getElementById("email").value;
    var p1 = document.getElementById("password").value;
    var p2 = document.getElementById("password-repeat").value;
    let n = document.getElementById("nom").value;
    let pn = document.getElementById("prenom").value;
    let bd = document.getElementById("birthday").value;

    function ValidateEmail(mail) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
            return (true)
        }
        alert("You have entered an invalid email address!")
        return (false)
    }

    if (!ValidateEmail(u)) {
        //     alert("Email is valide")
        // } else {
        alert("Email isn't valide")
        return false;
    }

    if (u == "") {
        alert("Enter your email!");
        return false;
    } else {
        if (!ValidateEmail(u) === true) {
            //     alert("Name is valide")
            // } else {
            alert("Your email isn't valide")
            return false;
        }

    }

    if (n == "") {
        alert("Please enter your first name!");
        return false;
    }
    //Nom

    if (pn == "") {
        alert("Please enter your last name!");
        return false;
    }
    //Prenom

    if (bd == "") {
        alert("Please enter your birthday!");
        return false;
    }
    //Birthday

    // Validates that the input string is a valid date formatted as "mm/dd/yyyy"
    function isValidDate(dateString) {
        // First check for the pattern
        if (!/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(dateString))
            return false;

        // Parse the date parts to integers
        var parts = dateString.split("/");
        var day = parseInt(parts[2], 10);
        var month = parseInt(parts[1], 10);
        var year = parseInt(parts[0], 10);

        // return alert(`Day :${day} Month :${month} year:${year}`);

        // Check the ranges of month and year
        if (year < 1000 || year > 3000 || month == 0 || month > 12)
            return false;

        var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        // Adjust for leap years
        if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
            monthLength[1] = 29;

        // Check the range of the day
        return day > 0 && day <= monthLength[month - 1];
    };
    if (!isValidDate(bd)) {
        alert("Wrong type birthday. Please follow this type AAAAMMJJ")
        return false;
    }


    if (p1 == "") {
        alert("Please enter your password!");
        return false;
    }
    if (p1.length < 6) {
        alert("Password is too weak")
            // else { alert("Pass is too strong") }
        return false;
    }
    if (p2 == "") {
        alert("Please enter your confirm password!");
        return false;
    }
    // Check confirm password

    if (p1 !== p2) {
        alert("Les deux passe ne sont pas le meme");
        return false;
    }
    //Check if les deux passe sont le meme
    alert("Your inscription is valide!")

    return true;
}