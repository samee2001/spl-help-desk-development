   <!-- New Ticket Modal -->
   <?php
    include 'configs/db_connection.php';

    ?>
   <div id="newTicketModal" class="modal" style="display:none;">
       <div class="modal-content">
           <span class="close-modal" id="closeModalBtn">&times;</span>
           <h2>Create New Ticket</h2>
           <form>
               <label for="summary">Summary:</label>
               <input type="text" id="summary" name="summary" required>
               <label for="assignee">Assignee:</label>
               <input type="text" id="assignee" name="assignee">
               <label for="creator">Creator:</label>
               <input type="text" id="creator" name="creator">
               <label for="organization">Organization:</label>
               <select id="organization" name="organization">
                   <option value="">Select organization</option>
                   <?php
                    $result = mysqli_query($conn, "SELECT org_name FROM tb_organization");
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . htmlspecialchars($row['org_name']) . '">' . htmlspecialchars($row['org_name']) . '</option>';
                        }
                    }
                    ?>
               </select>
               <label for="priority">Priority:</label>
               <select id="priority" name="priority">
                   <option value="high">High</option>
                   <option value="medium">Medium</option>
                   <option value="low">Low</option>
               </select>
               <label for="category">Category:</label>
               <input type="text" id="category" name="category">
               <label for="status">Status:</label>
               <select id="status" name="status">
                   <option value="open">Open</option>
                   <option value="waiting">Waiting</option>
                   <option value="closed">Closed</option>
               </select>
               <button type="submit" class="btn-primary">Submit</button>
           </form>
       </div>
   </div>

   <script>
       document.querySelector('.new-ticket-btn').onclick = function() {
           document.getElementById('newTicketModal').style.display = 'block';
       };
       document.getElementById('closeModalBtn').onclick = function() {
           document.getElementById('newTicketModal').style.display = 'none';
       };
       window.onclick = function(event) {
           var modal = document.getElementById('newTicketModal');
           if (event.target == modal) {
               modal.style.display = 'none';
           }
       };
   </script>