<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href= "stylecp.css"  >
    <?php include('schedule-email.php') ?>
    <script src="script.js"></script>
    <title>Email Scheduler</title>
</head>
<body>
    <h1>Email Scheduler.....

    </h1>

 <form action="schedule-email.php" method="post" onsubmit="return validateForm()">

    <label for="to">From:</label>
    <input type="email" id="to" name="to" required autocomplete="off">
    <br>

    <label for="from">To:</label>
    <input type="email" id="from" name="from" required autocomplete="off">
    <br>

    <label for="name">Name:</label>
    <textarea id="name" name="name" required autocomplete="off"></textarea>
    <br>

    <label for="emailbody">Body:</label>
    <textarea id="emailbody" name="emailbody" required autocomplete="off"></textarea>
    <script>
            const inputField = document.getElementById('emailbody');
            const wordCountElement = document.createElement('span');
            inputField.parentNode.insertBefore(wordCountElement, inputField.nextSibling);

            inputField.addEventListener('input', function() 
            {
            const inputValue = inputField.value;
            const words = inputValue.trim().split(/\s+/);
            const wordCount = inputValue.trim() === '' ? 0 : words.length;

            wordCountElement.innerText = `Number of words: ${wordCount}`;
            });


    </script>
    <br>

    <label for="scheduleDate">Schedule Date:</label>
    <input type="datetime-local" id="scheduleDate" name="scheduleDate" required autocomplete="off">
    <br>

    <!-- Human Verification Code -->
    <label for="verificationCode">Verification Code:</label>
    <span style="font-size: 24px; color: green;">
    <?php
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $sum = $num1 + $num2;
        echo "$num1 + $num2 = ";
    ?>
    </span>
    <input type="text" id="verificationCode" name="verificationCode" required autocomplete="off">
    <br>

    <button type="submit">Schedule Email</button>
</form>

<script>
    function validateForm() 
    {
        let to = document.querySelector("#to").value;
        let from = document.querySelector("#from").value;
        let scheduleDate = document.querySelector("#scheduleDate").value;
        let now = new Date();
        let fiveMinutesLater = new Date(now.getTime() + 1 * 10 * 1000);
        let verificationCode = document.querySelector("#verificationCode").value;
        
        if (to === "" || from === "") {
            alert("Email fields cannot be empty.");
            return false;
        }
        
        if (new Date(scheduleDate) < fiveMinutesLater) {
            alert("Schedule date must be at least 1 minutes from now.");
            return false;
        }
        
        if (verificationCode != <?php echo $sum; ?>) {
            alert("Verification code is incorrect.");
            return false;
        }
        
        return true;
    }
</script>
</body>
</html>
