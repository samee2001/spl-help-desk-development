<div class="d-flex flex-wrap align-items-center justify-content-center mb-3 gap-3">
    <div>
        <select class="form-select">
            <option value="">Select Status</option>
            <option value="Open">Open</option>
            <option value="Accepted">Accepted</option>
            <option value="Waiting">Waiting</option>
            <option value="Closed">Closed</option>
            <option value="Unassigned">Unassigned</option>
            <option value="My Tickets">My Tickets</option>
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