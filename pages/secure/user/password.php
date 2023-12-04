<?php
require_once __DIR__ . '../../../../infra/middlewares/middleware-user.php';
include_once __DIR__ . '../../../../templates/header.php';
@require_once __DIR__ . '/../../../helpers/session.php';

$title = ' - Change password';
$user = user();
?>

<div class="p-5 mb-2 bg-dark text-white">
  <h1>Change Password</h1>
</div>
<main>
  <section class="py-4">
    <a href="/crud/pages/secure/user/profile.php"><button type="button" class="btn btn-secondary px-5">Back</button></a>
  </section>
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
  <section>
    <form action="/crud/controllers/admin/user.php" method="post" class="form-control py-3">
      <div class="input-group mb-3">
        <span class="input-group-text">Name</span>
        <input type="text" readonly class="form-control" name="name" placeholder="<?= $user['name'] ?>"
          value="<?= $user['name'] ?>">
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Password</span>
        <input type="password" class="form-control" name="password" maxlength="255" size="255" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Confirm Password</span>
        <input type="password" class="form-control" name="confirm_password" maxlength="255" required>
      </div>
      <div class="d-grid col-4 mx-auto">
        <button class="w-100 btn btn-lg btn-success mb-2" type="submit" name="user" value="password">Change</button>
      </div>
    </form>
  </section>
</main>
<?php
include_once __DIR__ . '../../../../templates/footer.php';
?>