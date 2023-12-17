<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
    $countriesJson = file_get_contents('../templates/countries.json');
    $countries = json_decode($countriesJson, true);
?>

<style>
    .empty-avatar {
        width: 0;
        height: 0; 
        background-color: #f0f0f0; 
    }
</style>

<?php include __DIR__ . '/sidebar.php'; ?>
    <div class="p-4 overflow-auto h-100">
        <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item">Profile</li>
            </ol>
        </nav>
    
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

        <div class="d-flex justify-content-center">
            <button class="btn btn-blueviolet-reverse mx-2 my-0" onclick="showProfile()">Profile</button>
            <button class="btn btn-blueviolet mx-2 my-0" onclick="showChangePassword()">Password</button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-user-modal-<?= $user['id']; ?>">Delete</button>
        </div>

        <div class="row mt-5">
            <div class="col-md-4 d-flex align-items-center justify-content-center">
                <div class="text-center">
                    <?php if (!empty($user['avatar'])): ?>
                        <?php
                            $avatarData = base64_decode($user['avatar']);
                            $avatarSrc = 'data:image/jpeg;base64,' . base64_encode($avatarData);
                        ?>
                        <div class="h-auto w-100">
                            <img src="<?= $avatarSrc ?>" alt="avatar" class="object-fit-cover w-100 img-fluid d-block ui-w-80 mx-auto rounded" style="max-width: 100px;">
                        </div>
                    <?php else: ?>
                    <?php endif; ?>   
                    <form action="../../controllers/user/avatar.php" method="post" enctype="multipart/form-data">
                        <label class="btn btn-blueviolet-reverse mt-2">
                            Choose
                            <input type="file" class="account-settings-fileinput d-none" name="avatar">
                        </label>
                        <button type="submit" class="btn btn-blueviolet px-3 mt-2" name="update_avatar">Upload</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8 mb-5">
                <div class="mb-5" id="profileSection">
                    <form action="../../controllers/user/user.php" method="post">
                        <div class="row">
                            <div class="col-auto form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control mb-1" name="first_name" value="<?= $user['first_name'] ?>">
                            </div>
                            <div class="col-auto form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control mb-1" name="last_name" value="<?= $user['last_name'] ?>">
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control mb-1" name="email" value="<?= $user['email'] ?>" style="max-width: 495px;">
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Birth Date</label>
                            <input type="date" class="form-control mb-1" name="birthdate" value="<?= $user['birthdate'] ?>" style="max-width: 495px;">
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Country</label>
                            <select class="form-select mb-1" name="country" style="max-width: 495px;">
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?= $country['name'] ?>" <?= ($user['country'] == $country['name']) ? 'selected' : '' ?>>
                                        <?= $country['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="text-right mt-2 mb-5">
                            <button type="submit" class="btn btn-blueviolet">Save changes</button>&nbsp;
                            <button type="button" class="btn btn-danger" onclick="refreshPage()">Cancel</button>
                        </div>
                    </form>
                </div>

                <div class="mt-4" id="passwordSection" style="display: none;">
                    <form action="../../controllers/user/password.php" method="post">
                        <div class="form-group">
                            <label class="form-label">Current password</label>
                            <input type="password" class="form-control" name="current_password" required style="max-width: 495px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">New password</label>
                            <input type="password" class="form-control" name="new_password" required style="max-width: 495px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Repeat new password</label>
                            <input type="password" class="form-control" name="repeat_password" required style="max-width: 495px;">
                        </div>

                        <div class="text-right my-3">
                            <button type="submit" class="btn btn-blueviolet">Save changes</button>&nbsp;
                            <button type="button" class="btn btn-danger" onclick="refreshPage()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE -->
    <div class="modal fade" id="delete-user-modal-<?= $user['id']; ?>" tabindex="-1" aria-labelledby="delete-user-modal-<?= $user['id']; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../controllers/auth/signin.php" method="post">
                        <input type="hidden" name="id" value="<?= $user['id']; ?>">
                        <div class="mb-3">
                            Do you want to delete your account?
                        </div>
                        <button type="submit" name="user" value="delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    function showChangePassword() {
        document.getElementById('profileSection').style.display = 'none';
        document.getElementById('passwordSection').style.display = 'block';
    }

    function showProfile() {
        document.getElementById('passwordSection').style.display = 'none';
        document.getElementById('profileSection').style.display = 'block';
    }
    function refreshPage() {
        location.reload(true);
    }
</script>