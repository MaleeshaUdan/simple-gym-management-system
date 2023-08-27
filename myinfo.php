<?php

        session_start();

        if (!isset($_SESSION["username"])) {
            echo '<script>alert("Session expired or not logged in."); window.location.href = "index.php";</script>';
            exit;
        }

        require_once "dbconfig.php";

        $username = $_SESSION["username"];

        $selectQuery = "SELECT * FROM info WHERE username = '$username'";
        $result = $conn->query($selectQuery);

        $userDetails = [];
        if ($result->num_rows > 0) {
            $userDetails = $result->fetch_assoc();
        }
        $excludedColumns = ["id", "username"];

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gym Mangement System</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./dashboard.php" class="text-nowrap logo-img">
            <img src="../assets/images/logos/dark-logo.svg" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./dashboard.php" aria-expanded="false">
                <span>
                  <i class="ti ti-pencil"></i>
                </span>
                <span class="hide-menu">Update Info</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./myinfo.php" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">My Info</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./schedule.php" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">My Schedule</span>
              </a>
            </li>
            
            
          </ul>
          <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
              <?php

                    if (isset($_SESSION["username"])) {
                        $username = $_SESSION["username"];
                        echo "Welcome $username";
                    } else {
                        echo "Welcome!";
                    }
                ?>  
              </div>
              <div class="unlimited-access-img">
                <img src="../assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
              </div>
            </div>
            <a href="logout.php" class="btn btn-danger">Logout</a>
          </div>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      
      <!--  Header End -->
            <div class="container mt-5">
            <h2>User Details</h2>
            <table class="table">
                <tbody>
                <?php foreach ($userDetails as $column => $value) {
                    if (!in_array($column, $excludedColumns)) {
                ?>
                    <tr>
                        <th><?php echo ucwords(str_replace("_", " ", $column)); ?></th>
                        <td><?php echo $value; ?></td>
                    </tr>
                <?php
                    }
                } ?>
                </tbody>
            </table>
            <?php if ($result->num_rows > 0) { ?>
                    <button id="deleteButton" class="btn btn-danger">Delete User Data</button>
                <?php } ?>
            </div>  

            <script>
                document.getElementById("deleteButton").addEventListener("click", function() {
                    if (confirm("Are you sure you want to delete your user data?")) {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {  
                                if (xhr.status === 200) {
                                    if (xhr.responseText === "success") {
                                        //alert("User data deleted successfully.");
                                        window.location.href = "myinfo.php"; 
                                    } else {
                                        //alert("Failed to delete user data. Please try again.");
                                        window.location.href = "myinfo.php";
                                    }
                                } else {
                                    alert("An error occurred while communicating with the server.");
                                }
                            }
                        };
                        xhr.open("POST", "", true); 
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.send("delete_user_data=true");
                    }
                });
            </script>


                <?php
                //require_once "dbconfig.php"; 

                if (isset($_POST["delete_user_data"])) {
                    session_start();
                    
                    
                    $username = $_SESSION["username"];

                
                    $deleteQuery = "DELETE FROM info WHERE username = '$username'";
                    
                    if ($conn->query($deleteQuery) === TRUE) {
                        echo "success"; 
                    } else {
                        echo "error"; 
                    }
                    $deleteQuery2 = "DELETE FROM bmi WHERE username = '$username'";
                    if ($conn->query($deleteQuery2) === TRUE) {
                      echo "success"; 
                  } else {
                      echo "error"; 
                  }

                    $conn->close();
                }
            ?>

           
                
            
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by <a href="#" target="_blank" class="pe-1 text-primary text-decoration-underline">Group XX</a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>
  <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../assets/js/dashboard.js"></script>
</body>

</html>