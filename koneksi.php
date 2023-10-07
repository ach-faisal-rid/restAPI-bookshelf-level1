<?php
$servername = "localhost";
$username = "root";
$password = ""; // kalau tidak kalian password biarkan kosong
$db = "bookshelf";
    // membuat koneksi ke db
    $conn = mysqli_connect($servername, $username, $password, $db);
    // cek apa kah dia terkoneksi ke db ?
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
// echo "Connected successfully";