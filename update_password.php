<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["token"], $_POST["new_password"])) {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "student_portal";

    // Get the token and new password from the form
    $token = $_POST["token"];
    $newPassword = $_POST["new_password"];

    // Connect to the database
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the token exists in the resett table
    $query = "SELECT * FROM resett WHERE tokens = '$token'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Token is valid, update the password in the validation table
        $row = mysqli_fetch_assoc($result);
        $email = $row["EMAIL"];
    

        $updateQuery = "UPDATE validation SET PASSWORD = '$newPassword' WHERE email = '$email'";
        mysqli_query($conn, $updateQuery);

        // Password updated successfully
        echo "Your password has been successfully reset. Login In Below";
        header("Location: portal.php");
    } else {
        // Invalid token
        echo "Invalid token.";
    }

    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>
