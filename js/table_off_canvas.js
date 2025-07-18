document.addEventListener('DOMContentLoaded', function() {
    //let acceptedTickets = new Set();
    document.querySelectorAll('.ticket-row').forEach(function(row) {
        row.addEventListener('click', function() {
            document.getElementById('offcanvas-ticket-id').textContent = row.getAttribute('data-id');
            document.getElementById('offcanvas-ticket-summary').textContent = row.getAttribute('data-summary');
            document.getElementById('offcanvas-ticket-creator').textContent = 'From: ' + row.getAttribute('data-creator');
            var assignee = row.getAttribute('data-assignee');
            document.getElementById('offcanvas-ticket-assignee').textContent = 'Dear ' + assignee;
            document.getElementById('offcanvas-ticket-description').textContent = row.getAttribute('data-description');
            // Optionally set initials
            var creator = row.getAttribute('data-creator') || '';
            var initials = creator.split(' ').map(w => w[0]).join('').toUpperCase();
            document.getElementById('offcanvas-ticket-initials').textContent = initials || 'SS';

            // Reset Accept button state every time a new row is clicked
            // var acceptBtn = document.getElementById('acceptBtn');
            // if (acceptBtn) {
            //     acceptBtn.textContent = 'Accept';
            //     acceptBtn.disabled = false;
            // }
        });
    });

    // Accept button logic
    var acceptBtn = document.getElementById('acceptBtn');
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            acceptBtn.textContent = 'Accepted';
            acceptBtn.disabled = true;
        });
    }
});

