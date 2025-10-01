console.log("Script Loaded");

// --- Confirmation Functions --- //

// Confirm delete action
function confirmDelete(event) {
  event.preventDefault(); // Prevent the form from submitting immediately

  // Retrieve the form element
  var form = event.target;

  // Get the user ID and username from hidden inputs
  var userId = form.querySelector('input[name="id"]').value;
  var username = form.querySelector('input[name="username"]').value;

  // Show confirmation dialog
  var confirmation = confirm(
    `Are you sure you want to delete "[${userId}]${username}"?`
  );
  if (confirmation) {
    form.submit(); // Submit the form if confirmed
  }

  // If canceled, do nothing
  return false;
}

// Confirm edit action
function confirmEdit() {
  return confirm("Update user with adjusted values?");
}

// Confirm password reset action
function confirmPwdReset() {
  return confirm("Are you sure you want to reset your password?");
}

// Enable ESC key for closing alerts
function initializeAlertCloseOnEsc() {
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      const alerts = document.querySelectorAll(".alert-dismissible");
      alerts.forEach((alert) => {
        alert.classList.remove("show");
        setTimeout(() => alert.remove(), 150);
      });
    }
  });
}

// --- Table Filtering --- //

function filterTable() {
  const searchInput = document.getElementById("searchInput");
  const table = document.querySelector(".table-users");
  const rows = table.querySelectorAll("tbody tr");

  searchInput.addEventListener("input", function () {
    const query = searchInput.value.toLowerCase();

    rows.forEach((row) => {
      const cells = row.querySelectorAll("td:nth-child(4)"); // Does not start at index 0
      let match = false;

      cells.forEach((cell) => {
        if (cell.textContent.toLowerCase().includes(query)) {
          match = true;
        }
      });

      row.style.display = match ? "" : "none";
    });
  });
}

// --- Bootstrap Initializations --- //

// Enable popovers
const popoverTriggerList = document.querySelectorAll(
  '[data-bs-toggle="popover"]'
);
const popoverList = [...popoverTriggerList].map(
  (popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl)
);

// Enable tooltips
const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

// --- DOM Content Loaded --- //

document.addEventListener("DOMContentLoaded", function () {
  filterTable(); // Initialize table filtering
  initializeAlertCloseOnEsc();
});
