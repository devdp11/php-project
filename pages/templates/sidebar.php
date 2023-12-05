<div class="position-fixed h-100 w-90 sidebar" style="background-color: rgb(231, 231, 231);" id="sidebar">
    <a class="d-none fw-bold mx-3 px-3 pt-3 text-decoration-none" href="../secure" style="color: blueviolet">Hello <?= $user['name'] ?? null ?>! </a>

    <!-- Bootstrap Dropdown -->
    <div class="dropdown">
        <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none dropdown-toggle" href="#" role="button" id="expensesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: black;">
            Expenses
        </a>
        
        <ul class="dropdown-menu" aria-labelledby="expensesDropdown">
            <li><a class="dropdown-item my-2" href="#">Manage Expenses</a></li>
            <li><a class="dropdown-item my-2" href="#">List Expenses</a></li>
            <li><a class="dropdown-item my-2" href="#">Share Expenses</a></li>
        </ul>
    </div>

    <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Payment Methods</a>
    <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Categories</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
