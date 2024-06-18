<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../index.php");
    exit;
}

$name = $_SESSION["name"];
$member_id = $_SESSION["member_id"];

// Query to get the count of borrowed books
$sql = "SELECT COUNT(*) as total_borrowed_books FROM borrowed_books WHERE member_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_borrowed_books);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} else {
    $total_borrowed_books = 0;
}

// Query to get the count of books
$sql = "SELECT COUNT(*) AS total_books FROM books";
$result = mysqli_query($conn, $sql);

$totalBooks = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalBooks = $row['total_books'];
} else {
    echo "Error fetching total books: " . mysqli_error($conn);
}

// Query to get the count of members
$sql = "SELECT COUNT(*) AS total_members FROM member";
$result = mysqli_query($conn, $sql);

$totalMembers = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalMembers = $row['total_members'];
} else {
    echo "Error fetching total members: " . mysqli_error($conn);
}

// Query to get the count of late returned books
$sql_late_books = "
SELECT 
    COUNT(*) AS late_books_count
FROM 
    borrowed_books 
WHERE 
    member_id = ? AND returned_date IS NOT NULL AND returned_date > return_date
";

if ($stmt = mysqli_prepare($conn, $sql_late_books)) {
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $late_books_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} else {
    $late_books_count = 0; // Default value if query fails
}

// Close connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UMS Library - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
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
            <li class="nav-item active">
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
            <li class="nav-item">
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
                <a class="nav-link" href="about.php">
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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Borrowed Books Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Borrowed Books</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_borrowed_books; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Books Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Available Books</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalBooks; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Member Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Member
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalMembers; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Late Books Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Books Returned Late</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $late_books_count; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <!-- Favorite Books Section -->
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Featured Books of the Week</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                    <!-- Example Book 1 -->
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <img class="card-img-top" src="../img/cover/The Lean Startup.png" alt="The Lean Startup Cover">
                            <div class="card-body">
                                <h4 class="card-title">The Lean Startup</h4>
                                <p class="card-text">Buku ini ditulis oleh Eric Ries dan membahas metodologi untuk membangun perusahaan startup dengan pendekatan yang inovatif dan efisien. Eric Ries menyarankan pendekatan yang berfokus pada pengujian hipotesis dan pembelajaran cepat untuk mengurangi risiko dan meningkatkan keberhasilan bisnis startup.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Example Book 2 -->
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <img class="card-img-top" src="../img/cover/Spy X Family 2.png" alt="Spy X Family Cover">
                            <div class="card-body">
                                <h4 class="card-title">Spy X Family</h4>
                                <p class="card-text">Ini adalah manga karya Tatsuya Endo yang mengikuti kisah seorang mata-mata yang menyamar sebagai kepala keluarga untuk misi rahasia, hanya untuk menemukan bahwa putri angkatnya adalah seorang psikis yang dapat membaca pikirannya. Komedi dan jebakan sehari-hari dengan sentuhan komedi dijamin menghibur.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Example Book 3 -->
                    <div class="col-lg-4 mb-4">
                        <div class="card h-100">
                            <img class="card-img-top" src="../img/cover/The Lord of the Rings.png" alt="The Lord of the Rings Cover">
                            <div class="card-body">
                                <h4 class="card-title">The Lord of the Rings</h4>
                                <p class="card-text">Karya J.R.R. Tolkien adalah kisah epik fantasi yang memikat tentang perjalanan Frodo Baggins dan rekan-rekannya untuk menghancurkan cincin kekuatan yang jahat di Mordor. Dengan dunia yang dalam, karakter yang kompleks, dan tema-tema universal, novel ini telah menjadi klasik modern dan inspirasi bagi banyak karya fantasi lainnya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-6 mb-4">

                            <!-- Project Card Example -->
                            

                            <!-- Color System -->
                            

                        </div>

                        <div class="col-lg-6 mb-4">

                            <!-- Illustrations -->
                            

                            <!-- Approach -->
                            

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