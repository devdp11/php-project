<?php
require_once __DIR__ . '/../../middlewares/middleware-not-authenticated.php';
$title = '- Sign Up';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Flow - Sign UP</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body style="background-color: hsl(0, 0%, 96%)">
  <section class="py-4 px-5">
    <?php
    if (isset($_SESSION['success'])) {
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
      echo $_SESSION['success'] . '<br>';
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
      unset($_SESSION['success']);
    }
    if (isset($_SESSION['errors'])) {
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
      foreach ($_SESSION['errors'] as $error) {
        echo $error . '<br>';
      }
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
      unset($_SESSION['errors']);
    }
    ?>
  </section>
  <div class="px-4 py-5 px-md-5 text-center text-lg-start">
    <div class="container">
      <div class="row gx-lg-5 align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <h1 class="my-5 display-3 fw-bold ls-tight">
            The best offer <br />
            <span class="text-primary">for your business</span>
          </h1>
          <p style="color: hsl(217, 10%, 50.8%)">
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Eveniet, itaque accusantium odio, soluta, corrupti aliquam
            quibusdam tempora at cupiditate quis eum maiores libero
            veritatis? Dicta facilis sint aliquid ipsum atque?
          </p>
        </div>

        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="card">
            <div class="card-body py-5 px-md-5">
              <form action="../../controllers/auth/signup.php" method="post">

                <div class="form-outline mb-3">
                  <label class="mb-2" for="name">Name</label>
                  <input type="text" class="form-control" name="name" placeholder="name" maxlength="100" size="100" value="<?= isset($_REQUEST['name']) ? $_REQUEST['name'] : null ?>" required>
                </div>

                <!-- Email input -->
                <div class="form-outline mb-3">
                  <label class="mb-2" for="floatingInput">Email</label>
                  <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : null ?>">
                </div>

                <div class="form-outline mb-3">
                  <label class="mb-2" for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <!-- Password input -->
                <div class="form-outline mb-3">
                  <label class="mb-2" for="confirm_password">Confirm Password</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password">
                </div>

                <!-- Submit button -->
                <div class="d-flex justify-content-center mb-3">
                  <button class="w-50 btn btn-lg btn-primary mb-2" type="submit" name="user" value="signUp">Sign Up</button>
                </div>

                <!-- HasACC -->
                <div class="d-flex justify-content-center mb-2">
                  <label class="d-flex">Already have an <a class="text-decoration-none mx-1" href="./signin.php" style="color: blueviolet"> account?</a></label>
                </div>
                
                <!-- Register buttons -->
                <div class="text-center">
                  <p>or sign up with:</p>
                  <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-facebook-f"></i>
                  </button>

                  <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-google"></i>
                  </button>

                  <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-twitter"></i>
                  </button>

                  <button type="button" class="btn btn-link btn-floating mx-1">
                    <i class="fab fa-github"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>