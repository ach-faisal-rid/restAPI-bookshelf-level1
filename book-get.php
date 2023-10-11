// case 1
<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["error" => "Method tidak sesuai"]);
    exit();
}
// kode debugging
echo "metode sesuai";
// Pencetakan pesan untuk debugging
error_log("Metode sesuai");

require_once __DIR__ . "/koneksi.php";
// Query untuk mengambil semua data buku tanpa pencarian
$sql = "SELECT * FROM book ORDER BY id DESC";
// kueri ambil semua buku dan mengurutkan id urutan menurun
$query = mysqli_query($conn, $sql);
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

// case 2
<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(403);
    echo json_encode(["error" => "Method tidak sesuai"]);
    exit();
}
require_once __DIR__ . "/koneksi.php";

function getBooks() {
    global $conn;
    $sql = "SELECT * FROM book ORDER BY id DESC";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
        return $result;
    } else {
        return null; // Kembalikan null jika ada kesalahan
    }
}

$data = getBooks();

if ($data !== null) {
    $response = array(
        "data" => $data,
    );
    http_response_code(200);
    echo json_encode($response);
} else {
    http_response_code(500);
    echo json_encode(array("error" => mysqli_error($conn))); // Tampilkan pesan kesalahan database yang lebih spesifik
}

exit();
?>
