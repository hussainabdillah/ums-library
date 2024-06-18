<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../index.php");
    exit;
}

require '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $return_date = $_POST['return_date'];
    $member_id = $_SESSION["member_id"]; // Ambil ID member dari sesi yang sedang login

    // Pastikan data tidak kosong
    if (empty($book_id) || empty($return_date)) {
        echo "All fields are required.";
        exit;
    }

    // Query SQL untuk memperbarui data pengembalian buku
    $sql = "UPDATE borrowed_books SET returned_date = ? WHERE book_id = ? AND member_id = ? AND returned_date IS NULL";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sii", $return_date, $book_id, $member_id);
        
        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            // Redirect ke halaman return jika berhasil
            header("Location: return.php");
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
