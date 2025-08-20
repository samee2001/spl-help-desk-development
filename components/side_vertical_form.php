<?php
// Ensure you have a mysqli $conn before this include

include '../configs/db_connection.php';

// Normalize ticket_id from POST/GET (fallback to 0)
$ticket_id = 0;
if (isset($_POST['ticket_id'])) {
    $ticket_id = intval($_POST['ticket_id']);
} elseif (isset($_GET['ticket_id'])) {
    $ticket_id = intval($_GET['ticket_id']);
} elseif (isset($ticket_id)) {
    // fallback if parent scope sets it
    $ticket_id = intval($ticket_id);
}

// Fetch current ticket values
$currentTicket = null;
if ($ticket_id > 0) {
    // select the actual column names present in tb_ticket
    $stmt = $conn->prepare("
        SELECT org_id, ur_id, tk_assignee, tk_priority, cat_id
        FROM tb_ticket
        WHERE tk_id = ?
    ");
    // single integer param
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentTicket = $result->fetch_assoc() ?: null;
    $stmt->close();
}
?>

<aside class="right-vertical-panel" id="offcanvasRightPanel" aria-label="Ticket actions and form">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Ticket Actions / Quick Form</strong>
    </div>

    <form id="offcanvasRightForm" method="post" action="api/submit_quick_form.php">
        <input type="hidden" id="right_form_ticket_id" name="ticket_id" value="<?= htmlspecialchars((string)$ticket_id) ?>">

        <!-- Hidden current values for JS (use actual keys returned by query) -->
        <input type="hidden" id="current_org" value="<?= isset($currentTicket['org_id']) ? htmlspecialchars((string)$currentTicket['org_id']) : '' ?>">
        <input type="hidden" id="current_contact" value="<?= isset($currentTicket['ur_id']) ? htmlspecialchars((string)$currentTicket['ur_id']) : '' ?>">
        <input type="hidden" id="current_assignee" value="<?= isset($currentTicket['tk_assignee']) ? htmlspecialchars((string)$currentTicket['tk_assignee']) : '' ?>">
        <input type="hidden" id="current_priority" value="<?= isset($currentTicket['tk_priority']) ? htmlspecialchars((string)$currentTicket['tk_priority']) : '' ?>">
        <input type="hidden" id="current_category" value="<?= isset($currentTicket['cat_id']) ? htmlspecialchars((string)$currentTicket['cat_id']) : '' ?>">

        <!-- Organization -->
        <div class="mb-3">
            <label for="organization" class="form-label">Organization</label>
            <select class="form-select" id="organization" name="organization" required>
                <option value="">Select organization</option>
                <?php
                if ($rs = $conn->query("SELECT org_id, org_name FROM tb_organization")) {
                    while ($row = $rs->fetch_assoc()) {
                        $selected = ($currentTicket && (string)$currentTicket['org_id'] === (string)$row['org_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars((string)$row['org_id']) . '" ' . $selected . '>'
                            . htmlspecialchars($row['org_name']) . '</option>';
                    }
                    $rs->close();
                }
                ?>
            </select>
        </div>

        <!-- Contact -->
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <select class="form-select" id="contact" name="contact" required>
                <option value="">Select contact</option>
                <?php
                if ($rs = $conn->query("SELECT ur_id, ur_name FROM tb_user")) {
                    while ($row = $rs->fetch_assoc()) {
                        $selected = ($currentTicket && (string)$currentTicket['ur_id'] === (string)$row['ur_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars((string)$row['ur_id']) . '" ' . $selected . '>'
                            . htmlspecialchars($row['ur_name']) . '</option>';
                    }
                    $rs->close();
                }
                ?>
            </select>
        </div>

        <!-- Assignee -->
        <div class="mb-3">
            <label for="assignee" class="form-label">Assignee</label>
            <select class="form-select" id="assignee" name="assignee" required>
                <option value="">Select assignee</option>
                <?php
                if ($rs = $conn->query("SELECT emp_id, emp_email FROM tb_employee")) {
                    while ($row = $rs->fetch_assoc()) {
                        $selected = ($currentTicket && (string)$currentTicket['tk_assignee'] === (string)$row['emp_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars((string)$row['emp_id']) . '" ' . $selected . '>'
                            . htmlspecialchars($row['emp_email']) . '</option>';
                    }
                    $rs->close();
                }
                ?>
            </select>
        </div>

        <!-- Priority -->
        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <select class="form-select" id="priority" name="priority" required>
                <option value="low" <?= ($currentTicket && strtolower($currentTicket['tk_priority'] ?? '') === 'low') ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= ($currentTicket && strtolower($currentTicket['tk_priority'] ?? '') === 'medium') ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= ($currentTicket && strtolower($currentTicket['tk_priority'] ?? '') === 'high') ? 'selected' : '' ?>>High</option>
            </select>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
                <option value="">Select category</option>
                <?php
                if ($rs = $conn->query("SELECT cat_id, cat_name FROM tb_category")) {
                    while ($row = $rs->fetch_assoc()) {
                        $selected = ($currentTicket && (string)$currentTicket['cat_id'] === (string)$row['cat_id']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars((string)$row['cat_id']) . '" ' . $selected . '>'
                            . htmlspecialchars($row['cat_name']) . '</option>';
                    }
                    $rs->close();
                }
                ?>
            </select>
        </div>

        <div class="d-flex gap-3 justify-content-center">
            <button type="submit" class="btn btn-outline-secondary btn-sm"
                onmouseover="this.style.backgroundColor='#34ce57'; this.style.color='white'; this.style.borderColor='#34ce57';"
                onmouseout="this.style.backgroundColor=''; this.style.color=''; this.style.borderColor='';">Update</button>
            <button type="reset" class="btn btn-outline-secondary btn-sm"
                onmouseover="this.style.backgroundColor='red'; this.style.color='white'; this.style.borderColor='red';"
                onmouseout="this.style.backgroundColor=''; this.style.color=''; this.style.borderColor='';">Reset</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="offcanvas">Close</button>
        </div>
    </form>
    <script src="js/apply_current_values.js">
        // (function() {
        //     function setSelectValue(id, value) {
        //         var el = document.getElementById(id);
        //         if (!el) return;
        //         if (value !== undefined && value !== null && value !== '') {
        //             el.value = String(value);
        //             if (el.value !== String(value)) {
        //                 var opt = el.querySelector('option[value="' + String(value).replace(/"/g, '\\"') + '"]');
        //                 if (opt) opt.selected = true;
        //             }
        //         }
        //     }

        //     function applyCurrentValues() {
        //         setSelectValue("organization", document.getElementById("current_org")?.value || "");
        //         setSelectValue("contact", document.getElementById("current_contact")?.value || "");
        //         setSelectValue("assignee", document.getElementById("current_assignee")?.value || "");
        //         setSelectValue("priority", document.getElementById("current_priority")?.value || "");
        //         setSelectValue("category", document.getElementById("current_category")?.value || "");
        //     }

        //     // On full page load
        //     document.addEventListener("DOMContentLoaded", applyCurrentValues);

        //     // When offcanvas is shown (works if this partial is injected)
        //     var panel = document.getElementById("offcanvasRightPanel");
        //     if (panel) {
        //         panel.addEventListener("shown.bs.offcanvas", applyCurrentValues);
        //     }
        // })();
    </script>



    
</aside>