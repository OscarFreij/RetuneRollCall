function sendQuerry() {
    var u = document.getElementById('username').value;
    var p = document.getElementById('password').value;

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        if (this.readyState == 4)
        {
            console.log("CODE: "+this.status+" RESPONS: "+this.responseText);
            if (this.status == 202)
            {
                location.href = "?page=home";
            }
            else
            {
                document.getElementById('login-output').innerText = "ERROR: username or password is wrong!";
            }
        }
    }
    xhttp.open("POST", "callback.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("cba=login&username="+u+"&password="+p);
}