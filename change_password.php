<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_portal";

// Connect to the database

$conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
$REG_NO = $_SESSION['REGNO'];
$Password = $_POST["old_password"];
$New_password = $_POST["new_password"];
$Old_password = $_POST["confirm_password"];

    $query = "SELECT * FROM validation WHERE reg_no = '$REG_NO'";

    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $password = $row["PASSWORD"]; 
    if ($Password === $password)
    {
        if ($New_password === $Old_password){
        $updateQuery = "UPDATE validation SET PASSWORD = '$New_password' WHERE reg_no = '$REG_NO'";
        mysqli_query($conn, $updateQuery);
        
        header("Location: portal.php");
        exit();
        }
        else{
            echo "Password do not match.";
        }
    }
    else {
        // Invalid old password
        echo "Invalid  Old Password.";
    }
   
}
         // Close the database connection
         mysqli_close($conn);
        }
    ?>
    
<html>
    <head>
        <title>PASSWORD RESET</title>
        <link rel="stylesheet" href="portal.css">
        <script src="portal.js"></script>

    </head>
    <body>
        <div class="container">
          <div class="nav" >
            <i class="logo" href="\">
                <img src="logo.png" 
                width="60" 
             height="60" 
             />
            </i>
                <pre>
            MAXWELL
            UNIVERSITY
            </pre>
          </div> <br>
          <hr><br>
          <form method="post">
          <label for="new_password">Old Password:</label>
          <div class="password-wrapper">
          <input type="password" id="old_password" name="old_password" required  placeholder="Old Password">
          <span id="show" onclick="togglePasswordVisibility()"></span>
                </div><br>
                <label for="new_password">New Password:</label>
          <div class="password-wrapper">
          <input type="password" id="new_password" name="new_password" required  placeholder="New Password">
          <span id="show" onclick="togglePasswordVisibility()"></span>
                </div><br>
          <label for="new_password">Confirm Password:</label>
          <div class="password-wrapper">
          <input type="password" id="confirm_password" name="confirm_password" required  placeholder="Confirm Password">
          <span id="showPassword" onclick="PasswordVisibility()"></span>
                </div><br>
            <button type="submit" value="Reset Password" id="stif">Submit</button>
            <div class="pa">
            <a href="portal.php">
          <i ><p>Login</p></i></a>
          </div><br><br>
          </form>
          <script>
             function togglePasswordVisibility() {
             var passwordInput = document.getElementById("new_password");
             var showPasswordIcon = document.getElementById("show");
    
             if (passwordInput.type === "password") {
            passwordInput.type = "text";
              showPasswordIcon.style.backgroundImage = 'url("hide.png")'; /* Replace with your actual image path */
           } else {
        passwordInput.type = "new_password";
        showPasswordIcon.style.backgroundImage = 'url("show.jpeg")'; /* Replace with your actual image path */
    }
  }

 
          </script>
    </body>
</html>