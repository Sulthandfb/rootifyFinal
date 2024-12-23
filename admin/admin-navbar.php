<!-- navbar.php -->
<nav
  class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-3 navbar-light bg-white border-bottom border-bottom-lg-0 border-end-lg"
  id="navbarVertical"
>
  <div class="container-fluid">
    <!-- Navbar content -->
    <a class="navbar-brand py-lg-2 mb-lg-5 px-lg-6 me-0" href="#">
      <h3 class="text-success">
        <img src="../img/logo.png" width="40" /><span
          class="text-info"
          >Root</span
        >ify
      </h3>
    </a>
    <div class="navbar-user d-lg-none">
      <!-- Mobile user dropdown -->
    </div>
    <div class="collapse navbar-collapse" id="sidebarCollapse">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="admin.php">
            <i class="bi bi-house"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_hotels.php">
            <i class="bi bi-building"></i> Hotels
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_attractions.php">
            <i class="bi bi-balloon-fill"></i> Attractions
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin-view-hotels.php">
            <i class="bi bi-file-text"></i> View Hotels
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin-view-attractions.php">
            <i class="bi bi-file-text"></i> View Attractions
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin-package.php">
            <i class="bi bi-file-text"></i> Tour Packages
          </a>
        </li>
      </ul>
      <hr class="navbar-divider my-5 opacity-20" />
      <ul class="navbar-nav mb-md-4">
        <li>
          <div
            class="nav-link text-xs font-semibold text-uppercase text-muted ls-wide"
            href="#"
          >
          </div>
        </li>
      </ul>
      <div class="mt-auto"></div>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a
            class="nav-link"
            href="../authentication/index.php"
            onclick="return confirm('Are you sure you want to logout?')"
          >
            <i class="bi bi-box-arrow-left"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>