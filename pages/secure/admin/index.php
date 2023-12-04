<?php
require_once __DIR__ . '/../../../infra/repositories/userRepository.php';
require_once __DIR__ . '/../../../infra/middlewares/middleware-administrator.php';
require_once __DIR__ . '/../../../templates/header.php'; 

$users = getAll();
$title = ' - Admin management';
?>

<div class="pt-1 ">
  <div class="p-5 mb-2 bg-dark text-white">
    <h1>Users</h1>
  </div>

  <main class="bg-light">
    <section class="py-4">
      <div class="d-flex justify-content">
        <a href="/crud/pages/secure/"><button class="btn btn-secondary px-5 me-2">Back</button></a>
        <a href="./user.php"><button class="btn btn-success px-4 me-2">Create user</button></a>
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
      <div class="table-responsive">
        <table class="table">
          <thead class="table-secondary">
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Lastname</th>
              <th scope="col">Phone Number</th>
              <th scope="col">Email</th>
              <th scope="col">Administrator</th>
              <th scope="col">Manage</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($users as $user) {
              ?>
              <tr>
                <th scope="row">
                  <?= $user['name'] ?>
                </th>
                <td>
                  <?= $user['lastname'] ?>
                </td>
                <td>
                  <?= $user['phoneNumber'] ?>
                </td>
                <td>
                  <?= $user['email'] ?>
                </td>
                <td>
                  <?= $user['administrator'] == '1' ? 'Yes' : 'No' ?>
                </td>
                <td>
                  <div class="d-flex justify-content">
                    <a href="/crud/controllers/admin/user.php?<?= 'user=update&id=' . $user['id'] ?>"><button type="button"
                        class="btn btn-primary me-2">update</button></a>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                      data-bs-target="#delete<?= $user['id'] ?>">delete</button>
                  </div>
                </td>
              </tr>
              <div class="modal fade" id="delete<?= $user['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Delete user</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to delete this user?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <a href="/crud/controllers/admin/user.php?<?= 'user=delete&id=' . $user['id'] ?>"><button type="button"
                          class="btn btn-danger">Confirm</button></a>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
<?php
include_once __DIR__ . '/../../../templates/footer.php'; ?>