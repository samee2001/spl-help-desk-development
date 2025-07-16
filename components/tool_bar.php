<div class="d-flex flex-wrap align-items-center justify-content-center mb-3 gap-3">
    <div>
        <select class="form-select">
            <option value="open">Open</option>
            <option value="waiting">Waiting</option>
            <option value="unassigned">Unassigned</option>
            <option value="mytickets">My Tickets</option>
            <option value="all">All</option>
            <option value="active-alerts">Active Alerts</option>
        </select>
    </div>
    <button class="btn btn-secondary" class="">Bulk Update</button>
    <form action="index.php" method="GET" class="input-group w-auto">
        <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>
    <button class="btn btn-primary new-ticket-btn" data-bs-toggle="modal" data-bs-target="#newTicketModal">
        <i class="fas fa-plus"></i> New Ticket
    </button>
    <?php 
       if (isset($total_records) && $total_records > 0): ?>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span><?php echo ($start_record + 1) . ' - ' . min($start_record + $records_per_page, $total_records) . ' of ' . $total_records; ?></span>
            <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
            <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
        </div>
    <?php endif; ?>
</div>