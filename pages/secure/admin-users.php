<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    require_once __DIR__ . '/../../repositories/admin.php';
    $countriesJson = file_get_contents('../templates/countries.json');
    $countries = json_decode($countriesJson, true);

    $filterUserName = isset($_POST['filterUserName']) ? $_POST['filterUserName'] : '';
    $searchAdmin = isset($_POST['filterAdmin']) && $_POST['filterAdmin'] === 'true';

    if (!isset($_SESSION['showAdmins'])) {
        $_SESSION['showAdmins'] = false;
    }

    if ($searchAdmin) {
        $_SESSION['showAdmins'] = !isset($_SESSION['showAdmins']) || !$_SESSION['showAdmins'];
    }

    if ($_SESSION['showAdmins']) {
        $users = getUserByAdmin();
    } elseif (!empty($filterUserName)) {
        $users = getUsersByName($filterUserName);
    } else {
        $users = getAll();
    }
?>

<?php include __DIR__ . '/sidebar.php'; ?>

<link rel="stylesheet" href="../resources/styles/global.css">
<link rel="stylesheet" href="../resources/styles/card.css">

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Users</li>
        </ol>
    </nav>

    <section class="py-3 px-5">
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

    <div class="row mb-3">
        <div class="col-12 col-md-1 my-2 mb-2">
            <button class="btn btn-blueviolet" data-bs-toggle="modal" data-bs-target="#add-expense">
                <span class="fa fa-plus"></span>
            </button>
        </div>
        <div class="col col-md-9 my-2 mb-2">
            <form id="searchForm" class="d-flex" method="post" action="">
                <div class="form-group me-2 flex-grow-1">
                    <input type="text" class="form-control" id="filterUserName" name="filterUserName"
                        placeholder="Search by Name" value="<?php echo $filterUserName; ?>">
                </div>
            </form>
        </div>
        <div class="col col-md-2 my-2 mb-2">
            <form method="post" action="">
                <button id="adminButton" type="submit"
                    class="btn <?php echo $_SESSION['showAdmins'] ? 'btn-blueviolet' : 'btn-blueviolet-reverse'; ?> mb-4"
                    name="filterAdmin" value="true">
                    <span
                        class="fas <?php echo $_SESSION['showAdmins'] ? 'fa-users' : 'fa-user-shield'; ?> me-2 w-auto"></span>
                    <?php echo $_SESSION['showAdmins'] ? 'Show All' : 'Show Admins'; ?>
                </button>
            </form>
        </div>
    </div>

    <div class=" row row-cols-1 row-cols-md-3 g-4">
        <div class="d-flex justify-content-center w-100">
            <?php if (empty($users)) : ?>
            <strong>
                <p class="mt-3 justify-content-center text-center" style="color: red">No users found.</p>
            </strong>
            <?php endif; ?>
        </div>
        <?php foreach ($users as $user) : ?>
        <div class="col">
            <div class="card style" id="user-card-<?php echo $user['id']; ?>">
                <div class="row">
                    <div class="col m-2 mt-3">
                    </div>
                    <div class="col">
                        <div class="justify-content-end align-items-center mt-2 mx-2">
                            <button type="button" class='btn btn-danger btn-sm float-end m-1' data-bs-toggle="modal"
                                data-bs-target="#delete-user<?= $user['id']; ?>"><i
                                    class="fas fa-trash-alt"></i></button>
                            <button type="button" class='btn btn-blueviolet btn-sm float-end m-1' data-bs-toggle="modal"
                                data-bs-target="#edit-user<?= $user['id']; ?>"><i
                                    class="fas fa-pencil-alt"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-center">
                            <h5 class='card-title'>
                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h5>

                            <p class='card-text'><strong>Email: </strong><?php echo $user['email']; ?></p>
                            <p class='card-text'><strong>Birth Date:
                                </strong><?php echo $user['birthdate']; ?></p>
                            <p class='card-text'><strong>Country: </strong><?php echo $user['country']; ?>
                            </p>
                            <p class='card-text'><strong>Admin:
                                </strong><?php echo $user['admin'] == 1 ? 'Yes' : 'No'; ?></p>
                            <p class='card-text'><strong>Created at:
                                </strong><?php echo $user['created_at']; ?></p>
                            <p class='card-text'><strong>Updated at:
                                </strong><?php echo $user['updated_at']; ?></p>
                        </div>
                        <div class="my-3" style="<?php echo empty($user['avatar']) ? 'display: none;' : ''; ?>">
                            <?php if (!empty($user['avatar'])): ?>
                            <?php
                                                $avatar_data = base64_decode($user['avatar']);
                                                $avatar_src = 'data:image/jpeg;base64,' . base64_encode($avatar_data);
                                            ?>
                            <div class="h-auto w-100">
                                <img src="<?= $avatar_src ?>" alt="avatar"
                                    class="object-fit-cover w-100 img-fluid d-block ui-w-80 mx-auto rounded"
                                    style="max-width: 150px;">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT USER -->
        <div class="modal fade" id="edit-user<?= $user['id']; ?>" tabindex="-1"
            aria-labelledby="edit-user<?= $user['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"> Edit User </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form action="../../controllers/admin/user.php" method="post">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <div class="form-group mt-3">
                                <label>First Name</label>
                                <input autocomplete="off" type="text" class="form-control" id="first_name"
                                    name="first_name" placeholder="First Name"
                                    value="<?= isset($user['first_name']) ? $user['first_name'] : '' ?>">
                            </div>
                            <div class="form-group mt-3">
                                <label>Last Name</label>
                                <input autocomplete="off" type="text" class="form-control" id="last_name"
                                    name="last_name" placeholder="Last Name"
                                    value="<?= isset($user['last_name']) ? $user['last_name'] : '' ?>">
                            </div>
                            <div class="form-group mt-3">
                                <label>Email</label>
                                <input autocomplete="off" type="email" class="form-control" id="email" name="email"
                                    aria-describedby="emailHelp" placeholder="Enter email"
                                    value="<?= isset($user['email']) ? $user['email'] : '' ?>" readonly>
                            </div>
                            <div class="form-group mt-3">
                                <label>Birth Date</label>
                                <input autocomplete="off" type="date" class="form-control" id="birthdate"
                                    name="birthdate" aria-describedby="birthDateHelp" placeholder="Enter birth date"
                                    value="<?= isset($user['birthdate']) ? $user['birthdate'] : '' ?>">
                            </div>
                            <div class="form-group mt-3">
                                <label>Country</label>
                                <select class="form-select" name="country">
                                    <?php foreach ($countries as $country): ?>
                                    <option value="<?= $country['name'] ?>"
                                        <?= (isset($user['country']) && $user['country'] == $country['name']) ? 'selected' : '' ?>>
                                        <?= $country['name'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-check mt-3">
                                <label class="form-check-label">Admin?</label>
                                <input autocomplete="off" class="form-check-input" type="checkbox" name="admin"
                                    id="admin" <?= isset($user['admin']) && $user['admin'] == 1 ? 'checked' : '' ?>>
                            </div>
                            <button type="submit" class="btn btn-blueviolet mt-3" name="user"
                                value="update">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DELETE USER -->
        <div class="modal fade" id="delete-user<?= $user['id']; ?>" tabindex="-1"
            aria-labelledby="delete-user<?= $user['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../controllers/admin/user.php" method="post">
                            <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                            <div class="mb-3">
                                Do you want to proceed deleting the user?
                            </div>
                            <button type="submit" name="user" value="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
    </div>

    <!-- MODAL ADD USER -->
    <div class="modal fade" id="add-user" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"> Add a User </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="../../controllers/admin/user.php" method="post">
                        <div class="form-group mt-3">
                            <label>First Name</label>
                            <input autocomplete="off" type="text" class="form-control" id="first_name" name="first_name"
                                placeholder="First Name">
                        </div>
                        <div class="form-group mt-3">
                            <label>Last Name</label>
                            <input autocomplete="off" type="text" class="form-control" id="last_name" name="last_name"
                                placeholder="Last Name">
                        </div>
                        <div class="form-group mt-3">
                            <label>Email</label>
                            <input autocomplete="off" type="email" class="form-control" id="email" name="email"
                                aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group mt-3">
                            <label>Birth Date</label>
                            <input autocomplete="off" type="date" class="form-control" id="birthdate" name="birthdate"
                                aria-describedby="birthDateHelp" placeholder="Enter birth date">
                        </div>
                        <div class="form-group mt-3">
                            <label>Password</label>
                            <input autocomplete="off" type="password" class="form-control" id="password" name="password"
                                placeholder="Password">
                        </div>
                        <div class="form-check mt-3">
                            <input autocomplete="off" class="form-check-input" type="checkbox" name="admin" id="admin">
                            <label class="form-check-label">Admin?</label>
                        </div>
                        <button type="submit" class="btn btn-blueviolet mt-3" name="user" value="create">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function debounce(func, delay) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, delay);
        };
    }

    function submitFormOnType() {
        var form = document.getElementById("searchForm");

        document.getElementById("filterUserName").addEventListener("input", debounce(function() {
            form.submit();
        }, 350));
    }

    document.addEventListener("DOMContentLoaded", submitFormOnType);

    document.addEventListener("DOMContentLoaded", function() {
        var adminButton = document.getElementById("adminButton");

        adminButton.addEventListener("click", function() {
            document.getElementById("searchForm").submit();
        });
    });
    </script>