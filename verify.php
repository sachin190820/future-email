<?php

// Connect to the database
$host = "future-db.csc2uqqp87hs.ap-south-1.rds.amazonaws.com";
$username = "admin";
$password = "adminadmin";
$dbname = "email_db";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the email and code parameters are present in the URL
if (isset($_GET['to']) && isset($_GET['code'])) {
    $to_email = $_GET['to'];
    $code = $_GET['code'];

    // Retrieve the corresponding email from the database
    $sql = "SELECT * FROM emails WHERE to_email='$to_email' AND verification_code='$code'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Check if the entered code matches the code in the database
        if (isset($_POST['submit'])) {
            $entered_code = $_POST['code'];

            if ($entered_code == $row['verification_code']) {
                // Update the status in the database to "VERIFIED"
                $from_email = $row['from_email'];
                $update_sql = "UPDATE emails SET status='VERIFIED' WHERE from_email='$from_email' AND to_email='$to_email' AND verification_code='$code'";
                mysqli_query($conn, $update_sql);

                // Display a success message
                echo "<p>Email verified successfully.</p>";
                echo "<p>Email verified successfully. Redirecting to home page...</p>";
                header("Refresh: 3; url=index.php");
                //header("Location: index.php?message=Email verified successfully.");
                exit;
            } else {
                // Display an error message
                echo "<p>Incorrect code. Please try again.</p>";
            }
        }
    } else {
        // Display an error message if the email is not found in the database
        echo "<p>Email not found.</p>";
    }

    // Resend code button
    if (isset($_POST['resend'])) {
        // Generate a new 6-digit verification code
        $new_code = rand(100000, 999999);

        // Update the verification code in the database for the specific email
        $from_email = $row['from_email'];
        $update_code_sql = "UPDATE emails SET verification_code='$new_code' WHERE from_email='$from_email' AND to_email='$to_email' AND verification_code='$code'";
        mysqli_query($conn, $update_code_sql);

    // Send the new code to the email address
    $to = $to_email;
    $subject = "New Verification Code";
    $message = "Your new verification code is: $new_code";
    $headers = "From: no-reply@example.com";

    if (mail($to, $subject, $message, $headers)) 
    {
        echo "<p>Code sent to $to</p>";
    } 
    else 
    {
    echo "<p>Failed to send code</p>";
    }

    // Update the URL parameters with the new code
    header("Location: verify.php?to=$to&code=$new_code");
    


            // Display a message that the code has been sent
             "To: " . $to . "<br>";
             "Code: " . $code . "<br>";
             "Entered Code: " . $entered_code . "<br>";
             "New Code: " . $new_code . "<br>";
             "<p>Verification code sent to $to.</p>";
        }
     
    else 
    {
        // Display an error message if the email is not found in the database
        echo "<p></p>";
    }


}

?>

<!-- Input field for the verification code -->


<form action="" method="post">
<link rel="stylesheet" href= "stylecp.css"  >
<p>verification code has been sent to <?php echo $to_email; ?> . </p>

  <label for="code">Enter code:</label>
  <input type="text" id="code" name="code" >
  <!-- Submit button -->
  <input type="submit" name="submit" value="Submit">
  <!-- Resend code button -->
  <input type="submit " name="resend" value="Resend Code">
</form>
