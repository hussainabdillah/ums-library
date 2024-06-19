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
    $genre = $_POST['genre'];

    $target_dir = "../img/cover/";

    // Ensure the target directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Check if there is a comma in the genre and take only the part before the first comma
    $comma_position = strpos($genre, ',');
    if ($comma_position !== false) {
        $genre = substr($genre, 0, $comma_position);
        $genre = trim($genre);
    }

    // Process the cover file if uploaded
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == UPLOAD_ERR_OK) {
        $cover_file = $_FILES['cover'];
        $file_extension = pathinfo($cover_file['name'], PATHINFO_EXTENSION);
        
        // Replace spaces and special characters in the title
        $sanitized_title = preg_replace('/[^A-Za-z0-9\-]/', '_', $title);

        // Construct the new file name
        $cover = $sanitized_title . '.' . $file_extension;
        $target_file = $target_dir . $cover;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($cover_file['tmp_name'], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    } else {
        echo "No file was uploaded or there was an upload error.";
        exit;
    }

    // Query SQL untuk memasukkan data buku baru
    $sql = "INSERT INTO books (title, author, published_year, isbn, cover, genre) VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssisss", $title, $author, $published_year, $isbn, $cover, $genre);
        
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