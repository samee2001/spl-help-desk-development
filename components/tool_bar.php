<?php
// Ensure session so we can read user id
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'configs/db_connection.php'; // DB connection
require_once 'api/access_permission.php';

// Permission: can the current user add an employee?
$canAddEmployee = userHasPermission($conn, $_SESSION['user_id'] ?? 0, 'add_employee');
$canCreateTicket = userHasPermission($conn, $_SESSION['user_id'] ?? 0, 'create_ticket');
// Current selected status (persist selection)
$currentStatus = isset($_GET['status']) ? trim($_GET['status']) : '';
?>

<div class="d-flex flex-wrap align-items-center justify-content-center mb-3 gap-3">
    <div>
        <select class="form-select">
            <option value="" <?php echo $currentStatus === '' ? 'selected' : ''; ?>>Select Status</option>
            <option value="Open" <?php echo $currentStatus === 'Open' ? 'selected' : ''; ?>>Open</option>
            <option value="Accepted" <?php echo $currentStatus === 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
            <option value="Waiting" <?php echo $currentStatus === 'Waiting' ? 'selected' : ''; ?>>Waiting</option>
            <option value="Closed" <?php echo $currentStatus === 'Closed' ? 'selected' : ''; ?>>Closed</option>
            <option value="Unassigned" <?php echo $currentStatus === 'Unassigned' ? 'selected' : ''; ?>>Unassigned</option>
            <option value="All" <?php echo $currentStatus === 'All' ? 'selected' : ''; ?>>All</option>
        </select>
    </div>
    <p><?php echo htmlspecialchars($_SESSION['user_id'] ?? 'no'); ?></p>

    <?php if ($canAddEmployee): ?>
        <button class="btn btn-secondary"><a href="employee_registration.php" style="text-decoration: none; color: white;">Add Employee</a></button>
    <?php endif; ?>
    <form action="index.php" method="GET" class="input-group w-auto">
        <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>
    <?php if ($canCreateTicket): ?>
        <button class="btn btn-primary new-ticket-btn" data-bs-toggle="modal" data-bs-target="#newTicketModal">
            <i class="fas fa-plus"></i> New Ticket
        </button>
    <?php endif; ?>
    <!-- change of the status -->
    <script>
        // Pass the selected status to the table via URL parameter
        document.querySelector('.form-select').addEventListener('change', function() {
            var status = this.value;
            window.location.href = 'index.php?status=' + status;
        });
    </script>
    <?php
    if (isset($total_records) && $total_records > 0): ?>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span><?php echo ($start_record + 1) . ' - ' . min($start_record + $records_per_page, $total_records) . ' of ' . $total_records; ?></span>
            <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
            <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
        </div>
    <?php endif; ?>
</div>