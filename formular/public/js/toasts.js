document.addEventListener("DOMContentLoaded", function () {
  const toastTrigger = document.getElementById("dynamicToastBtn");
  const toastElements = document.querySelectorAll(".toast");
  let currentToastIndex = 0;

  if (toastTrigger && toastElements.length > 0) {
    // Set initial button text for the first toast
    toastTrigger.textContent =
      toastElements[currentToastIndex].getAttribute("data-button-text");

    toastTrigger.addEventListener("click", () => {
      // Get the current toast element
      const toastElement = toastElements[currentToastIndex];

      // Check the autohide value from data attribute
      const autohideValue =
        toastElement.getAttribute("data-bs-autohide") === "true";

      const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastElement, {
        autohide: autohideValue,
      });

      toastBootstrap.show();

      // Update index for next toast
      currentToastIndex = (currentToastIndex + 1) % toastElements.length;

      // Update button text to the next toast's button text
      toastTrigger.textContent =
        toastElements[currentToastIndex].getAttribute("data-button-text");
    });
  }
});
