<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_portal";

// Connect to the database

$conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $REG_NO = $_SESSION['REGNO'] ;

    $sql = "SELECT * FROM profile WHERE reg_no = '$REG_NO'";
    $result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    // Loop through the matching rows in the result set
    while ($row = mysqli_fetch_assoc($result)) {
        // Access and use the data in each matching row
        $Name = strtoupper($row["Name"]);
        $regNo = strtoupper($row["reg_no"]);
        $Id = strtoupper($row["ID"]);
        $Email = strtoupper($row["Email"]);
        $Campus = strtoupper($row["Campus"]);
        $Gender = strtoupper($row["Gender"]);
        $Date = strtoupper($row["Date"]);
        $Picture = strtoupper($row['PICTURES']);
        $Address = strtoupper($row["Address"]);
    }
    
    function createPortalPage($Name, $regNo, $Id, $Email, $Campus,$Address,$Gender,$Date,$Picture, $Default_code) {
        $portalContent = str_replace(
            ['#Name', '#Reg', '#Id', '#Email','#Campus','#Address','#Gender','#Date','#profilepicture'],
            [$Name,  $regNo, $Id, $Email, $Campus,$Address,$Gender,$Date,$Picture],
            $Default_code
        );
        $cleanedRegNo = preg_replace("/[^a-zA-Z0-9]/", "", $regNo);
        $portalFileName = substr($cleanedRegNo, -14) . ".php";
        $file = fopen($portalFileName, "w");
        fwrite($file, $portalContent);
        fclose($file);
    
        return $portalFileName;
    }
     // Retrieve the default code from the database
     function getDefaultCodeFromDatabase($conn) {
        $sql = "SELECT defaultcodes FROM links";
        $result = mysqli_query($conn, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $defaultCodeFile = $row["defaultcodes"]; // Get the file name from the Default_code column
    
            // Read the content of the file
            $filePath = __DIR__ . '/' . $defaultCodeFile;
            $defaultCode = file_get_contents($filePath);
    
            return $defaultCode;
        }
    
        return null;
    }
    // Get the default code
  $Default_code = getDefaultCodeFromDatabase($conn);

  $portalinfo = createPortalPage($Name, $regNo, $Id, $Email, $Campus,$Address,$Gender,$Date,$Picture, $Default_code);
  function insertInfoIntoPortal($portalcode, $portalinfo) {
    // Read the content of the portal file
    $portalContent = file_get_contents($portalcode);
    // Replace the placeholder with the HTML code for the image
    $portalContentWithPicture = str_replace(['$info'], [$portalinfo] , $portalContent);

    // Write the modified content back to the portal file
    file_put_contents($portalcode, $portalContentWithPicture);
}
function getportalCodeFromDatabase($conn,$regNo) {
    $sql = "SELECT portal FROM validation WHERE reg_no = '$regNo'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $defaultCodeFile = $row["portal"]; // Get the file name from the Default_code column

        return $defaultCodeFile;
    }

    return null;
}
$portalcode = getportalCodeFromDatabase($conn, $regNo);

if (!empty($portalcode)) {
    mysqli_begin_transaction($conn);

    // insert the info into the portal page for the user with the default code
    insertInfoIntoPortal($portalcode,  $portalinfo);
}
else {
    $infoinsertionMessage = "Default code not found!";
}
} else {
    echo "No data found in the 'profile' table for reg_no = '$REG_NO'.";
}
  // Close the database connection
  mysqli_close($conn);
    ?>