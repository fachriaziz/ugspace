document.addEventListener("DOMContentLoaded", function () {
  // Autohide alerts
  const alerts = document.querySelectorAll(".alert");
  if (alerts.length > 0) {
    setTimeout(() => {
      alerts.forEach((alert) => {
        alert.style.transition = "opacity 0.3s ease";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 300);
      });
    }, 5000);
  }

  const searchInput = document.getElementById("searchInput");
  const roomsTable = document.getElementById("roomsTable");

  if (searchInput && roomsTable) {
    searchInput.addEventListener("input", function () {
      const filter = this.value.toLowerCase().trim();
      const rows = roomsTable.querySelectorAll("tbody tr");

      rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
  }

  const slots = document.querySelectorAll(".slot-available");
  const bookingForm = document.getElementById("bookingForm");
  const startHourInput = document.getElementById("startHour");
  const displayTime = document.getElementById("displayTime");

  if (slots.length > 0 && bookingForm) {
    slots.forEach((slot) => {
      slot.addEventListener("click", function () {
        const hour = this.dataset.hour;

        startHourInput.value = hour;
        displayTime.textContent = hour.padStart(2, "0") + ":00";

        bookingForm.style.display = "block";

        slots.forEach((s) => s.classList.remove("slot-selected"));

        this.classList.add("slot-selected");

        bookingForm.scrollIntoView({ behavior: "smooth", block: "start" });
      });
    });
  }

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const targetId = this.getAttribute("href");
      if (targetId === "#") return;

      const target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
});
