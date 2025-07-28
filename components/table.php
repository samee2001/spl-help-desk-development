<?php
include 'configs/db_connection.php';
?>
<!-- Success Alert (hidden by default) -->
<div id="successAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
    Assignee updated to Maleesha Dewashan!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<div id="custom-alert" style="display:none; position:fixed; top:10px; left:50%; transform:translateX(-50%); z-index:9999; background:#f44336; color:#fff; padding:12px 24px; border-radius:5px; font-size:16px; box-shadow:0 2px 8px rgba(0,0,0,0.15);"></div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <!-- <th scope="col"><input type="checkbox"></th> -->
                <th scope="col">Ticket ID <i class="fas fa-sort"></i></th>
                <th scope="col">Summary <i class="fas fa-sort"></i></th>
                <th scope="col">Assignee <i class="fas fa-sort"></i></th>
                <th scope="col">Creator <i class="fas fa-sort"></i></th>
                <th scope="col">Organization <i class="fas fa-sort"></i></th>
                <th scope="col">Priority <i class="fas fa-sort-up"></i></th>
                <th scope="col">Category <i class="fas fa-sort"></i></th>
                <th scope="col">Status <i class="fas fa-sort"></i></th>
                <th scope="col">Created <i class="fas fa-sort"></i></th>
                <th scope="col">Updated At <i class="fas fa-sort"></i></th>
                <th scope="col">Due Date <i class="fas fa-sort"></i></th>
            </tr>
        </thead>
        <tbody>
            <?php

            // Database connection (adjust as needed)
            $search_query = $_GET['search'] ?? '';
            $status_filter = $_GET['status'] ?? ''; // Get the selected status filter
            // Base SQL with JOINs to get names instead of IDs


            // Path to the .sql file
            // $sqlFilePath = '/path/to/your/file.sql';

            // // Read the file contents into a PHP variable
            // $sql = file_get_contents($sqlFilePath);

            // // Check if the file was read successfully
            // if ($sqlContent === false) {
            //     die("Error reading the SQL file.");
            // }
            $sql = "SELECT 
                t.tk_id, 
                t.tk_summary, 
                t.tk_priority, 
                t.tk_created_at, 
                t.tk_updated_at, 
                t.tk_due_date as due_date,
                t.tk_description,
                t.tk_creator as creator_name,
                assignee.ur_name as assignee_name,
                org.org_name,
                cat.cat_name,
                t.status_name,
                log.changed_at
            FROM tb_ticket t
            LEFT JOIN tb_user creator ON t.tk_creator = creator.ur_email
            LEFT JOIN tb_user assignee ON t.tk_assignee = assignee.ur_id
            LEFT JOIN tb_organization org ON t.org_id = org.org_id
            LEFT JOIN tb_category cat ON t.cat_id = cat.cat_id
            LEFT JOIN tb_status st ON t.st_id = st.st_id
            LEFT JOIN (
                SELECT tk_id, MAX(changed_at) AS changed_at
                FROM tb_ticket_log
                GROUP BY tk_id
            ) log ON t.tk_id = log.tk_id";


            $params = [];
            $types = '';

            if (!empty($search_query)) {
                // You can add more fields to search in
                $sql .= " WHERE t.tk_summary LIKE ? 
                          OR t.tk_description LIKE ? 
                          OR t.tk_id LIKE ? 
                          OR t.tk_creator LIKE ? 
                          OR assignee.ur_name LIKE ? 
                          OR org.org_name LIKE ? 
                          OR t.tk_priority LIKE ?
                          OR cat.cat_name LIKE ?
                          OR t.status_name LIKE ?";
                $search_param = "%{$search_query}%";
                $params = array_fill(0, 9, $search_param); // We have 9 placeholders
                $types = str_repeat('s', 9);
            }

            // Apply status filter if selected
            if (!empty($status_filter)) {
                $sql .= (empty($search_query) ? " WHERE" : " AND") . " t.status_name = ?";
                $params[] = $status_filter;
                $types .= 's';
            }

            $types .= 'ii';
            $sql .= " ORDER BY t.tk_id DESC LIMIT ?, ?";

            // Pagination logic
            $current_page = $_GET['page'] ?? 1;
            $records_per_page = 20;
            $start_record = ($current_page - 1) * $records_per_page;

            $params[] = $start_record;
            $params[] = $records_per_page;

            // Fetch all tickets
            if (!empty($params)) {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $conn->query($sql);
            }

            // Count total records (for pagination display)
            $count_sql = "SELECT COUNT(*) FROM tb_ticket";
            $count_result = $conn->query($count_sql);
            $total_records = $count_result->fetch_row()[0];
            $total_pages = ceil($total_records / $records_per_page);


            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Adjust these fields to match your table columns if needed

                    $id = $row['tk_id'];
                    $summary = htmlspecialchars($row['tk_summary']);
                    $assignee = isset($row['assignee_name']) ? htmlspecialchars($row['assignee_name']) : 'Unassigned';
                    $creator = isset($row['creator_name']) ? htmlspecialchars($row['creator_name']) : 'N/A';
                    $organization = isset($row['org_name']) ? htmlspecialchars($row['org_name']) : 'N/A';
                    $priority = htmlspecialchars($row['tk_priority']);
                    $category = isset($row['cat_name']) ? htmlspecialchars($row['cat_name']) : 'N/A';
                    $status = isset($row['status_name']) ? htmlspecialchars($row['status_name']) : 'N/A';
                    $created = date('M d, Y', strtotime($row['tk_created_at']));
                    $updated = isset($row['changed_at']) ? htmlspecialchars($row['changed_at']) : 'Not Updated';
                    //$due_date = isset($row['due_date']) && $row['due_date'] ? date('M d, Y', strtotime($row['due_date'])) : '';

                    // Priority badge color
                    $priorityBadge = 'bg-secondary';
                    if (strtolower($priority) === 'high') $priorityBadge = 'bg-danger';
                    elseif (strtolower($priority) === 'medium') $priorityBadge = 'bg-warning text-dark';
                    elseif (strtolower($priority) === 'low') $priorityBadge = 'bg-success';

                    echo "<tr class='ticket-row' 
                            data-bs-toggle='offcanvas' 
                            data-bs-target='#ticketDetailsOffcanvas' 
                            aria-controls='ticketDetailsOffcanvas'
                            data-id='{$id}'
                            data-summary='{$summary}'
                            data-assignee='{$assignee}'
                            data-creator='{$creator}'
                            data-organization='{$organization}'
                            data-priority='{$priority}'
                            data-category='{$category}'
                            data-status='{$status}'
                            data-created='{$created}'
                            data-description='" . htmlspecialchars($row['tk_description']) . "'
                        >";
                    // echo "<td><input type='checkbox'></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$id}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$summary}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$assignee}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$creator}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$organization}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'><span class='badge {$priorityBadge}'>" .
                        ($priorityBadge === 'bg-danger' ? "<i class='fas fa-arrow-up'></i> " : ($priorityBadge === 'bg-warning text-dark' ? "<i class='fas fa-arrow-right'></i> " : "<i class='fas fa-arrow-down'></i> ")) .
                        ucfirst($priority) . "</span></a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$category}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$status}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$created}</a></td>";
                    echo "<td><a href='#' class='text-decoration-none'>{$updated}</a></td>";
                    //    echo "<td><a href='#' class='text-decoration-none'>{$due_date}</a></td>";
                    echo "</tr>";
                }
            } else {
                if (!empty($search_query)) {
                    echo "<tr><td colspan='12' class='text-center'>No tickets found matching your search for \"" . htmlspecialchars($search_query) . "\".</td></tr>";
                } else {
                    echo "<tr><td colspan='12' class='text-center'>No tickets found.</td></tr>";
                }
            }

            ?>

        </tbody>
    </table>
</div>

<!-- Offcanvas for Ticket Details -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="ticketDetailsOffcanvas" aria-labelledby="ticketDetailsLabel" style="height: 60vh;">
    <div class="offcanvas-header d-flex flex-column align-items-start">
        <div class="w-100 d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">
                    <span id="offcanvas-ticket-id"></span> - <span id="offcanvas-ticket-summary"></span>
                </span>
                <i class="fas fa-pen ms-2" style="cursor:pointer;"></i>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-success btn-sm me-2" id="acceptBtn" value="accept">Accept</button>
                <button class="btn btn-outline-secondary btn-sm me-2" id="closeBtn" data-bs-dismiss="offcanvas">Close</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-status="Closed">Close Ticket</a></li>
                        <li><a class="dropdown-item" href="#" data-status="Waiting">Waiting Ticket</a></li>
                        <li><a class="dropdown-item" href="#" data-status="Rejected">Reject Ticket</a></li>
                        <li><a class="dropdown-item" href="#" data-status="Delete">Delete Ticket</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas-body pt-0" style="overflow-y:auto;">
        <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle bg-warning text-white d-flex justify-content-center align-items-center" style="width:40px;height:40px;font-weight:bold;font-size:1.2rem;">
                <span id="offcanvas-ticket-initials">SS</span>
            </div>
            <div class="ms-3">
                <div><strong id="offcanvas-ticket-creator"></strong></div>
            </div>
        </div>
        <div class="mb-4">
            <p class="mb-1" id="offcanvas-ticket-assignee"></p>
            <p class="mb-1" id="offcanvas-ticket-description"></p>
            <p class="mb-0">Warm Regards,</p>
        </div>
        <div class="position-absolute bottom-0 start-0 w-100 px-4 pb-3" style="background: #fff;">
            <form class="d-flex align-items-center">
                <input type="text" class="form-control me-2" placeholder="Type a public response...">
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>
<script src="js/table_off_canvas.js"></script>

<script>
    // document.addEventListener('DOMContentLoaded', function(){
    //     var assigneeBtn = document.getElementById('assigneeBtn');
    //     var successAlert = document.getElementById('successAlert');
    //     if (assigneeBtn) {
    //         assigneeBtn.addEventListener('click', function(e) {
    //             e.stopPropagation();

    //             assigneeBtn.outerHTML = '<a href="#" class="text-decoration-none">Maleesha Dewashan</a>';

    //             successAlert.classList.remove('d-none');

    //             setTimeout(function() {
    //                 if (successAlert) successAlert.classList.add('d-none');
    //             }, 2000);
    //         });
    //     }
    // });
</script>

<!-- <?php //include 'components/tool_bar.php';  
        ?>  -->