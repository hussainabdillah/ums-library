<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../index.php");
    exit;
}

require '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $title = $_POST['title'];
    $author = $_POST['author'];
    $published_year = $_POST['published_year'];
    $isbn = $_POST['isbn'];
    $cover = $title . '.png'; // Generate cover filename

    // Query SQL untuk memasukkan data buku baru
    $sql = "INSERT INTO books (title, author, published_year, isbn, cover) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssiss", $title, $author, $published_year, $isbn, $cover);
        
        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            // Redirect ke halaman books jika berhasil
            header("Location: books.php");
        } else {
            echo "Error: Could not execute query: $sql. " . mysqli_error($conn);
        }
        
        // Menutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Could not prepare query: $sql. " . mysqli_error($conn);
    }
    
    // Menutup koneksi database
    mysqli_close($conn);
}
?>