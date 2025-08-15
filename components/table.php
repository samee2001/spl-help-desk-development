<?php
include 'configs/db_connection.php';

// Get the logged-in user's employee ID
$user_email = $_SESSION['user_email'] ?? '';
$user_emp_id = null;

if (!empty($user_email)) {
    // Get the employee ID for the logged-in user
    $user_stmt = $conn->prepare("SELECT emp_id FROM tb_user WHERE ur_email = ?");
    $user_stmt->bind_param("s", $user_email);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_emp_id = $user_row['emp_id'];
    }
    $user_stmt->close();
}
?>
<!-- Success Alert (hidden by default) -->
<!-- <div id="successAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
    Assignee updated to Maleesha Dewashan!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<div id="custom-alert" style="display:none; position:fixed; top:10px; left:50%; transform:translateX(-50%); z-index:9999; background:#f44336; color:#fff; padding:12px 24px; border-radius:5px; font-size:16px; box-shadow:0 2px 8px rgba(0,0,0,0.15);"></div> -->

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
            $sql = "SELECT 
                t.tk_id, 
                t.tk_summary, 
                t.tk_priority, 
                t.tk_created_at, 
                t.tk_updated_at, 
                t.tk_due_date as due_date,
                t.tk_description,
                t.tk_creator as creator_name,
                assignee.emp_name as assignee_name,
                org.org_name,
                cat.cat_name,
                t.status_name,
                log.changed_at
            FROM tb_ticket t
            LEFT JOIN tb_user creator ON t.tk_creator = creator.ur_email
            LEFT JOIN tb_employee assignee ON t.tk_assignee = assignee.emp_id
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

            // Filter tickets by assigned user (if user is logged in)
            if (!empty($user_emp_id)) {
                $sql .= " WHERE t.tk_assignee = ?";
                $params[] = $user_emp_id;
                $types = 'i';
            }

            if (!empty($search_query)) {
                // You can add more fields to search in
                $sql .= (empty($user_emp_id) ? " WHERE" : " AND") . " (t.tk_summary LIKE ? 
                          OR t.tk_description LIKE ? 
                          OR t.tk_id LIKE ? 
                          OR t.tk_creator LIKE ? 
                          OR assignee.emp_name LIKE ? 
                          OR org.org_name LIKE ? 
                          OR t.tk_priority LIKE ?
                          OR cat.cat_name LIKE ?
                          OR t.status_name LIKE ?)";
                $search_param = "%{$search_query}%";
                $search_params = array_fill(0, 9, $search_param); // We have 9 placeholders
                $params = array_merge($params, $search_params);
                $types .= str_repeat('s', 9);
            }

            // Apply status filter if selected
            if (!empty($status_filter)) {
                $sql .= (empty($user_emp_id) && empty($search_query) ? " WHERE" : " AND") . " t.status_name = ?";
                $params[] = $status_filter;
                $types .= 's';
            }

            $sql .= " ORDER BY t.tk_id DESC";

            // Fetch all tickets
            if (!empty($params)) {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $conn->query($sql);
            }




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
            <!-- <div class="rounded-circle bg-warning text-white d-flex justify-content-center align-items-center" style="width:40px;height:40px;font-weight:bold;font-size:1.2rem;">
                <span id="offcanvas-ticket-initials"></span>
            </div> -->
            <div class="ms-3">
                <div><strong id="offcanvas-ticket-creator"></strong></div>
            </div>
        </div>
        <div class="mb-4">
            <p class="mb-1" id="offcanvas-ticket-assignee"></p>
            <p class="mb-1" id="offcanvas-ticket-description"></p>
            <p class="mb-0">Warm Regards,</p>
        </div>

        <!-- Conversation History -->
        <div class="conversation-area mb-4" style="max-height: 200px; overflow-y: auto;">
            <h6 class="text-muted mb-3">Conversation History</h6>
            <div id="conversation-messages">
                <!-- Messages will be loaded here -->
            </div>
        </div>

        <div class="position-absolute bottom-0 start-0 w-100 px-4 pb-3" style="background: #fff;">
            <form id="messageForm" class="d-flex align-items-center">
                <input type="text" class="form-control me-2" id="messageInput" placeholder="Type a message..." required>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="css/conversation.css">
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