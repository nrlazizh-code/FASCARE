document.addEventListener("DOMContentLoaded", () => {
  // Toggle Sidebar (Desktop & Mobile)
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", () => {
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle("open");
      } else {
        sidebar.classList.toggle("collapsed");
      }
    });
  }

  // Toggle Dropdown Notifikasi
  const notifToggle = document.getElementById("notifToggle");
  const notifDropdown = document.getElementById("notifDropdown");
  if (notifToggle && notifDropdown) {
    notifToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      notifDropdown.classList.toggle("show");
    });
    document.addEventListener("click", () => {
      notifDropdown.classList.remove("show");
    });
  }

  // Preview Gambar saat Upload
  const fotoInput = document.getElementById("fotoInput");
  const fotoPreview = document.getElementById("fotoPreview");
  if (fotoInput && fotoPreview) {
    fotoInput.addEventListener("change", function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          fotoPreview.src = e.target.result;
          fotoPreview.style.display = "block";
        };
        reader.readAsDataURL(file);
      }
    });
  }
});
