<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<aside class="col-md-2 bg-light border-end min-vh-100 p-2" id="mainSidebar">
    <nav class="h-100 d-flex flex-column">
        <ul class="nav flex-column py-4 gap-2 h-100">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?php echo ($current_page == 'dashboard.php') ? 'active fw-bold bg-primary text-white' : 'text-secondary'; ?>"
                    href="#">
                    <i class="fas fa-desktop me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?php echo ($current_page == 'index.php') ? 'active fw-bold bg-primary text-white' : 'text-secondary'; ?>"
                    href="index.php">
                    <i class="fas fa-ticket-alt me-2"></i>
                    Tickets
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?php echo ($current_page == 'inventory.php') ? 'active fw-bold bg-primary text-white' : 'text-secondary'; ?>"
                    href="#">
                    <i class="fas fa-boxes me-2"></i>
                    Inventory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?php echo ($current_page == 'reports.php') ? 'active fw-bold bg-primary text-white' : 'text-secondary'; ?>"
                    href="#">
                    <i class="fas fa-chart-bar me-2"></i>
                    Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center <?php echo ($current_page == 'settings.php') ? 'active fw-bold bg-primary text-white' : 'text-secondary'; ?>"
                    href="settings.php">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </nav>
</aside>