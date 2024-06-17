<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../index.php");
    exit;
}

require '../includes/config.php';

$member_id = $_SESSION["member_id"];
$name = $_SESSION["name"];

// Query untuk mengambil semua buku
$sql_books = "SELECT book_id, title FROM books";
$result_books = mysqli_query($conn, $sql_books);

// Query untuk mengambil buku yang dipinjam oleh member yang sedang login
$sql_borrowed_books = "
SELECT 
    b.title AS book_title,
    b.author AS book_author,
    b.published_year,
    bb.borrow_date,
    bb.return_date,
    bb.returned_date
FROM 
    borrowed_books bb
JOIN 
    books b ON bb.book_id = b.book_id
WHERE 
    bb.member_id = ?
ORDER BY 
    bb.borrow_date ASC;
";

$borrowed_books = [];
if ($stmt = mysqli_prepare($conn, $sql_borrowed_books)) {
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $book_title, $book_author, $published_year, $borrow_date, $return_date, $returned_date);
    while (mysqli_stmt_fetch($stmt)) {
        $borrowed_books[] = [
            'book_title' => $book_title,
            'book_author' => $book_author,
            'published_year' => $published_year,
            'borrow_date' => $borrow_date,
            'return_date' => $return_date,
            'returned_date' => $returned_date
        ];
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>UMS Library - Borrow</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">UMS Library</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Heading -->
            <div class="sidebar-heading">
                Books
            </div>
            
            <!-- Nav Item - Books -->
            <li class="nav-item">
                <a class="nav-link" href="books.php">
                    <i class="fas fa-fw fa-book-open"></i>
                    <span>List of Books</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Heading -->
            <div class="sidebar-heading">
                Services
            </div>
            
            <!-- Nav Item - Borrow -->
            <li class="nav-item active">
                <a class="nav-link" href="borrow.php">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Borrow</span></a>
            </li>
            
            <!-- Nav Item - Returns -->
            <li class="nav-item">
                <a class="nav-link" href="return.php">
                    <i class="fas fa-fw fa-handshake"></i>
                    <span>Returns</span></a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                About
            </div>

            <!-- Nav Item - About -->

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-info-circle"></i>
                    <span>About Us</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <!-- Page Heading -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Borrowed Books</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Form untuk meminjam buku -->
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Borrow Book</h6>
                                </div>
                                <div class="card-body">
                                    <form action="process_borrow.php" method="POST">
                                        <div class="form-group">
                                            <label for="book_id">Select Book:</label>
                                            <select class="form-control" id="book_id" name="book_id" required>
                                                <option value="">-- Select Book --</option>
                                                <?php while ($book = mysqli_fetch_assoc($result_books)): ?>
                                                    <option value="<?php echo $book['book_id']; ?>"><?php echo htmlspecialchars($book['title']); ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="borrow_date">Borrow Date:</label>
                                            <input type="date" class="form-control" id="borrow_date" name="borrow_date" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Borrow</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Books Borrowed by <?php echo htmlspecialchars($name); ?></h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Author</th>
                                                    <th>Published Year</th>
                                                    <th>Borrow Date</th>
                                                    <th>Return Date</th>
                                                    <th>Returned Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($borrowed_books as $book): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($book['book_title']); ?></td>
                                                        <td><?php echo htmlspecialchars($book['book_author']); ?></td>
                                                        <td><?php echo htmlspecialchars($book['published_year']); ?></td>
                                                        <td><?php echo htmlspecialchars($book['borrow_date']); ?></td>
                                                        <td><?php echo htmlspecialchars($book['return_date']); ?></td>
                                                        <td><?php echo htmlspecialchars($book['returned_date']); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Group 2 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>
</body>
</html>
