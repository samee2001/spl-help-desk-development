document.addEventListener("DOMContentLoaded", function () {
  let currentTicketId = null;

  // Set currentTicketId when a row is clicked
  document.querySelectorAll(".ticket-row").forEach(function (row) {
    row.addEventListener("click", function () {
      currentTicketId = row.getAttribute("data-id");
      document.getElementById("offcanvas-ticket-id").textContent =
        currentTicketId;
      document.getElementById("offcanvas-ticket-summary").textContent =
        row.getAttribute("data-summary");
      document.getElementById("offcanvas-ticket-creator").textContent =
        "From: " + row.getAttribute("data-creator");
      var assignee = row.getAttribute("data-assignee");
      document.getElementById("offcanvas-ticket-assignee").textContent =
        "Dear " + assignee;
      document.getElementById("offcanvas-ticket-description").textContent =
        row.getAttribute("data-description");
      // Optionally set initials
      var creator = row.getAttribute("data-creator") || "";
      var initials = creator
        .split(" ")
        .map((w) => w[0])
        .join("")
        .toUpperCase();
      document.getElementById("offcanvas-ticket-initials").textContent =
        initials || "SS";

      // Reset Accept button state every time a new row is clicked
      var acceptBtn = document.getElementById("acceptBtn");
      if (acceptBtn) {
        acceptBtn.textContent = "Accept";
        acceptBtn.disabled = false;
      }
    });
  });

  // Function to handle hiding the offcanvas and showing the modal
  function handleOffcanvasAndModal() {
    var offcanvasElement = document.getElementById("ticketDetailsOffcanvas");
    var offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);

    // If the offcanvas is shown, hide it
    if (offcanvasInstance && offcanvasInstance._isShown) {
      offcanvasInstance.hide();
    }

    // Check if the offcanvas is hidden (not visible)
    offcanvasElement.addEventListener("hidden.bs.offcanvas", function () {
      var myModal = new bootstrap.Modal(
        document.getElementById("popupMessage")
      );
      myModal.show(); // Show the modal when offcanvas is hidden
    });
  }

  // Accept button logic
  var acceptBtn = document.getElementById("acceptBtn");
  if (acceptBtn) {
    acceptBtn.addEventListener("click", function () {
      // Update ticket status
      updateTicketStatus(currentTicketId, "Accepted");

      // Handle offcanvas hide and show modal
      handleOffcanvasAndModal();
    });
  }

  // Dropdown menu logic
  document
    .querySelectorAll(".dropdown-menu .dropdown-item")
    .forEach(function (item) {
      item.addEventListener("click", function (e) {
        e.preventDefault();
        console.log("Dropdown item clicked");

        // Handle offcanvas hide and show modal
        handleOffcanvasAndModal();

        // Get status and update ticket status
        let status = item.getAttribute("data-status");
        updateTicketStatus(currentTicketId, status);
      });
    });
  // Function to update the ticket status
  function updateTicketStatus(ticketId, status) {
    fetch("api/accept_ticket.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body:
        "tk_id=" +
        encodeURIComponent(ticketId) +
        "&status_name=" +
        encodeURIComponent(status),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          if (status === "Accepted") {
            var acceptBtn = document.getElementById("acceptBtn");
            if (acceptBtn) {
              acceptBtn.textContent = "Accepted";
              acceptBtn.disabled = true;
            }
          }
          // Optionally update the table row or show a message here
        } else {
          alert(
            "Failed to update ticket status: " + (data.error || "Unknown error")
          );
        }
      })
      .catch((error) => {
        alert("Error updating ticket status: " + error);
      });
  }
});
