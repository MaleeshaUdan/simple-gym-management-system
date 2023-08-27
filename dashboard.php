<?php

session_start();

if (!isset($_SESSION["username"])) {
    echo '<script>alert("Session expired or not logged in."); window.location.href = "index.php";</script>';
    exit;
}

require_once "dbconfig.php"; 

// Check if data is already available for the username
$username = $_SESSION["username"];
$checkQuery = "SELECT * FROM info WHERE username = '$username'";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    // Data found, make user input fields read-only
    $readOnly = "readonly";
    $disabled = "disabled";
} else {
    // Data not found, user input fields are editable
    $readOnly = "";
    $disabled = "";
}

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
                <h2>User Registration</h2>
                <form id="userForm"  method="POST">
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="fullName" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]{10}" required>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date Of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male" required>
                    <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="Female" required>
                    <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="emergencyContact" class="form-label">Emergency Contact Number</label>
                    <input type="tel" class="form-control" id="emergencyContact" name="emergencyContact" pattern="[0-9]{10}" required>
                </div>
                <div class="mb-3">
                    <label for="fitnessGoals" class="form-label">Fitness Goals</label>
                    <select class="form-control" id="fitnessGoals" name="fitnessGoals" required>
                    <option value="" disabled selected>Select your fitness goal</option>
                    <option value="Weight Gain">Weight Gain</option>
                    <option value="Weight Loss">Weight Loss</option>
                    <option value="Maintain Fitness">Maintain Fitness</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fitnessLevel" class="form-label">Fitness Level</label>
                    <select class="form-control" id="fitnessLevel" name="fitnessLevel" required>
                    <option value="" disabled selected>Select your fitness level</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advance">Advance</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="medicalHistory" class="form-label">Medical History</label>
                    <textarea class="form-control" id="medicalHistory" name="medicalHistory" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" <?php echo $disabled; ?> >Submit</button>
                <button type="reset" class="btn btn-danger" <?php echo $disabled; ?>>Reset</button>
                </form>
            </div>
            <?php
                  require_once "dbconfig.php"; 

                  if ($_SERVER["REQUEST_METHOD"] === "POST") {
                      
                      $fullName = $_POST["fullName"];
                      $email = $_POST["email"];
                      $phoneNumber = $_POST["phoneNumber"];
                      $dob = $_POST["dob"];
                      $gender = $_POST["gender"];
                      $address = $_POST["address"];
                      $emergencyContact = $_POST["emergencyContact"];
                      $fitnessGoals = $_POST["fitnessGoals"];
                      $fitnessLevel = $_POST["fitnessLevel"];
                      $medicalHistory = $_POST["medicalHistory"];

                      
                      $username = $_SESSION["username"];

                      $checkQuery = "SELECT email FROM info";

                      if ($conn->query($checkQuery) === TRUE) {
                          
                          echo '<script>alert("Email address already exsist!"); window.location.href = "dashboard.php";</script>';
                      } 


                      $insertQuery = "INSERT INTO info (username, fullname, email, phonenumber, DOB, gender, address, emerphonenum, fitnessGoal, fitnessLevel, medicalHistory) 
                                      VALUES ('$username', '$fullName', '$email', '$phoneNumber', '$dob', '$gender', '$address', '$emergencyContact', '$fitnessGoals', '$fitnessLevel', '$medicalHistory')";

                      if ($conn->query($insertQuery) === TRUE) {
                          
                          echo '<script>alert("Data inserterd successfully"); window.location.href = "dashboard.php";</script>';
                      } else {
                        echo '<script>alert("Failed to enter data. Please try again!"); window.location.href = "dashboard.php";</script>';
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