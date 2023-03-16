<?php
// Connect to the database
$host = "future-db.csc2uqqp87hs.ap-south-1.rds.amazonaws.com";
$username = "admin";
$password = "adminadmin";
$dbname = "email_db";

$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data
    $name = validate_input($_POST["name"]);
    $to = validate_input($_POST["to"]);
    $from = validate_input($_POST["from"]);
    $body = validate_input($_POST["emailbody"]);
    $scheduled_time = validate_input($_POST["scheduleDate"]);

    // Generate a 6 digit random number
    $code = rand(100000, 999999);

    // Send the email with the code
    $subject = "Verification Code";
    $message = "Your verification code is: " . $code;
    $headers = "From: " . $from;
    mail($to, $subject, $message, $headers);

    // Insert the data into the "emails" table
    $sql = "INSERT INTO emails (to_email, from_email, body, schedule_date, name, verification_code, status) VALUES (?, ?, ?, ?, ?, ?, 'ADDED')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $to, $from, $body, $scheduled_time, $name, $code);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirect to the verification page
        header("Location: verify.php?to=" . $to . "&code=" . $code);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Function to validate form inputs
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data); 
    $data = htmlspecialchars($data);
    return $data;
}
?>
