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
                        $username = $_SESSION["username"];
                        echo "Welcome<br>";
                        echo $username;
                    } else {
                        echo "Welcome!";
                    }
              ?>  
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
          <div class="container mt-5">
            <?php
            require_once "dbconfig.php";
            $username = $_SESSION['username'];
      $sql = "SELECT gender, fitnessGoal, fitnessLevel FROM info WHERE username = '$username'";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $gender = $row['gender'];
              $fitnessGoal = $row['fitnessGoal'];
              $fitnessLevel = $row['fitnessLevel'];

              if ($gender == 'Male' && $fitnessGoal == 'Weight Loss' && $fitnessLevel == 'Beginner') {
                  echo "<table border='1'>";
                  echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                  echo "<tr><td>Warmup and Stretching</td><td></td></tr>";
                  echo "<tr><td>Cardio 40min</td><td></td></tr>";
                  echo "<tr><td>Kettlebell swings x 25 x3</td><td></td></tr>";
                  echo "<tr><td>Jumping Jacks x 25 x3</td><td></td></tr>";
                  echo "<tr><td>(rest 1min)</td><td></td></tr>";
                  echo "<tr><td>Leg extension (10-15) x 3</td><td></td></tr>";
                  echo "<tr><td>Leg press (10-15) x 3</td><td></td></tr>";
                  echo "<tr><td>Dumbbell deadlift 12 x 3</td><td></td></tr>";
                  echo "<tr><td>Dumbbell pullover 15 x 3</td><td></td></tr>";
                  echo "<tr><td>Chest press 15 x 3</td><td></td></tr>";
                  echo "<tr><td>Lat pulldown 15 x 3</td><td></td></tr>";
                  echo "<tr><td>Shoulder press 15 x 3</td><td></td></tr>";
                  echo "<tr><td>Cable curl 15 x 3</td><td></td></tr>";
                  echo "<tr><td>Overhead triceps extension 15 x 3</td><td></td></tr>";
                  echo "<tr><td>Triceps-press down 20 x 3</td><td></td></tr>";
                  echo "<tr><td>Cool down</td><td></td></tr>";
                  echo "<tr><td>Static stretches 15-30sec</td><td></td></tr>";
                  echo "<tr><td>Deep breathing 30-40sec</td><td></td></tr>";
                  echo "<tr><td>Foam rolling 30sec</td><td></td></tr>";
                  echo "</table>";
                } elseif($gender == 'Male' && $fitnessGoal == 'Weight Loss' && $fitnessLevel == 'Intermediate'){
                    echo "<h2>Workout Schedule</h2>";

                    $days = array(
                        "Day-01" => array(
                            "Warmup and Stretching",
                            "Cardio 40min",
                            "Kettlebell swings x 25 x3",
                            "Jumping Jacks x 25 x3",
                            "(rest 1min)",
                            "Dumbbell pullover (15-20) x 3",
                            "Chest press (15-20) x 3",
                            "Decline dumbbell press (15-20) x 3",
                            "Decline cable flies (15-20) x 3",
                            "Lat pull down (15-20) x 3",
                            "Seated cable row (15-20) x 3",
                            "One-arm dumbbell row (15-20) x 3",
                            "Triceps press down (15-20) x 3",
                            "Triceps extension (15-20) x 3",
                            "Wrist curl (15-20) x 3",
                            "Cool down",
                            "Static stretches 15-30sec",
                            "Deep breathing 30-40sec",
                            "Foam rolling 30sec",
                        ),
                        "Day-02" => array(
                            "Warmup and Stretching",
                            "Cardio 40min",
                            "Kettlebell swings x 25 x3",
                            "Jumping Jacks x 25 x3",
                            "(rest 1min)",
                            "Leg extension (15-20) x 3",
                            "Lunges (15-20) x 3",
                            "Squat (15-20) x 3",
                            "Leg press (15-20) x 3",
                            "Deadlift (15-20) x 3",
                            "Shoulder press (15-20) x 3",
                            "Front press (15-20) x 3",
                            "Back press (15-20) x 3",
                            "Feature curl (15-20) x 3",
                            "Barbell curl (15-20) x 3",
                            "Cool down",
                            "Static stretches 15-30sec",
                            "Deep breathing 30-40sec",
                            "Foam rolling 30sec",
                        ),
                        "Day-03" => array(
                            "Rest",
                        ),
                    );
              
                    foreach ($days as $day => $exercises) {
                        echo "<h3>$day</h3>";
                        echo "<table border='1'>";
                        echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                        foreach ($exercises as $exercise) {
                            echo "<tr><td>$exercise</td><td></td></tr>";
                        }
                        echo "</table>";
                    }
                }
                elseif($gender == 'Male' && $fitnessGoal == 'Weight Loss' && $fitnessLevel == 'Advanced'){
                    
                                echo "<h2>Workout Schedule</h2>";

                  // Create a workout schedule here
                  $days = array(
                      "Day-01" => array(
                          "Warmup and Stretching",
                          "Cardio 40min",
                          "Kettlebell swings x 25 x3",
                          "Jumping Jacks x 25 x3",
                          "(rest 1min)",
                          "Bent arm pullover (15-20) x 3",
                          "Chest press (15-20) x 3",
                          "Incline chest press (15-20) x 3",
                          "Dumbbell flies (15-20) x 3",
                          "Decline cable flies (15-20) x 3",
                          "Decline dumbbell press (15-20) x 3",
                          "Tri-pulldown (15-20) x 3",
                          "Overhead tri-pulldown (15-20) x 3",
                          "Triceps dips (15-20) x 3",
                          "Wrist curl (15-20) x 3",
                          "Cool down",
                          "Static stretches 15-30sec",
                          "Deep breathing 30-40sec",
                          "Foam rolling 30sec",
                      ),
                      "Day-02" => array(
                          "Rest",
                      ),
                      "Day-03" => array(
                          "Rest",
                      ),
                  );

                  // Display the workout schedule
                  foreach ($days as $day => $exercises) {
                      echo "<h3>$day</h3>";
                      echo "<table border='1'>";
                      echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                      foreach ($exercises as $exercise) {
                          echo "<tr><td>$exercise</td><td></td></tr>";
                      }
                      echo "</table>";
                  }
              }
              elseif($gender == 'Male' && $fitnessGoal == 'Weight Gain' && $fitnessLevel == 'Beginner'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Leg extension (6-10) x 3",
                        "Leg press (6-10) x 3",
                        "Dumbbell deadlift (6-10) x 3",
                        "Dumbbell pullover (6-10) x 3",
                        "Chest press (6-10) x 3",
                        "Lat pulldown (6-10) x 3",
                        "Shoulder press (6-10) x 3",
                        "Cable curl (6-10) x 3",
                        "Overhead triceps extension (6-10) x 3",
                        "Triceps-press down (6-10) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }


              }
              elseif($gender == 'Male' && $fitnessGoal == 'Weight Gain' && $fitnessLevel == 'Intermediate'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Dumbbell pullover (6-10) x 3",
                        "Chest press (6-10) x 3",
                        "Decline dumbbell press (6-10) x 3",
                        "Decline cable flies (6-10) x 3",
                        "Lat pull down (6-10) x 3",
                        "Seated cable row (6-10) x 3",
                        "One-arm dumbbell row (6-10) x 3",
                        "Triceps press down (6-10) x 3",
                        "Triceps extension (6-10) x 3",
                        "Wrist curl (6-10) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }

              }

              elseif($gender == 'Male' && $fitnessGoal == 'Weight Gain' && $fitnessLevel == 'Advanced'){

                              echo "<h2>Workout Schedule</h2>";

                  // Create a workout schedule here
                  $days = array(
                      "Day-01" => array(
                          "Warmup and Stretching",
                          "Cardio 40min",
                          "Kettlebell swings x 25 x3",
                          "Jumping Jacks x 25 x3",
                          "(rest 1min)",
                          "Bent arm pullover (8-12) x 3",
                          "Chest press (8-12) x 3",
                          "Incline chest press (8-12) x 3",
                          "Dumbbell flies (8-12) x 3",
                          "Decline cable flies (8-12) x 3",
                          "Decline dumbbell press (8-12) x 3",
                          "Tri-pulldown (8-12) x 3",
                          "Overhead tri-pulldown (8-12) x 3",
                          "Triceps dips (8-12) x 3",
                          "Wrist curl (8-12) x 3",
                          "Cool down",
                          "Static stretches 15-30sec",
                          "Deep breathing 30-40sec",
                          "Foam rolling 30sec",
                      ),
                      "Day-02" => array(
                          "Rest",
                      ),
                      "Day-03" => array(
                          "Rest",
                      ),
                  );

                  // Display the workout schedule
                  foreach ($days as $day => $exercises) {
                      echo "<h3>$day</h3>";
                      echo "<table border='1'>";
                      echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                      foreach ($exercises as $exercise) {
                          echo "<tr><td>$exercise</td><td></td></tr>";
                      }
                      echo "</table>";
                  }
              }
              elseif($gender == 'Male' && $fitnessGoal == 'Fitness' && $fitnessLevel == 'Beginner'){

                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 20min",
                        "Kettlebell swings x 25 x3",
                        "Rope x 25 x3",
                        "(rest 45sec)",
                        "Barbell clean and press 12 x 3",
                        "Romanian deadlift 12 x 3",
                        "Dumbbell pullover 12 x 3",
                        "Chest press machine 12 x 3",
                        "Lat pull down 12 x 3",
                        "Barbell row 12 x 3",
                        "Front press 12 x 3",
                        "Barbell curl 12 x 3",
                        "Overhead tri extension 12 x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Male' && $fitnessGoal == 'Fitness' && $fitnessLevel == 'Intermediate'){

                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 20min",
                        "Kettlebell swings x 25 x3",
                        "Rope x 25 x3",
                        "(rest 45sec)",
                        "Dumbbell pullover 12 x 3",
                        "Chin-ups 12 x 3",
                        "Chest press 12 x 3",
                        "Lat pulldown 12 x 3",
                        "Barbell row 12 x 3",
                        "Tri-extension 12 x 3",
                        "Tri dips 12 x 3",
                        "Barbell curl 12 x 3",
                        "Preacher curl 12 x 3",
                        "Smith machine 12 x 3",
                        "Leg press 12 x 3",
                        "Calf raises 12 x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Male' && $fitnessGoal == 'Fitness' && $fitnessLevel == 'Advanced'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 20min",
                        "Kettlebell swings x 25 x3",
                        "Rope x 25 x3",
                        "(rest 45sec)",
                        "Chin-ups 12 x 3",
                        "Paddle bar 12 x 3",
                        "Chest press 12 x 3",
                        "Squat 12 x 3",
                        "Leg press 12 x 3",
                        "Deadlift 12 x 3",
                        "Lat pulldown 12 x 3",
                        "One arm 12 x 3",
                        "Barbell clean and press 12 x 3",
                        "Tri dips 12 x 3",
                        "Tri extension 12 x 3",
                        "Barbell curl 12 x 3",
                        "Wrist curl (15-20) x 3",
                        "Calf raises (15-20) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Female' && $fitnessGoal == 'Weight Loss' && $fitnessLevel == 'Beginner'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Dumbbell step-ups (8-10) x 3",
                        "Leg extension (8-10) x 3",
                        "Squat (8-10) x 3",
                        "Cable kick-back (8-10) x 3",
                        "Dumbbell sumo squat (8-10) x 3",
                        "Leg curl (8-10) x 3",
                        "Dumbbell deadlift (8-10) x 3",
                        "Lat pull down (8-10) x 3",
                        "Cable row (8-10) x 3",
                        "Chest press (8-10) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Female' && $fitnessGoal == 'Weight Loss' && $fitnessLevel == 'Intermediate'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Dumbbell step-ups (10-12) x 3",
                        "Leg extension (10-12) x 3",
                        "Squat (10-12) x 3",
                        "Cable kick-back (10-12) x 3",
                        "Dumbbell sumo squat (10-12) x 3",
                        "Leg curl (10-12) x 3",
                        "Dumbbell deadlift (10-12) x 3",
                        "Lat pull down (10-12) x 3",
                        "Cable row (10-12) x 3",
                        "Chest press (10-12) x 3",
                        "Calf raises (15-20)",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Female' && $fitnessGoal == 'Weight Loss' && $fitnessLevel == 'Advanced'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Dumbbell pullover (10-15) x 3",
                        "Dumbbell chest press (10-15) x 3",
                        "Lat pulldown (10-15) x 3",
                        "Barbell row (10-15) x 3",
                        "Smith machine squat (10-15) x 3",
                        "Leg extension (10-15) x 3",
                        "Leg press (10-15) x 3",
                        "Lat curl (10-15) x 3",
                        "Cable kickback (10-15) x 3",
                        "Deadlift (10-15) x 3",
                        "Tri extension (10-15) x 3",
                        "Barbell curl (10-15) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }

              }
              elseif($gender == 'Female' && $fitnessGoal == 'Weight Gain' && $fitnessLevel == 'Beginner'){

                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Dumbbell step-ups (6-10) x 3",
                        "Leg extension (6-10) x 3",
                        "Squat (6-10) x 3",
                        "Cable kick-back (6-10) x 3",
                        "Dumbbell sumo squat (6-10) x 3",
                        "Leg curl (6-10) x 3",
                        "Dumbbell deadlift (6-10) x 3",
                        "Lat pull down (6-10) x 3",
                        "Cable row (6-10) x 3",
                        "Chest press (6-10) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }

              }
              elseif($gender == 'Female' && $fitnessGoal == 'Weight Gain' && $fitnessLevel == 'Intermediate'){

                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Dumbbell step-ups (6-10) x 3",
                        "Leg extension (6-10) x 3",
                        "Squat (6-10) x 3",
                        "Cable kick-back (6-10) x 3",
                        "Dumbbell sumo squat (6-10) x 3",
                        "Leg curl (6-10) x 3",
                        "Dumbbell deadlift (6-10) x 3",
                        "Lat pull down (6-10) x 3",
                        "Cable row (6-10) x 3",
                        "Chest press (6-10) x 3",
                        "Calf raises (8-10)",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Female' && $fitnessGoal == 'Weight Gain' && $fitnessLevel == 'Advanced'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Dumbbell pullover (6-10) x 3",
                        "Dumbbell chest press (6-10) x 3",
                        "Lat pulldown (6-10) x 3",
                        "Barbell row (6-10) x 3",
                        "Smith machine squat (6-10) x 3",
                        "Leg extension (6-10) x 3",
                        "Leg press (6-10) x 3",
                        "Lat curl (6-10) x 3",
                        "Cable kickback (6-10) x 3",
                        "Deadlift (6-10) x 3",
                        "Tri extension (6-10) x 3",
                        "Barbell curl (6-10) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Female' && $fitnessGoal == 'Fitness' && $fitnessLevel == 'Beginner'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Lunges (10-15) x 3",
                        "Leg extension (10-15) x 3",
                        "Squat (10-15) x 3",
                        "Leg press (10-15) x 3",
                        "Deadlift (10-15) x 3",
                        "Lat pulldown (10-15) x 3",
                        "Chest press (10-15) x 3",
                        "Tri dips (10-15) x 3",
                        "Barbell clean and press (10-15) x 3",
                        "Barbell curl (10-15) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Female' && $fitnessGoal == 'Fitness' && $fitnessLevel == 'Intermediate'){
                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Leg extension (10-15) x 3",
                        "Squat (10-15) x 3",
                        "Leg press (10-15) x 3",
                        "Deadlift (10-15) x 3",
                        "Lat pulldown (10-15) x 3",
                        "Cable row (10-15) x 3",
                        "Chest press (10-15) x 3",
                        "Shoulder press (10-15) x 3",
                        "Tri dips (10-15) x 3",
                        "Barbell curl (10-15) x 3",
                        "Wrist curl (10-15) x 3",
                        "Calf raises (10-15) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              elseif($gender == 'Female' && $fitnessGoal == 'Fitness' && $fitnessLevel == 'Advanced'){

                echo "<h2>Workout Schedule</h2>";

                // Create a workout schedule here
                $days = array(
                    "Day-01" => array(
                        "Warmup and Stretching",
                        "Cardio 40min",
                        "Kettlebell swings x 25 x3",
                        "Jumping Jacks x 25 x3",
                        "(rest 1min)",
                        "Leg extension (10-15) x 3",
                        "Squat (10-15) x 3",
                        "Leg press (10-15) x 3",
                        "Deadlift (10-15) x 3",
                        "Cable rows (10-15) x 3",
                        "Chest press (10-15) x 3",
                        "Barbell clean and press (10-15) x 3",
                        "Tri dips (10-15) x 3",
                        "Barbell curl (10-15) x 3",
                        "Double curl (10-15) x 3",
                        "Wrist curl (10-20) x 3",
                        "Calf raises (10-20) x 3",
                        "Cool down",
                        "Static stretches 15-30sec",
                        "Deep breathing 30-40sec",
                        "Foam rolling 30sec",
                    ),
                    "Day-02" => array(
                        "Rest",
                    ),
                    "Day-03" => array(
                        "Rest",
                    ),
                );
            
                // Display the workout schedule
                foreach ($days as $day => $exercises) {
                    echo "<h3>$day</h3>";
                    echo "<table border='1'>";
                    echo "<tr><th>Exercise</th><th>Sets and Reps</th></tr>";
                    foreach ($exercises as $exercise) {
                        echo "<tr><td>$exercise</td><td></td></tr>";
                    }
                    echo "</table>";
                }
              }
              else{
                echo "No user data found.";
              }
                //else if okkoma me pahala thiyena bracket ekata udin lynna

                 }

                } else {
                    echo "No user data found.";
                }

                $conn->close();
                ?>
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