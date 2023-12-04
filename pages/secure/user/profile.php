<?php
require_once __DIR__ . '../../../../infra/middlewares/middleware-user.php';
include_once __DIR__ . '../../../../templates/header.php';
@require_once __DIR__ . '/../../../helpers/session.php';

$title = ' - Profile';
$user = user();
?>

<div class="p-5 mb-2 bg-dark text-white">
  <h1>User</h1>
</div>
<main>
  <section class="py-4">
    <div class="d-flex justify-content">
      <a href="/crud/"><button type="button" class="btn btn-secondary px-5 me-2">Back</button></a>
      <a href="./password.php"><button class="btn btn-warning px-2 me-2">Change Password</button></a>
    </div>
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
    <form enctype="multipart/form-data" action="/crud/controllers/admin/user.php" method="post"
      class="form-control py-3">
      <div class="input-group mb-3">
        <span class="input-group-text">Name</span>
        <input type="text" class="form-control" name="name" placeholder="name" maxlength="100" size="100"
          value="<?= isset($_REQUEST['name']) ? $_REQUEST['name'] : $user['name'] ?>" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Lastname</span>
        <input type="text" class="form-control" name="lastname" placeholder="lastname" maxlength="100" size="100"
          value="<?= isset($_REQUEST['lastname']) ? $_REQUEST['lastname'] : $user['lastname'] ?>" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Phone Number</span>
        <input type="tel" class="form-control" name="phoneNumber" maxlength="9"
          value="<?= isset($_REQUEST['phoneNumber']) ? $_REQUEST['phoneNumber'] : $user['phoneNumber'] ?>" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">email</span>
        <input type="email" class="form-control" name="email" maxlength="255"
          value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : $user['email'] ?>" required>
      </div>
      <div class="input-group mb-3">
        <label class="input-group-text" for="inputGroupFile01">Picture</label>
        <input accept="image/*" type="file" class="form-control" id="inputGroupFile01" name="foto" />
      </div>
      <div class="d-grid col-4 mx-auto">
        <button class="w-100 btn btn-lg btn-success mb-2" type="submit" name="user" value="profile">Change</button>
      </div>
    </form>
  </section>
</main>
<?php
include_once __DIR__ . '../../../../templates/footer.php';
?>