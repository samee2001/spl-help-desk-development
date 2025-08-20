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

      // Load conversation history for this ticket
      loadConversationHistory(currentTicketId);
    });
  });

  // Function to load conversation history
  function loadConversationHistory(ticketId) {
    fetch(
      `api/email_conversation.php?ticket_id=${encodeURIComponent(ticketId)}`
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          displayConversation(data.conversation);
        }
      })
      .catch((error) => {
        console.error("Error loading conversation:", error);
      });
  }

  // Function to display conversation messages
  function displayConversation(conversation) {
    const messagesContainer = document.getElementById("conversation-messages");
    if (!messagesContainer) return;

    if (conversation.length === 0) {
      messagesContainer.innerHTML =
        '<p class="text-muted text-center">No messages yet. Start the conversation!</p>';
      return;
    }

    let messagesHTML = "";
    conversation.forEach((msg) => {
      const messageClass = msg.is_creator ? "text-end" : "text-start";
      const bubbleClass = msg.is_creator ? "bg-primary text-white" : "bg-light";
      const time = new Date(msg.sent_at).toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
      });

      messagesHTML += `
        <div class="mb-2 ${messageClass}">
          <div class="d-inline-block p-2 rounded ${bubbleClass}" style="max-width: 80%;">
            <div class="small">${msg.message}</div>
            <div class="small ${
              msg.is_creator ? "text-white-50" : "text-muted"
            }">${time}</div>
          </div>
        </div>
      `;
    });

    messagesContainer.innerHTML = messagesHTML;
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
  }

  // Handle message form submission
  document
    .getElementById("messageForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const messageInput = document.getElementById("messageInput");
      const message = messageInput.value.trim();

      if (!message || !currentTicketId) return;

      // Send message
      fetch("api/email_conversation.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          ticket_id: currentTicketId,
          message: message,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            messageInput.value = "";
            // Reload conversation to show new message
            loadConversationHistory(currentTicketId);
          } else {
            alert("Failed to send message: " + (data.error || "Unknown error"));
          }
        })
        .catch((error) => {
          console.error("Error sending message:", error);
          alert("Error sending message. Please try again.");
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

  document.querySelectorAll(".ticket-row").forEach(function (row) {
    row.addEventListener("click", function () {
      var ticketId = this.getAttribute("data-id");
      // Load the form via AJAX
      fetch(
        "components/side_vertical_form.php?ticket_id=" +
          encodeURIComponent(ticketId)
      )
        .then((response) => response.text())
        .then((html) => {
          document.getElementById("right-vertical-form-container").innerHTML =
            html;
        });
    });
  });

  // Delegate submit handling for the quick form so it works after dynamic loads
  document.addEventListener("submit", function (e) {
    var form = e.target;
    if (form && form.id === "offcanvasRightForm") {
      e.preventDefault();

      var formData = new FormData(form);
      fetch(form.action, { method: "POST", body: formData })
        .then(function (res) {
          return res.json();
        })
        .then(function (data) {
          if (typeof showToast === "function") {
            showToast(
              data.message || "Unknown response",
              data.status === "success"
            );
          }
          if (data.status === "success") {
            var offcanvasEl = document.getElementById("ticketDetailsOffcanvas");
            if (offcanvasEl && window.bootstrap && bootstrap.Offcanvas) {
              var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
              offcanvas.hide();
            }
            setTimeout(function () {
              location.reload();
            }, 2000);
          }
        })
        .catch(function () {
          if (typeof showToast === "function") {
            showToast("An error occurred. Please try again.", false);
          }
        });
    }
  });
});
