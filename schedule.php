<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["username"])) {
    echo '<script>alert("Session expired or not logged in."); window.location.href = "index.php";</script>';
    exit;
}

require_once "dbconfig.php";

$username = $_SESSION["username"];

// Check if user data exists
$readOnly = "readonly";
$disabled = "disabled";
$checkQuery = "SELECT * FROM info WHERE username = '$username'";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    $readOnly = "";
    $disabled = "";
}

// Retrieve BMI data and calculate condition and suggestion
$bmiValue = null;
$condition = "";
$suggestion = "";
$bmiQuery = "SELECT bmi_value FROM bmi WHERE username = '$username'";
$bmiResult = $conn->query($bmiQuery);

if ($bmiResult->num_rows > 0) {
    $bmiRow = $bmiResult->fetch_assoc();
    $bmiValue = $bmiRow["bmi_value"];

    if ($bmiValue < 18.5) {
        $condition = "Underweight";
        $suggestion = "Weight Gain";
    } elseif ($bmiValue >= 18.5 && $bmiValue <= 24.9) {
        $condition = "Healthy Weight";
        $suggestion = "Can select any (Weight gain/Weight loss/Fitness maintain)";
    } elseif ($bmiValue >= 25.0 && $bmiValue <= 29.9) {
        $condition = "Overweight";
        $suggestion = "Weight Loss";
    } else {
        $condition = "Obesity";
        $suggestion = "Weight Loss";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $height = $_POST["height"];
    $weight = $_POST["weight"];

    // Update or insert BMI data
    $bmiQuery = "SELECT * FROM bmi WHERE username = '$username'";
    $bmiResult = $conn->query($bmiQuery);

    if ($bmiResult->num_rows > 0) {
        // Data found, perform update
        $update_sql = "UPDATE bmi SET height = $height, weight = $weight WHERE username = '$username'";
        $update_result = $conn->query($update_sql);

        if ($update_result) {
            $updateMessage = "Height and weight updated successfully!";
        } else {
            $updateMessage = "Error updating record: " . $conn->error;
        }
    } else {
        // Data not found, perform insert
        $insert_sql = "INSERT INTO bmi (username, height, weight) VALUES ('$username', $height, $weight)";
        $insert_result = $conn->query($insert_sql);

        if ($insert_result) {
            $insertMessage = "Height and weight inserted successfully!";
        } else {
            $insertMessage = "Error inserting record: " . $conn->error;
        }
    }
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
              <form method='POST' action='schedule.php'>
                  <div class="row">
                      <div class="col-md-6 mb-3">
                          <label for="height" class="form-label">Height (cm)</label>
                          <input type="number" step="0.01" min="0" class="form-control" id="height" name="height" placeholder="cm" required <?php echo $readOnly; ?>>
                      </div>
                      <div class="col-md-6 mb-3">
                          <label for="weight" class="form-label">Weight (Kg)</label>
                          <input type="number" step="0.01" min="0" class="form-control" id="weight" name="weight" placeholder="Kg" required <?php echo $readOnly; ?>>
                      </div>
                  </div>
                  <button type="submit" class="btn btn-success" <?php echo $disabled; ?>>Update</button>
              </form>
              <div class="mt-3">
                  <?php if (isset($bmiValue)) { ?>
                  <h2>BMI Details</h2>
                  <table class="table">
                      <tbody>
                          <tr>
                              <th>Condition</th>
                              <td><?php echo $condition; ?></td>
                          </tr>
                          <tr>
                              <th>Suggestion</th>
                              <td><?php echo $suggestion; ?></td>
                          </tr>
                          <tr>
                              <th>Your BMI value is</th>
                              <td><?php echo number_format($bmiValue, 2); ?></td>
                          </tr>
                      </tbody>
                  </table>
                  <?php } ?>
              </div>
          </div>


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