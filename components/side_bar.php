<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="col-md-2 bg-light border-end min-vh-100">
    <ul class="nav flex-column py-3">
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                <i class="fas fa-desktop me-2"></i>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">
                <i class="fas fa-ticket-alt me-2"></i>Tickets
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'inventory.php') ? 'active' : ''; ?>" href="inventory.php">
                <i class="fas fa-boxes me-2"></i>Inventory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>" href="reports.php">
                <i class="fas fa-chart-bar me-2"></i>Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>" href="settings.php">
                <i class="fas fa-cog me-2"></i>Settings
            </a>
        </li>
    </ul>
</aside>