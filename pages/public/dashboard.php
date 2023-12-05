<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '../../../validations/session.php';
    include_once __DIR__ . '/../templates/header.php';
    $user = user();
    $title = 'Dashboard';
?>

<?php include_once __DIR__ . '/../templates/sidebar.php'; ?>

<script> document.title = "EFlow - <?= $title ?? '' ?>"; </script>