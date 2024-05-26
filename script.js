document.addEventListener("DOMContentLoaded", () => {
  const body = document.querySelector("body"),
        sidebar = body.querySelector(".sidebar"),
        toggle = body.querySelector(".toggle"),
        searchBtn = body.querySelector(".search-box"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text");

  // Load dark mode state from local storage
  if (localStorage.getItem("darkMode") === "enabled") {
      body.classList.add("dark");
      modeText.innerText = "Light Mode";
  } else {
      body.classList.remove("dark");
      modeText.innerText = "Dark Mode";
  }

  // Load sidebar state from local storage
  if (localStorage.getItem("sidebar") === "open") {
      sidebar.classList.remove("close");
  } else {
      sidebar.classList.add("close");
  }

  toggle.addEventListener("click", () => {
      sidebar.classList.toggle("close");

      // Save sidebar state to local storage
      if (sidebar.classList.contains("close")) {
          localStorage.setItem("sidebar", "closed");
      } else {
          localStorage.setItem("sidebar", "open");
      }
  });

  searchBtn.addEventListener("click", () => {
      sidebar.classList.remove("close");
      localStorage.setItem("sidebar", "open"); // Ensure the sidebar state is saved as open
  });

  modeSwitch.addEventListener("click", () => {
      body.classList.toggle("dark");

      // Save dark mode state to local storage
      if (body.classList.contains("dark")) {
          localStorage.setItem("darkMode", "enabled");
          modeText.innerText = "Light Mode";
      } else {
          localStorage.setItem("darkMode", "disabled");
          modeText.innerText = "Dark Mode";
      }
  });
});
