<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["error" => "Method tidak sesuai"]);
    exit();
}
// echo "metode sesuai";
// die;

require_once __DIR__ . "/koneksi.php";
// Query untuk mengambil semua data buku tanpa pencarian
$sql = "SELECT * FROM book ORDER BY id DESC";
// kueri ambil semua buku dan mengurutkan id urutan menurun
$query = mysqli_query($conn, $sql);
// print_r($query);
if ($query) {
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

    $response = array(
        "data" => $result,
    );

    http_response_code(200);
    echo json_encode($response);
    exit();
}
// Ketika query error
http_response_code(500);
echo json_encode(array("error" => "Gagal mengambil data dari database."));
exit();
?>