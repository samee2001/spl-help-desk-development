 // populate offcanvas content and right form when a ticket row is clicked
            (function(){
                function populateOffcanvasFromRow(row) {
                    if (!row) return;
                    const id = row.dataset.id || '';
                    const summary = row.dataset.summary || '';
                    const assignee = row.dataset.assignee || '';
                    const priority = row.dataset.priority || '';
                    const description = row.dataset.description || '';

                    // header / main fields
                    const headerId = document.getElementById('offcanvas-ticket-id');
                    const headerSummary = document.getElementById('offcanvas-ticket-summary');
                    const headerAssignee = document.getElementById('offcanvas-ticket-assignee');
                    const headerDescription = document.getElementById('offcanvas-ticket-description');

                    if (headerId) headerId.textContent = id;
                    if (headerSummary) headerSummary.textContent = summary;
                    if (headerAssignee) headerAssignee.textContent = assignee;
                    if (headerDescription) headerDescription.textContent = description;

                    // right form
                    const fTicketId = document.getElementById('right_form_ticket_id');
                    const fSummary = document.getElementById('right_form_summary');
                    const fAssignee = document.getElementById('right_form_assignee');
                    const fPriority = document.getElementById('right_form_priority');
                    const fDescription = document.getElementById('right_form_description');

                    if (fTicketId) fTicketId.value = id;
                    if (fSummary) fSummary.value = summary;
                    if (fAssignee) fAssignee.value = assignee;
                    if (fPriority) fPriority.value = priority;
                    if (fDescription) fDescription.value = description;
                }

                // attach click handlers to rows (also handles rows added later if necessary)
                document.querySelectorAll('.ticket-row').forEach(function(r){
                    r.addEventListener('click', function(){
                        populateOffcanvasFromRow(r);
                    });
                });

                // reset right form button
                const resetBtn = document.getElementById('rightFormReset');
                if (resetBtn) resetBtn.addEventListener('click', function(){ document.getElementById('offcanvasRightForm').reset(); });

                // optional: load conversation/messages via AJAX when a ticket is opened
                // (implement fetch to your api/email_conversation.php or similar if needed)
            })();