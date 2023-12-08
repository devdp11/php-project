<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
    $countriesJson = file_get_contents('../templates/countries.json');
    $countries = json_decode($countriesJson, true);
?>

<style>
    .empty-avatar {
        width: 100px;
        height: 100px; 
        background-color: #f0f0f0; 
    }
</style>

<?php include __DIR__ . '/dashboard.php'; ?>
    
    <div class="col-auto flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-1">
            Account settings
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-border-light">
                <div class="col-md-2 pt-0 d-flex flex-column">
                    <div class="mb-4"></div>
                        <div class="list-group account-settings-links">
                            <a class="mx-4 my-2 rounded list-group-item list-group-item-action" data-toggle="list"
                                href="#account-general">Profile</a>
                            <a class="mx-4 my-2 rounded list-group-item list-group-item-action" data-toggle="list"
                                href="#account-change-password">Change password</a>
                            <form action="../../controllers/auth/signin.php" method="post">
                                <button class="btn btn-danger mx-4 mt-2" type="submit" name="user" value="logout">Logout</button>
                            </form>
                            <form action="../../controllers/auth/signin.php" method="post" onsubmit="return confirmDelete()">
                                <button class="btn btn-danger mx-4" type="submit" name="user" value="delete">Delete Account</button>
                            </form>
                        </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body text-center">
                                <div class="my-4">
                                    <?php if (!empty($user['avatar'])): ?>
                                        <?php
                                        $avatarData = base64_decode($user['avatar']);
                                        $avatarSrc = 'data:image/jpeg;base64,' . base64_encode($avatarData);
                                        ?>
                                        <img src="<?= $avatarSrc ?>" alt="avatar" class="d-block ui-w-80 mx-auto" style="max-width: 100px">
                                    <?php else: ?>
                                        <div class="empty-avatar"></div>
                                    <?php endif; ?>
                                </div>

                                <form action="../../controllers/user/avatar.php" method="post" enctype="multipart/form-data">
                                    <label class="btn btn-outline-primary">
                                        Choose Image
                                        <input type="file" class="account-settings-fileinput d-none" name="avatar">
                                    </label>
                                    <button type="submit" class="btn btn-primary px-4" name="update_avatar">Upload</button>
                                    <button type="button" class="btn btn-danger px-4">Reset</button>
                                </form>
                            </div>
                            <div class="card-body d-flex justify-content-center mt-0">
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
                                        <input type="text" class="form-control mb-1" name="email" value="<?= $user['email'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <select class="form-select mb-1" name="country">
                                            <?php foreach ($countries as $country): ?>
                                                <option value="<?= $country['name'] ?>" <?= ($user['country'] == $country['name']) ? 'selected' : '' ?>>
                                                    <?= $country['name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Birth Date</label>
                                        <input type="date" class="form-control mb-1" name="birthdate" value="<?= $user['birthdate'] ?>">
                                    </div>

                                    <div class="text-right my-3">
                                        <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                        <button type="button" class="btn btn-default">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <form action="../../controllers/user/password.php" method="post">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Current password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New password</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Repeat new password</label>
                                        <input type="password" class="form-control" name="repeat_password" required>
                                    </div>
                                    <div class="text-right my-3">
                                        <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                        <button type="button" class="btn btn-default">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmDelete() {
            var result = confirm("Are you sure about the account removal?");
            return result;
        }
    </script>