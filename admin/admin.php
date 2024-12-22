<!-- admin.php -->
<?php
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

include 'admin-header.php';
include 'admin-navbar.php';
?>

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
