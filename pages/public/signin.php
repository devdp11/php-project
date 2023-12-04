<?php
require_once __DIR__ . '/../../infra/middlewares/middleware-not-authenticated.php';
include_once __DIR__ . '../../../templates/header.php';

$title = ' - Sign In';
?>
<main>
  <section>
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
  <form action="/crud/controllers/auth/signin.php" method="post">
    <h1 class="h3 mb-3 fw-normal">Sign In</h1>
    <div class="form-floating mb-2">
      <input type="email" class="form-control" id="Email" placeholder="Email" name="email" maxlength="255"
        value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : null ?>">
      <label for="Email">Email</label>
    </div>
    <div class="form-floating mb-2">
      <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="255"
        value="<?= isset($_REQUEST['password']) ? $_REQUEST['password'] : null ?>">
      <label for="password">Password</label>
    </div>
    <div class="checkbox mb-3">
      <label><input type="checkbox" value="remember-me">Remember me</label>
    </div>
    <button class="w-100 btn btn-lg btn-success mb-2" type="submit" name="user" value="login">Sign In</button>
  </form>
  <a href="/crud"><button class="w-100 btn btn-lg btn-info">Back</button></a>
</main>
<?php
include_once __DIR__ . '../../../templates/footer.php';
?>