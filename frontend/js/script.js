
      // Add click event to cards for fullscreen
      document.querySelectorAll(".card-wrapper").forEach((card) => {
        card.addEventListener("click", function () {
          const img = this.querySelector("img");
          const modal = document.getElementById("modalOverlay");
          const modalImg = document.getElementById("modalImage");

          if (!img || !modal || !modalImg) return;

          modalImg.src = img.src;
          modal.classList.add("active");
          document.body.style.overflow = "hidden";
        });
      });

      // Close modal on close button click
      const closeModal = document.querySelector(".close-modal");
      if (closeModal) {
        closeModal.addEventListener("click", function () {
        const modal = document.getElementById("modalOverlay");
        if (!modal) return;
        modal.classList.remove("active");
        document.body.style.overflow = "auto";
        });
      }

      // Close modal on overlay click
      const modalOverlay = document.getElementById("modalOverlay");
      if (modalOverlay) {
        modalOverlay.addEventListener("click", function (e) {
          if (e.target === this) {
            this.classList.remove("active");
            document.body.style.overflow = "auto";
          }
        });
      }

      // Close modal on Escape key
      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
          const modal = document.getElementById("modalOverlay");
          if (!modal) return;
          modal.classList.remove("active");
          document.body.style.overflow = "auto";
        }
      });

      // Redirect to login page when the sign-in button is clicked
      const signInBtn = document.getElementById("signinBtn");
      if (signInBtn) {
        signInBtn.addEventListener("click", function () {
          window.location.href = window.location.pathname.includes("/pages/") ? "Login.html" : "pages/Login.html";
        });
      }

      // Shuffle cards when page loads
      window.addEventListener("DOMContentLoaded", function () {
        // Cards are ready
      });
