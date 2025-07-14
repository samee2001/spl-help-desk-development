
        document.addEventListener('DOMContentLoaded', function() {
            const editRuleModal = document.getElementById('editRuleModal');
            const ruleLabelInput = document.getElementById('ruleLabelInput');

            // Listen for when the modal is about to be shown
            editRuleModal.addEventListener('show.bs.modal', function (event) {
                // Get the button that triggered the modal
                const button = event.relatedTarget;
                // Extract info from data-bs-whatever attributes
                const ruleLabel = button.getAttribute('data-rule-label');

                // Update the modal's content
                ruleLabelInput.value = ruleLabel;

                // For a real application, you'd fetch the complete rule data (rules, actions)
                // from a backend API using the ruleLabel or an ID passed via data-attribute,
                // then dynamically populate all other fields in the modal.
            });

            // Example for dynamically adding a rule row (simplified)
            const addRuleBtnModal = document.querySelector('.add-rule-btn-modal');
            addRuleBtnModal.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default link behavior
                const rulesSection = document.querySelector('#editRuleForm > div:nth-child(2)'); // Target the rules section

                const newRuleRow = `
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-md-4">
                            <select class="form-select">
                                <option selected>Summary</option>
                                <option value="1">Description</option>
                                <option value="2">Category</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select">
                                <option selected>is</option>
                                <option value="1">contains</option>
                                <option value="2">starts with</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" value="">
                        </div>
                        <div class="col-md-1 d-flex justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 text-secondary delete-rule-row">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
                // Insert the new row right before the '+ Add rule' link
                addRuleBtnModal.insertAdjacentHTML('beforebegin', newRuleRow);
            });

            // Example for dynamically adding an action row (simplified)
            const addActionBtnModal = document.querySelector('.add-action-btn-modal');
            addActionBtnModal.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default link behavior
                const actionsSection = document.querySelector('#editRuleForm > div:nth-child(3)'); // Target the actions section

                const newActionRow = `
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-md-11">
                            <input type="text" class="form-control" placeholder="New Action Name">
                        </div>
                        <div class="col-md-1 d-flex justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 text-secondary delete-rule-row">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `;
                // Insert the new row right before the '+ Add action' link
                addActionBtnModal.insertAdjacentHTML('beforebegin', newActionRow);
            });

            // Handle dynamically added delete buttons for rules/actions
            document.getElementById('editRuleForm').addEventListener('click', function(event) {
                if (event.target.closest('.delete-rule-row')) {
                    event.target.closest('.row.g-2').remove();
                }
            });
        });
    