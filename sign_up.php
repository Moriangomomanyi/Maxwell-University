<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_portal";

// Connect to the database

$conn = mysqli_connect($servername, $username, $password, $database);
    // Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
     // Retrieving the portal creation credentials from the form
     $Name = strtoupper($_POST["name"]);
     $Email = strtoupper($_POST["email"]);
     $RegNo = strtoupper($_POST["RegNumber"]);
     $County =strtoupper($_POST["county"]);
     $Address = $_POST["address"];
     $Gender =strtoupper($_POST["gender"]);
     $Date =$_POST["date"];
     $ID =$_POST["id"];
     $Campus =strtoupper($_POST["campus"]);
     $password = $_POST["password1"];
     $password1 = $_POST["password2"];

     if ($password === $password1)
     {
      // Function to create a portal page for the user with the default code
 function createPortalPage($Name,$RegNo, $Default_code) {
    $portalContent = str_replace (['$Name'],[$Name],$Default_code);
    $cleanedRegNo = preg_replace("/[^a-zA-Z0-9]/", "", $RegNo);
    $portalFileName = strtoupper(substr($cleanedRegNo, -14)) . "_profile.php";
    $file = fopen($portalFileName, "w");
    fwrite($file, $portalContent);
    fclose($file);

    return $portalFileName;
}
 // Retrieve the default code from the database
 function getDefaultCodeFromDatabase($conn) {
    $sql = "SELECT Default_code FROM user_create";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $defaultCodeFile = $row["Default_code"]; // Get the file name from the Default_code column

        // Read the content of the file
        $filePath = __DIR__ . '/' . $defaultCodeFile;
        $defaultCode = file_get_contents($filePath);

        return $defaultCode;
    }

    return null;
}


// Get the default code
$Default_code = getDefaultCodeFromDatabase($conn);

if (!empty($Default_code)) {
    // Start a transaction
    mysqli_begin_transaction($conn);

    // Create the portal page for the user with the default code
    $portalFileName = createPortalPage($Name,$RegNo, $Default_code);
    
    // Insert the portal file path into the profile table
    $portalFilePath = mysqli_real_escape_string($conn, $portalFileName);
   

    // Insert the portal file path into the validation table$picturePath
    $portalSql = "INSERT INTO validation (reg_no, PASSWORD, email, portal) VALUES ('$RegNo','$password', '$Email','$portalFilePath')";
    
    // Perform insertions and check for success
    $validationInsertSuccess = mysqli_query($conn, $portalSql);
    
    // Commit or rollback the transaction based on success of both insertions
    if ($validationInsertSuccess) {
        mysqli_commit($conn);
        $portalCreationMessage = "Portal created successfully.";
    } else {
        mysqli_rollback($conn);
        $portalCreationMessage = "Error creating portal. Transaction rolled back.";
    }
} else {
    $portalCreationMessage = "Default code not found!";
}

     function insertPictureIntoPortal($portalFileName, $picturePath) {
        // Read the content of the portal file
        $portalContent = file_get_contents($portalFileName);
    
        // Define a placeholder in the portal content for the picture
    
        // Replace the placeholder with the HTML code for the image
        $portalContentWithPicture = str_replace(['$ProfilePicture'], [$picturePath] , $portalContent);
    
        // Write the modified content back to the portal file
        file_put_contents($portalFileName, $portalContentWithPicture);
    }
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        // Retrieve the details of the uploaded file
        $fileTmpPath = $_FILES['profilePicture']['tmp_name'];
        $fileName = $_FILES['profilePicture']['name'];
        $fileSize = $_FILES['profilePicture']['size'];
        $fileType = $_FILES['profilePicture']['type'];
    
        // Extract the first three initials of the username
        $cleanedRegNo = preg_replace("/[^a-zA-Z0-9]/", "", $RegNo);
        $initials = strtoupper(substr($cleanedRegNo,-14));
    
        // Generate the new file name with the initials and file extension
        $newFileName = $initials . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
    
        // Move the uploaded file to a permanent location with the new file name
        $destinationPath = 'C:\wamp64\www\Maxwell-University/' . $newFileName; // Set your desired destination directory
        
        if (move_uploaded_file($fileTmpPath, $destinationPath)) {
            // Store the file path in the `pictures` column of the `resett` table
            $picturePath = $newFileName; // Escape the path to prevent SQL injection
            
            $sql = "INSERT INTO resett (EMAIL, name,pictures) VALUES ('$Email','$Name','$picturePath')";
            $profileSql = "INSERT INTO profile (EMAIL, Name, PICTURES, reg_no, Date, County, Gender, Campus, ID, Address) VALUES ('$Email','$Name','$picturePath','$RegNo','$Date','$County','$Gender','$Campus','$ID','$Address')";
           
            $profileInsertSuccess = mysqli_query($conn, $profileSql);
            $ressetInsertSuccess = mysqli_query($conn, $sql);
            if ($profileInsertSuccess && $ressetInsertSuccess) {
                mysqli_commit($conn);
                // Picture path inserted successfully
                $pictureMessage = "Picture path inserted successfully.";
      // Call the insertPictureIntoPortal function
      
      insertPictureIntoPortal($portalFileName, $picturePath);
              
            } else {
                mysqli_rollback($conn);
                // Error in SQL execution
                $pictureMessage = "Error executing SQL query: " . mysqli_error($conn);
            }
        } else {
            // Error moving the uploaded file
            $pictureMessage = "Error moving the uploaded file.";
        }
    }
   
     }
     else{
        echo "Password do not match.";
    }
 

         // Close the database connection
mysqli_close($conn);
    }
?>

<html>
    <head>
        <title>STUDENT PORTAL</title>
        <link rel="stylesheet" href="portal.css">

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
          <h3>Create a student portal</h3>
          <form name="myForm"id="form" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
            <label for="terry" >Name</label><br><br>
            <input type="text"name="name" id="name" placeholder="e.g EMMANUEL MORIANGO MOMANYI"><br><br>
            <label for="terry" >Email</label><br><br>
            <input type="text"name="email" id="email" placeholder="e.g EMMOMANYI@KABARAK.AC.KE"><br><br>
            <label for="terry" >Reg.Number</label><br><br>
            <input type="text"name="RegNumber" id="stiff" placeholder="Reg.Number"><br><br>
            <label for="terry" >Id No</label><br><br>
            <input type="text"name="id" id="idno"placeholder="ID Number"><br><br>
            <label for="terry" >Gender</label><br><br>
            <input type="text"name="gender" id="gender" placeholder="Male/Female"><br><br>
            <label for="terry" >Date of Birth</label><br><br>
            <input type="text"name="date" id="date" placeholder="Date Of Birth"><br><br>
            <label for="terry" >Campus</label><br><br>
            <input type="text"name="campus" id="campus" placeholder="Campus"><br><br>
            <label for="terry" >Address</label><br><br>
            <input type="text"name="address" id="address" placeholder="Address"><br><br>
            <label for="terry" >County</label><br><br>
            <input type="text"name="county" id="county" placeholder="County"><br><br>
            <label for="terry" >Password</label><br><br>
            <div class="password-wrapper">
            <input type="password"name="password1" id="password1" placeholder="Password">
            <span id="showPassword" onclick="togglePasswordVisibility()"></span>
                </div><br>
                <label for="terry" >Confirm Password</label><br><br>
            <div class="password-wrapper">
            <input type="password"name="password2" id="password2" placeholder=" Confirm Password">
            <span id="showPassword" onclick="togglePasswordVisibility()"></span>
                </div><br>
            <label for="terry">Profile Picture:</label>
            <input type="file" id="profilePicture" name="profilePicture">
  
          
            <button type="submit" id="stif">Sign Up</button><br>
            <div class="pa">
            <a href="portal.php">
          <i ><p>Login</p></i></a>
          </div><br><br>
          </form>
          </div>
        <div class="end">
            <p>2023 Designed by Maxwell Systems</p>
        </div>
        <script src="portal.js"></script>

    </body>
</html>
