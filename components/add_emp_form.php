<div class="form-whole">
    <form action="employee_registration.php" method="POST" id="register-form">
        <div class="form_style">
            <h2 class="text-center mb-4 text-primary">Add Employee</h2>

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="reg_emp_name" required class="form-control" placeholder="Enter employee name">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" name="reg_emp_email" required class="form-control" placeholder="Enter employee email">
            </div>

            <div class="mb-3">
                <label for="designation" class="form-label">Designation:</label>
                <input type="text" name="reg_emp_des"  class="form-control" placeholder="Enter employee Designation">
            </div>

            <div class="mb-3">
                <label for="organization" class="form-label">Organization:</label>
                <select class="form-select" id="organization" name="reg_emp_org" required>
                    <option value="">Select Organization</option>
                    <?php
                    // Query to fetch organizations from the tb_organization table
                    $result = mysqli_query($conn, "SELECT org_name,org_id FROM tb_organization");
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . htmlspecialchars($row['org_id']) . '">' . htmlspecialchars($row['org_name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <input type="submit" id="submit" value="Submit" name="register_employees" >
                <input type="button" id="cancel" value="Cancel" onclick="cancelForm()" >
            </div>

        </div>

    </form>
</div>




<script>
    function cancelForm() {
        document.getElementById("register-form").reset(); // Reset the form on Cancel button click
    }
</script>