<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include file konfigurasi database
require_once "../includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $book_id = $_POST['book_id'];
    $borrow_date = $_POST['borrow_date'];
    $member_id = $_SESSION["member_id"]; // Ambil ID member dari sesi yang sedang login
    
    // Pastikan data tidak kosong
    if (empty($book_id) || empty($borrow_date)) {
        echo "All fields are required.";
        exit;
    }

    // Hitung tanggal pengembalian (borrow_date + 7 hari)
    $return_date = date('Y-m-d', strtotime($borrow_date . ' + 7 days'));

    // Query SQL untuk memasukkan data peminjaman buku
    $sql = "INSERT INTO borrowed_books (book_id, member_id, borrow_date, return_date) VALUES (?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiss", $book_id, $member_id, $borrow_date, $return_date);
        
        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            // Redirect ke halaman borrow jika berhasil
            header("Location: borrow.php");
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
