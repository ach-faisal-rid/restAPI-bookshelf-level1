<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Izinkan permintaan dari semua sumber lintas asal
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "method tidak sesuai"]);
    exit();
}
require_once __DIR__ . "/koneksi.php";
$requiredFields = ['id'];
$missingFields = [];
if (empty($missingFields)) {
    $id = (int)htmlspecialchars($_REQUEST['id'], true);
    $sql = "SELECT * FROM book WHERE id='$id' limit 1";
    $query = mysqli_query($conn, $sql);
    $cekId = mysqli_fetch_all($query, MYSQLI_ASSOC);
    if (!sizeof($cekId)) {
        http_response_code(400);
        echo json_encode([
            "error" => "Data [ $id ] Tidak ditemukan."
        ]);
        exit();
    }
    $sql = "DELETE FROM book WHERE id='$id'";
    $query = mysqli_query($conn, $sql);
    
    $res = mysqli_affected_rows($conn);

    if ($res) {
        $response = [
            "message" => "berhasil dihapus"
        ];
        http_response_code(200);
        echo json_encode($response);
        exit();
    }
    http_response_code(400);
    echo json_encode([
        "error" => "Gagal HAPUS data pada database"
    ]);
    exit();
}
// Data $_POST tidak lengkap, berikan pesan kesalahan dengan kunci yang tidak terpenuhi
$missingFieldsString = implode(', ', $missingFields);
http_response_code(400);
echo json_encode([
    "error" => "Data tidak lengkap. Kunci yang harus dipenuhi: " . $missingFieldsString
]);