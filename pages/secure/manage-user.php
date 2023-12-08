<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
    $countriesJson = file_get_contents('../templates/countries.json');
    $countries = json_decode($countriesJson, true);
?>

<?php include __DIR__ . '/dashboard.php'; ?>
