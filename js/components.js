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
      return '<li><a href="' + link.href + '" class="' + activeClass + '">' + link.label + '</a></li>';
    })
    .join("");

  if (header) {
    header.innerHTML = `
      <header>
        <div class="container nav-container">
          <a href="index.html" class="logo">
            <img src="images/sandpiper-logo.png" alt="Sandpiper Productions Logo">
          </a>
          <nav>
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
      <footer>
        <div class="container">
          <p>&copy; <span id="year"></span> Sandpiper Productions</p>
        </div>
      </footer>
    `;
  }

  const yearSpan = document.getElementById("year");
  if (yearSpan) {
    yearSpan.textContent = new Date().getFullYear();
  }
});