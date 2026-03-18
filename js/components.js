document.addEventListener("DOMContentLoaded", function () {
  const header = document.getElementById("site-header");
  const footer = document.getElementById("site-footer");
  const page = document.body.getAttribute("data-page") || "";

  const navLinks = [
    { href: "index.html", label: "Home", key: "home" },
    { href: "about.html", label: "About", key: "about" },
    { href: "services.html", label: "Services", key: "services" },
    { href: "portfolio.html", label: "Portfolio", key: "portfolio" },
    { href: "contact.html", label: "Contact", key: "contact" }
  ];

  const navHtml = navLinks
    .map(function (link) {
      const activeClass = page === link.key ? "active" : "";
      const ariaCurrent = page === link.key ? ' aria-current="page"' : "";
      return '<li><a href="' + link.href + '" class="' + activeClass + '"' + ariaCurrent + ">" + link.label + "</a></li>";
    })
    .join("");

  if (header) {
    header.innerHTML = `
      <header class="site-header">
        <div class="container nav-container">
          <a href="index.html" class="logo" aria-label="Sandpiper Productions Home">
            <img src="images/sandpiper-logo.png" alt="Sandpiper Productions Logo">
          </a>

          <button
            class="nav-toggle"
            id="nav-toggle"
            aria-label="Toggle navigation"
            aria-expanded="false"
            aria-controls="site-nav"
            type="button"
          >
            <span></span>
            <span></span>
            <span></span>
          </button>

          <nav id="site-nav" class="site-nav" aria-label="Main navigation">
            <ul>
              ${navHtml}
            </ul>
          </nav>
        </div>
      </header>
    `;
  }

  if (footer) {
    footer.innerHTML = `
      <footer class="site-footer">
        <div class="container">
          <p>&copy; <span id="year"></span> Sandpiper Productions. All rights reserved.</p>
        </div>
      </footer>
    `;
  }

  const yearSpan = document.getElementById("year");
  if (yearSpan) {
    yearSpan.textContent = new Date().getFullYear();
  }

  const navToggle = document.getElementById("nav-toggle");
  const siteNav = document.getElementById("site-nav");

  if (navToggle && siteNav) {
    navToggle.addEventListener("click", function () {
      const isOpen = siteNav.classList.toggle("open");
      navToggle.classList.toggle("open", isOpen);
      navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });

    siteNav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        siteNav.classList.remove("open");
        navToggle.classList.remove("open");
        navToggle.setAttribute("aria-expanded", "false");
      });
    });
  }
});