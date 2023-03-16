// JavaScript code to validate the form inputs and send the form data to the server
var form = document.getElementById("emailForm");
form.onsubmit = function(event) 
{
    event.preventDefault();
    var to = form.elements["to"].value;
    var from = form.elements["from"].value;
    var body = form.elements["body"].value;
    var scheduleDate = new Date(form.elements["scheduleDate"].value);
    var currentDate = new Date();
    
    if (scheduleDate <= currentDate) 
    {
        alert("Please enter a schedule date that is after the current date and time.");
        return;
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "schedule_email.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) 
        {
            alert(xhr.responseText);
        }
    };
    xhr.send("to=" + to + "&from=" + from + "&body=" + body + "&scheduleDate=" + scheduleDate);
}