<div class="position-fixed h-100 w-90 sidebar" style="background-color: rgb(231, 231, 231);" id="sidebar">
    <a class="d-none fw-bold mx-3 px-3 pt-3 text-decoration-none" href="../secure" style="color: blueviolet">Hello <?= $user['name'] ?? null ?>! </a>
    <?php if (isset($customButtons)) { echo $customButtons; } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
