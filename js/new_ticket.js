document.addEventListener("DOMContentLoaded", function () {
  var attachBtn = document.getElementById("attachBtn");
  var fileInput = document.getElementById("fileInput");
  var fileList = document.getElementById("fileList");
  // Store files in a DataTransfer object for easy manipulation
  var dt = new DataTransfer();

  function renderFileList() {
    fileList.innerHTML = "";
    for (let i = 0; i < dt.files.length; i++) {
      const file = dt.files[i];
      const fileDiv = document.createElement("div");
      fileDiv.className =
        "d-flex align-items-center mb-1 bg-light rounded px-2 py-1";
      fileDiv.innerHTML = `
                    <span class="me-2"><i class="fas fa-file"></i></span>
                    <span class="flex-grow-1 text-truncate" style="max-width: 180px;">${file.name}</span>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2 remove-file" data-index="${i}" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                `;
      fileList.appendChild(fileDiv);
    }
    // Attach remove event
    fileList.querySelectorAll(".remove-file").forEach((btn) => {
      btn.addEventListener("click", function () {
        const idx = parseInt(this.getAttribute("data-index"));
        dt.items.remove(idx);
        fileInput.files = dt.files;
        renderFileList();
      });
    });
  }

  if (attachBtn && fileInput) {
    attachBtn.addEventListener("click", function () {
      fileInput.click();
    });

    fileInput.addEventListener("change", function () {
      const allowedExtensions = ["pdf", "docx"];
      const allowedImageTypes = [
        "image/jpeg",
        "image/png",
        "image/gif",
        "image/bmp",
        "image/webp",
      ];
      let valid = true;
      // Add new files to DataTransfer if valid
      for (let i = 0; i < fileInput.files.length; i++) {
        const file = fileInput.files[i];
        const ext = file.name.split(".").pop().toLowerCase();
        if (
          allowedExtensions.includes(ext) ||
          allowedImageTypes.includes(file.type)
        ) {
          // Prevent duplicates
          let duplicate = false;
          for (let j = 0; j < dt.files.length; j++) {
            if (
              dt.files[j].name === file.name &&
              dt.files[j].size === file.size
            ) {
              duplicate = true;
              break;
            }
          }
          if (!duplicate) dt.items.add(file);
        } else {
          valid = false;
          break;
        }
      }
      if (!valid) {
        alert("Only PDF, DOCX, and image files are allowed.");
      }
      fileInput.value = "";
      fileInput.files = dt.files;
      renderFileList();
    });
  }
});
