<?php
require_once __DIR__ . '/../../../infra/middlewares/middleware-administrator.php';
require_once __DIR__ . '/../../../templates/header.php'; 

$title = ' - user';
?>

<main>
  <section class="py-4">
    <a href="./"><button type="button" class="btn btn-secondary px-5">Back</button></a>
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
  <section class="pb-4">
    <form enctype="multipart/form-data" action="/crud/controllers/admin/user.php" method="post"
      class="form-control py-3">
      <div class="input-group mb-3">
        <span class="input-group-text">Name</span>
        <input type="text" class="form-control" name="name" maxlength="100" size="100"
          value="<?= isset($_REQUEST['name']) ? $_REQUEST['name'] : null ?>" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Last Name</span>
        <input type="text" class="form-control" name="lastname" maxlength="100" size="100"
          value="<?= isset($_REQUEST['lastname']) ? $_REQUEST['lastname'] : null ?>" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Phone Number</span>
        <input type="tel" class="form-control" name="phoneNumber" maxlength="9"
          value="<?= isset($_REQUEST['phoneNumber']) ? $_REQUEST['phoneNumber'] : null ?>" required>
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">E-mail</span>
        <input type="email" class="form-control" name="email" maxlength="255"
          value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : null ?>" required>
      </div>
      <div class="input-group mb-3">
        <label class="input-group-text" for="inputGroupFile01">Foto Profile</label>
        <input accept="image/*" type="file" class="form-control" id="inputGroupFile01" name="foto" />
      </div>
      <div class="input-group mb-3">
        <span class="input-group-text">Password</span>
        <input type="password" class="form-control" name="password" maxlength="255" required>
      </div>
      <div class="input-group mb-3">
        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" name="administrator" role="switch" id="flexSwitchCheckChecked"
            <?= isset($_REQUEST['administrator']) && $_REQUEST['administrator'] == true ? 'checked' : null ?>>
          <label class="form-check-label" for="flexSwitchCheckChecked">administrator</label>
        </div>
      </div>
      <div class="d-grid col-4 mx-auto">
        <input type="hidden" name="id" value="<?= isset($_REQUEST['id']) ? $_REQUEST['id'] : null ?>">
        <input type="hidden" name="foto" value="<?= isset($_REQUEST['foto']) ? $_REQUEST['foto'] : null ?>">
        <button type="submit" class="btn btn-success" name="user" <?= isset($_REQUEST['action']) && $_REQUEST['action'] == 'update' ? 'value="update"' : 'value="create"' ?>>Create</button>
      </div>
    </form>
  </section>
</main>
<?php
require_once __DIR__ . '/../../../templates/footer.php';
?>