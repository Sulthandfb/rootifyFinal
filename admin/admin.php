<?php
// Include the database connection file
include '../filter_wisata/db_connect.php';

// Fetch users from database
$sql = "SELECT id, username, email, created_at FROM users";
$result = $db->query($sql);

// Get total number of users
$count_sql = "SELECT COUNT(*) as total_users FROM users";
$count_result = $db->query($count_sql);
$total_users = $count_result->fetch_assoc()['total_users'];

// Check for query execution errors
if (!$result) {
    die("Error executing query: " . $db->error);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Admin Dashboard Page</title>
    <link rel="stylesheet" href="./style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  </head>
  <body>
    <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
      <nav
        class="navbar show navbar-vertical h-lg-screen navbar-expand-lg px-0 py-3 navbar-light bg-white border-bottom border-bottom-lg-0 border-end-lg"
        id="navbarVertical"
      >
        <div class="container-fluid">
          <button
            class="navbar-toggler ms-n2"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#sidebarCollapse"
            aria-controls="sidebarCollapse"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand py-lg-2 mb-lg-5 px-lg-6 me-0" href="#">
            <h3 class="text-success">
              <img src="https://bytewebster.com/img/logo.png" width="40" /><span
                class="text-info"
                >Root</span
              >ify
            </h3>
          </a>
          <div class="navbar-user d-lg-none">
            <div class="dropdown">
              <a
                href="#"
                id="sidebarAvatar"
                role="button"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <div class="avatar-parent-child">
                  <img
                    alt="Image Placeholder"
                    src="https://images.unsplash.com/photo-1548142813-c348350df52b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=3&w=256&h=256&q=80"
                    class="avatar avatar- rounded-circle"
                  />
                  <span class="avatar-child avatar-badge bg-success"></span>
                </div>
              </a>
              <div
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="sidebarAvatar"
              >
                <hr class="dropdown-divider" />
                <a href="#" class="dropdown-item">Logout</a>
              </div>
            </div>
          </div>
          <div class="collapse navbar-collapse" id="sidebarCollapse">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-house"></i> Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-building"></i> Hotels
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-balloon-fill"></i> Attractions
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <i class="bi bi-file-text"></i> Posts
                </a>
              </li>
            </ul>
            <hr class="navbar-divider my-5 opacity-20" />
            <ul class="navbar-nav mb-md-4">
              <li>
                <div
                  class="nav-link text-xs font-semibold text-uppercase text-muted ls-wide"
                  href="#">
                </div>
              </li>
            </ul>
            <div class="mt-auto"></div>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a
                  class="nav-link"
                  href="#"
                  onclick="return confirm('Are you sure you want to logout?')"
                >
                  <i class="bi bi-box-arrow-left"></i> Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="h-screen flex-grow-1 overflow-y-lg-auto">
        <header class="bg-surface-primary border-bottom pt-6">
          <div class="container-fluid">
            <div class="mb-npx">
              <div class="row align-items-center">
                <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                  <h1 class="h2 mb-0 ls-tight">
                    <img
                      src="https://bytewebster.com/img/logo.png"
                      width="40"
                    />
                    Rootify Aplication
                  </h1>
                </div>
              </div>
              <pre>
              </pre>
            </div>
          </div>
        </header>
        <main class="py-6 bg-surface-secondary">
          <div class="container-fluid">
            <div class="row g-6 mb-6">
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span
                          class="h6 font-semibold text-muted text-sm d-block mb-2"
                          >Users</span>
                        <span class="h3 font-bold mb-0"><?php echo $total_users?></span>
                      </div>
                      <div class="col-auto">
                        <div
                          class="icon icon-shape bg-tertiary text-white text-lg rounded-circle"
                        >
                          <i class="bi bi-people"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span
                          class="h6 font-semibold text-muted text-sm d-block mb-2"
                          >Transaction</span
                        >
                        <span class="h3 font-bold mb-0">--</span>
                      </div>
                      <div class="col-auto">
                        <div
                          class="icon icon-shape bg-primary text-white text-lg rounded-circle"
                        >
                          <i class="bi bi-people"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span
                          class="h6 font-semibold text-muted text-sm d-block mb-2"
                          >Hotels</span
                        >
                        <span class="h3 font-bold mb-0">--</span>
                      </div>
                      <div class="col-auto">
                        <div
                          class="icon icon-shape bg-info text-white text-lg rounded-circle"
                        >
                          <i class="bi bi-building"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card shadow border-0">
                  <div class="card-body">
                    <div class="row">
                      <div class="col">
                        <span
                          class="h6 font-semibold text-muted text-sm d-block mb-2"
                          >Attractions</span
                        >
                        <span class="h3 font-bold mb-0">--</span>
                      </div>
                      <div class="col-auto">
                        <div
                          class="icon icon-shape bg-warning text-white text-lg rounded-circle"
                        >
                          <i class="bi bi-balloon-fill"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card shadow border-0 mb-7">
              <div class="card-header">
                <h5 class="mb-0">Users</h5>
              </div>
              <div class="table-responsive">
                <table class="table table-hover table-nowrap">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">Username</th>
                      <th scope="col">Email</th>
                      <th scope="col">Created at</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
                              $created_at = date('M d, Y', strtotime($row['created_at']));
                              ?>
                              <tr data-user-id="<?php echo $row['id']; ?>">
                                  <td>
                                      <img alt="Default profile" src="https://via.placeholder.com/40" class="avatar avatar-sm rounded-circle me-2" />
                                      <a class="text-heading font-semibold" href="#">
                                          <?php echo htmlspecialchars($row['username']); ?>
                                      </a>
                                  </td>
                                  <td><?php echo htmlspecialchars($row['email']); ?></td>
                                  <td><?php echo $created_at; ?></td>
                                  <td class="text-end">
                                      <button onclick="showEditAlert(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['username']); ?>', '<?php echo htmlspecialchars($row['email']); ?>', 'https://via.placeholder.com/100')" class="btn btn-sm btn-neutral">Edit</button>
                                      <button onclick="showDeleteAlert(<?php echo $row['id']; ?>)" class="btn btn-sm btn-square btn-neutral text-danger-hover">
                                          <i class="bi bi-trash"></i>
                                      </button>
                                  </td>
                              </tr>
                              <?php
                          }
                      } else {
                          echo "<tr><td colspan='4' class='text-center'>No users found</td></tr>";
                      }
                      ?>
                  </tbody>
                </table>
              </div>
              <div class="card-footer border-0 py-5">
                <span class="text-muted text-sm"
                  ><?php echo $result->num_rows; ?> items out of <?php echo $total_users; ?> results found</span
                >
                <nav aria-label="Page navigation example">
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link disabled" href="#">Previous</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link bg-info text-white" href="#">1</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="#">Next</a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
      crossorigin="anonymous"
    ></script>
    <script src="script.js"></script>
  </body>
</html>
