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
    <div class="p-4">
        <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item">Profile</li>
            </ol>
        </nav>
    
    <div class="container mt-5">

        <div class="d-flex justify-content-center">
            <button class="btn btn-blueviolet-reverse mx-2 my-0" onclick="showProfile()">Profile</button>
            <button class="btn btn-blueviolet mx-2 my-0" onclick="showChangePassword()">Password</button>
            <form action="../../controllers/auth/signin.php" method="post" onsubmit="return deleteCf()" class="mx-2 my-0">
                <button class="btn btn-danger" type="submit" name="user" value="delete">Delete</button>
            </form>
        </div>

        <div class="row mt-5">
            <div class="col-md-4">
                <div class="text-center">
                    <?php if (!empty($user['avatar'])): ?>
                        <?php
                            $avatarData = base64_decode($user['avatar']);
                            $avatarSrc = 'data:image/jpeg;base64,' . base64_encode($avatarData);
                        ?>
                        <img src="<?= $avatarSrc ?>" alt="avatar" class="d-block ui-w-80 mx-auto rounded" width="150px">
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

            <div class="col-md-8">
                <div class="mt-4" id="profileSection">
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
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control mb-1" name="email" value="<?= $user['email'] ?>" style="max-width: 495px;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Country</label>
                            <select class="form-select mb-1" name="country" style="max-width: 495px;">
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?= $country['name'] ?>" <?= ($user['country'] == $country['name']) ? 'selected' : '' ?>>
                                        <?= $country['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Birth Date</label>
                            <input type="date" class="form-control mb-1" name="birthdate" value="<?= $user['birthdate'] ?>" style="max-width: 495px;">
                        </div>

                        <div class="text-right my-3">
                            <button type="submit" class="btn btn-blueviolet">Save changes</button>&nbsp;
                            <button type="button" class="btn btn-danger">Cancel</button>
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
                            <button type="button" class="btn btn-default">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function deleteCf() {
        var result = confirm("Are you sure about the account removal?");
        return result;
    }

    function showChangePassword() {
        document.getElementById('profileSection').style.display = 'none';
        document.getElementById('passwordSection').style.display = 'block';
    }

    function showProfile() {
        document.getElementById('passwordSection').style.display = 'none';
        document.getElementById('profileSection').style.display = 'block';
    }

</script>