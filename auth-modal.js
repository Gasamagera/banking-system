document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("auth-modal");
  const loginForm = document.getElementById("login-form");
  const signupForm = document.getElementById("signup-form");
  const loginTab = document.getElementById("login-tab");
  const signupTab = document.getElementById("signup-tab");
  const loginBtn = document.querySelector(".login-btn");
  const closeBtn = document.getElementById("close-btn"); // FIXED ID

  document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const showLogin = urlParams.get("show");

    if (showLogin === "login") {
      const signupForm = document.getElementById("signup-form");
      const loginForm = document.getElementById("login-form");

      if (signupForm) signupForm.classList.add("hidden");
      if (loginForm) loginForm.classList.remove("hidden");
    }
  });

  // Open modal
  if (loginBtn) {
    loginBtn.addEventListener("click", function (e) {
      e.preventDefault();
      modal.style.display = "flex";
      showForm("login");
    });
  }

  // Close modal with ×
  if (closeBtn) {
    closeBtn.addEventListener("click", () => {
      modal.style.display = "none";
    });
  }

  // Close modal when clicking outside the modal box
  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  // Switch to login
  loginTab.addEventListener("click", () => showForm("login"));

  // Switch to signup
  signupTab.addEventListener("click", () => showForm("signup"));

  // Toggle form display
  function showForm(form) {
    if (form === "login") {
      loginForm.classList.remove("hidden");
      signupForm.classList.add("hidden");
      loginTab.classList.add("active");
      signupTab.classList.remove("active");
    } else {
      signupForm.classList.remove("hidden");
      loginForm.classList.add("hidden");
      signupTab.classList.add("active");
      loginTab.classList.remove("active");
    }
  }
});
