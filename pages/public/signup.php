<?php
require_once __DIR__ . '/../../infra/middlewares/middleware-not-authenticated.php';
$title = '- Sign Up';
include_once __DIR__ . '../../../templates/header.php'; ?>

<main>
  <section>
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
  <form action="/crud/controllers/auth/signup.php" method="post">
    <h1 class="h3 mb-3 fw-normal">Sign Up</h1>
    <div class="form-floating mb-2">
      <input type="text" class="form-control" name="name" placeholder="name" maxlength="100" size="100"
        value="<?= isset($_REQUEST['name']) ? $_REQUEST['name'] : null ?>" required>
      <label for="name">Name</label>
    </div>
    <div class="form-floating mb-2">
      <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com"
        value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : null ?>">
      <label for="floatingInput">Email</label>
    </div>
    <div class="form-floating mb-2">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
      <label for="password">Password</label>
    </div>
    <div class="form-floating mb-2">
      <input type="password" class="form-control" id="confirmar_palavra_passe" name="confirmar_palavra_passe"
        placeholder="Confirm password">
      <label for="confirmar_palavra_passe">Confirm Password</label>
    </div>
    <button class="w-100 btn btn-lg btn-success mb-2" type="submit" name="user" value="signUp">Sign Up</button>
  </form>
  <a href="/crud/"><button class="w-100 btn btn-lg btn-info">Back</button></a>
</main>
<?php
include_once __DIR__ . '../../../templates/footer.php'; ?>
?>