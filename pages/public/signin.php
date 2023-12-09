<?php
require_once __DIR__ . '/../../middlewares/middleware-not-authenticated.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Expense Flow - Sign IN</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../resources/styles/global.css">
  </head>
  
<body style="background-color: hsl(0, 0%, 96%)" class="py-5">
  <section class="py-4 px-5">
    <?php
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
          <div class="d-flex justify-content-center">
            <a class="text-decoration-none" href="../../index.php">
              <h1 class="fw-bold" style="color: blueviolet">EXPENSE FLOW</h1>
            </a>
          </div>
          <p style="color: hsl(217, 10%, 50.8%)">
            Hello there! Returning to your financial hub? Sign in to your account and unlock a world of personalized financial management. Our platform is more than just numbers; it's about your journey, your story, and your financial well-being.
          </p>
        </div>

        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="card">
            <div class="card-body py-5 px-md-5">
              <form action="../../controllers/auth/signin.php" method="post">
                <!-- Email input -->
                <div class="form-outline mb-4">
                  <label class="mb-2" for="Email">Email Adress</label>
                  <input type="email" class="form-control" id="Email" placeholder="Email" name="email" maxlength="255" value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : null ?>">
                </div>

                <!-- Password input -->
                <div class="form-outline mb-4">
                  <label class="mb-2" for="password">Password</label>
                  <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="255" value="<?= isset($_REQUEST['password']) ? $_REQUEST['password'] : null ?>">
                </div>

                <!-- CheckBox -->
                <div class="form-check d-flex justify-content-start mb-4">
                  <div class="justify-content-start">
                    <input class="form-check-input" type="checkbox" id="remember-me">
                  </div>
                  <div class="justify-content-end">
                    <label class="form-check-label" for="remember-me">
                      Remember me
                    </label>
                  </div>          
                </div>

                <!-- Submit button -->
                <div class="d-flex justify-content-center mb-4">
                  <button class="w-50 btn btn-lg btn-blueviolet mb-2" type="submit" name="user" value="login">Sign In</button>
                </div>

                <div class="text-center">
                  <p>or sign in using:</p>
                  <button type="button" class="btn btn-link btn-floating mx-1 btn-blueviolet">
                    <i class="fab fa-facebook-f"></i>
                  </button>

                  <button type="button" class="btn btn-link btn-floating mx-1 btn-blueviolet">
                    <i class="fab fa-google"></i>
                  </button>

                  <button type="button" class="btn btn-link btn-floating mx-1 btn-blueviolet">
                    <i class="fab fa-twitter"></i>
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