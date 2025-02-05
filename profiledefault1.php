<html>
    <head>
        <title>Profile</title>
        <link href='profile.css' rel='stylesheet'>
        <style>
            table{
                width: 400px;
            }
            th {
              padding-left: 10px;
              color: black;
            }
            
            td {
              padding-left: 40px;
            }
          </style>
          
    </head>
    <body>
        <div class="general">
            <div class="title">
                <p>Dashboard</p>
            </div>
            <div class="full">
                <div class="basic">
                    <p>Basic information</p>
                </div>
                <div class="information">
                    <div class="p">
                    <img src="#profilepicture" width="100" height="100"/>
                    <hr class="hori">
                    </div>
                    
                    <table class="s">
                        <tr>
                            <th>Reg.No</th>
                            <td>#Reg</td>
                          </tr>
                          <tr>
                            <th>Name</th>
                            <td>#Name</td>
                          </tr>
                          <tr>
                            <th>ID No</th>
                            <td>#Id</td>
                          </tr>
                          <tr>
                            <th>Gender</th>
                            <td>#Gender</td>
                          </tr>
                    </table>
                    
                    <table class="s">
                        <tr>
                          <th>Address</th>
                          <td>#Address</td>
                        </tr>
                        <tr>
                          <th>Email</th>
                          <td>#Email</td>
                        </tr>
                        <tr>
                          <th>Date Of Birth </th>
                          <td>#Date</td>
                        </tr>
                        <tr>
                          <th>Campus</th>
                          <td>#Campus</td>
                        </tr>
                      </table>
                      </div>
                      <div class="line">
                      <hr>
                      <hr>
                      <hr>
                  
                      <button type="submit" id="seff" onclick="generateToken()">Get Catering Token</button>
                      <p id= "sefff">Current Catering Token:</p>
                      <p id="token-display"></p>
                      <div id="token-container">
                        </div>
                      <button type="submit" id="sef">Get Academic Calender</button>
                    </div>
                    
            </div>
           
        </div>
        <script>
        function generateToken() {
            // Generate a random six-digit token
            const token = Math.floor(100000 + Math.random() * 900000);

            // Display the token
            document.getElementById("token-display").textContent = token;
        }
    </script>
    </body>