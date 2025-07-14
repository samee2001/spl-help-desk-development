<?php
include 'configs/db_connection.php';

?>
<!-- Success Alert (hidden by default) -->
<div id="successAlert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
    Assignee updated to Maleesha Dewashan!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th scope="col"><input type="checkbox"></th>
                <th scope="col">ID <i class="fas fa-sort"></i></th>
                <th scope="col">Summary <i class="fas fa-sort"></i></th>
                <th scope="col">Assignee <i class="fas fa-sort"></i></th>
                <th scope="col">Creator <i class="fas fa-sort"></i></th>
                <th scope="col">Organization <i class="fas fa-sort"></i></th>
                <th scope="col">Priority <i class="fas fa-sort-up"></i></th>
                <th scope="col">Category <i class="fas fa-sort"></i></th>
                <th scope="col">Status <i class="fas fa-sort"></i></th>
                <th scope="col">Created <i class="fas fa-sort"></i></th>
                <th scope="col">Updated <i class="fas fa-sort"></i></th>
                <th scope="col">Due Date <i class="fas fa-sort"></i></th>
            </tr>
        </thead>
        <tbody>
            <?php

            // Database connection (adjust as needed)

            // Fetch all tickets
            $result = $conn->query("SELECT * FROM tb_ticket ORDER BY tk_created_at DESC");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Adjust these fields to match your table columns if needed

                    $id = $row['tk_id'];
                    $summary = htmlspecialchars($row['tk_summary']);
                    $assignee = isset($row['tk_assignee']) ? htmlspecialchars($row['tk_assignee']) : '';
                    $creator = isset($row['ur_id']) ? htmlspecialchars($row['ur_id']) : '';
                    $organization = htmlspecialchars($row['org_id']); // Replace with JOIN for name if needed
                    $priority = htmlspecialchars($row['tk_priority']);
                    $category = htmlspecialchars($row['cat_id']); // Replace with JOIN for name if needed
                    $status = htmlspecialchars($row['st_id']);
                    $created = date('M d, Y', strtotime($row['tk_created_at']));
                    $updated = isset($row['tk_updated_at']) ? date('M d, Y', strtotime($row['tk_updated_at'])) : '';
                    $due_date = isset($row['due_date']) && $row['due_date'] ? date('M d, Y', strtotime($row['due_date'])) : '';

                    // Priority badge color
                    $priorityBadge = 'bg-secondary';
                    if (strtolower($priority) === 'high') $priorityBadge = 'bg-danger';
                    elseif (strtolower($priority) === 'medium') $priorityBadge = 'bg-warning text-dark';
                    elseif (strtolower($priority) === 'low') $priorityBadge = 'bg-success';

                    echo "<tr class='ticket-row' data-bs-toggle='offcanvas' data-bs-target='#ticketDetailsOffcanvas' aria-controls='ticketDetailsOffcanvas'>";
                    echo "<td><input type='checkbox'></td>";
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
                    echo "<td><a href='#' class='text-decoration-none'>{$due_date}</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12'>No tickets found.</td></tr>";
            }
        
            ?>
            <!-- <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3806</a></td>
                <td><a href="#" class="text-decoration-none">Request for Remote Access to SAP</a></td>
                <td>
                    <a href="#" id="assigneeBtn" class="text-decoration-none">Accept</a>
                </td>
                <td><a href="#" class="text-decoration-none">sumuduw@sadaharitha.com</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">SAP - Maleesha</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">Jun 19, 2025</a></td>
                <td><a href="#" class="text-decoration-none">3d ago</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3707</a></td>
                <td><a href="#" class="text-decoration-none">Maturity date</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">Tharanga Amarasinghe</a></td>
                <td><a href="#" class="text-decoration-none">SPLIT</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-warning text-dark"><i class="fas fa-arrow-right"></i> Medium</span></a>
                </td>
                <td><a href="#" class="text-decoration-none">Software</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">May 30, 2025</a></td>
                <td><a href="#" class="text-decoration-none">May 30, 2025</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3585</a></td>
                <td><a href="#" class="text-decoration-none">CRM</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">Kasun Mendis</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">CRM - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">May 14, 2025</a></td>
                <td><a href="#" class="text-decoration-none">May 14, 2025</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3753</a></td>
                <td><a href="#" class="text-decoration-none">Agreement number : 25083174786AE</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">sanjeewani@sadaharitha.com</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">Jun 06, 2025</a></td>
                <td><a href="#" class="text-decoration-none">Jun 06, 2025</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3881</a></td>
                <td><a href="#" class="text-decoration-none">Agreement</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">Piyumika Premathilaka</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">5d ago</a></td>
                <td><a href="#" class="text-decoration-none">5d ago</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3300</a></td>
                <td><a href="#" class="text-decoration-none">Agreement No. 25002673214P</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">sanjeewani@sadaharitha.com</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">Apr 01, 2025</a></td>
                <td><a href="#" class="text-decoration-none">Apr 01, 2025</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3799</a></td>
                <td><a href="#" class="text-decoration-none">Memo on Guidelines for Converting Deactivate...</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">Nikesha Nilmini</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">Jun 18, 2025</a></td>
                <td><a href="#" class="text-decoration-none">Jun 18, 2025</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3723</a></td>
                <td><a href="#" class="text-decoration-none">Agreement</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">emblip@iy2@sadaharitha.com</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">Jun 02, 2025</a></td>
                <td><a href="#" class="text-decoration-none">Jun 03, 2025</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3877</a></td>
                <td><a href="#" class="text-decoration-none">Agreement Cancellation- 25061975101AG/250...</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">Dilini Madubashini</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">6d ago</a></td>
                <td><a href="#" class="text-decoration-none">6d ago</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3790</a></td>
                <td><a href="#" class="text-decoration-none">Agreement No: 20102947257A</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">sanjeewani@sadaharitha.com</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">Jun 17, 2025</a></td>
                <td><a href="#" class="text-decoration-none">Jun 17, 2025</a></td>
            </tr>
            <tr class="ticket-row" data-bs-toggle="offcanvas" data-bs-target="#ticketDetailsOffcanvas" aria-controls="ticketDetailsOffcanvas">
                <td><input type="checkbox"></td>
                <td><a href="#" class="text-decoration-none">3896</a></td>
                <td><a href="#" class="text-decoration-none">Agreement</a></td>
                <td><a href="#" class="text-decoration-none">Randima De Silva</a></td>
                <td><a href="#" class="text-decoration-none">Piyumika Premathilaka</a></td>
                <td><a href="#" class="text-decoration-none">SPL</a></td>
                <td><a href="#" class="text-decoration-none"><span class="badge bg-danger"><i class="fas fa-arrow-up"></i> High</span></a></td>
                <td><a href="#" class="text-decoration-none">Agreement - Randima</a></td>
                <td><a href="#" class="text-decoration-none">open</a></td>
                <td><a href="#" class="text-decoration-none">3d ago</a></td>
                <td><a href="#" class="text-decoration-none">3d ago</a></td>
            </tr> -->

        </tbody>
    </table>
</div>

<!-- Offcanvas for Ticket Details -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="ticketDetailsOffcanvas" aria-labelledby="ticketDetailsLabel" style="height: 60vh;">
    <div class="offcanvas-header d-flex flex-column align-items-start">
        <div class="w-100 d-flex justify-content-between align-items-center">
            <div>
                <span class="fw-bold">#3906 Online quotation</span>
                <i class="fas fa-pen ms-2" style="cursor:pointer;"></i>
            </div>
            <div>
                <button class="btn btn-outline-success btn-sm me-2">Accept</button>
                <button class="btn btn-outline-secondary btn-sm me-2 " data-bs-dismiss="offcanvas">Close</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Forward</a></li>
                        <li><a class="dropdown-item" href="#">Print</a></li>
                        <li><a class="dropdown-item" href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas-body pt-0" style="overflow-y:auto;">
        <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle bg-warning text-white d-flex justify-content-center align-items-center" style="width:40px;height:40px;font-weight:bold;font-size:1.2rem;">
                SS
            </div>
            <div class="ms-3">
                <div><strong>Savithri Samaranayaka</strong> <span class="text-muted small">3d ago</span></div>
            </div>
        </div>
        <div class="mb-4">
            <p class="mb-1">Dear Mr.Padmal,</p>
            <p class="mb-1">
                Please change Mrs.M L N R Sewwandi's (KT2-1125P-5714F) Mobile number in system (Quotation).<br>
                076 380 7042
            </p>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var assigneeBtn = document.getElementById('assigneeBtn');
        var successAlert = document.getElementById('successAlert');
        if (assigneeBtn) {
            assigneeBtn.addEventListener('click', function(e) {
                e.stopPropagation();

                assigneeBtn.outerHTML = '<a href="#" class="text-decoration-none">Maleesha Dewashan</a>';

                successAlert.classList.remove('d-none');

                setTimeout(function() {
                    if (successAlert) successAlert.classList.add('d-none');
                }, 2000);
            });
        }
    });
</script>