<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../index.php");
    exit;
}

$name = $_SESSION["name"];
$id = $_SESSION["id"];

?>
<html>
  <head>dashboard admin</head>
<body>
  <p>ini admin hanya contoh</p>
</body>
</html>