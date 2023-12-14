<?php
require_once __DIR__ . '../../../middlewares/middleware-user.php';
@require_once __DIR__ . '/../../validations/session.php';
require_once __DIR__ . '../../../db/connection.php'; // Certifique-se de que este arquivo contenha a lógica de conexão PDO
$user = user();

// Consulta ao banco de dados para obter despesas do usuário autenticado
$stmt = $pdo->prepare("SELECT expense_id, description, date FROM expenses WHERE user_id = :user_id AND deleted_at IS NULL");
$stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$events = [];
foreach ($expenses as $expense) {
    $events[] = [
        'id' => $expense['expense_id'],
        'title' => $expense['description'],
        'start' => $expense['date'],
    ];
}
?>

    <meta charset="UTF-8">
    <script src='./dist/index.global.js'></script>
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
    </style>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Calendar</li>
        </ol>
    </nav>
    <div id='calendar'></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: '2023-01-12',
            editable: true,
            selectable: true,
            businessHours: true,
            dayMaxEvents: true,
            events: <?php echo json_encode($events); ?> // Adiciona os eventos formatados ao calendário
        });

        calendar.render();
    });
</script>