<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gym Management - User Registration</title>
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa; /* Set your desired background color */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-container {
      width: 350px;
      padding: 20px;
      border-radius: 10px;
      background-color: #ffffff; /* Set your desired form background color */
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-heading {
      text-align: center;
      margin-bottom: 20px;
      color: #007bff; /* Set your desired heading color */
    }

    .form-container .form-control {
      border-radius: 5px;
    }

    .form-container .btn-primary {
      background-color: #007bff; /* Set your desired button background color */
      border: none;
      width: 100%;
      margin-top: 20px;
    }

    .form-container .btn-primary:hover {
      background-color: #0056b3; /* Set your desired button hover color */
    }

    .form-container .btn-reset {
      background-color: #6c757d; /* Set your desired reset button background color */
      border: none;
      width: 100%;
      margin-top: 10px;
    }

    .form-container .btn-reset:hover {
      background-color: #444d56; /* Set your desired reset button hover color */
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center align-items-center">
      <div class="col-md-4">
        <div class="form-container">
          <h2 class="form-heading">Gym Management - Create User</h2>
          <form method="post" action="createusercheck.php">
            <div class="mb-3">
              <input type="text" class="form-control" name="name" placeholder="Name" required>
            </div>
            <div class="mb-3">
              <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Create User</button>
            <button type="reset" class="btn btn-reset">Reset</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS (Optional if you don't require any Bootstrap JS components) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
