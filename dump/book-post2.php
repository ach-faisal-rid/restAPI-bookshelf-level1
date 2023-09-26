<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "method tidak sesuai"]);
    exit();
}

// START PROSES VALIDASI REQUEST
if (!isset($_POST['title']) || empty($_POST['title'])) {
    http_response_code(400);
    echo json_encode(["error" => "request title wajib dikirim"]);
    exit();
}

if (!isset($_POST['author']) || empty($_POST['author'])) {
    http_response_code(400);
    echo json_encode(["error" => "request author wajib dikirim"]);
    exit();
}

if (!isset($_POST['year']) || empty($_POST['year'])) {
    http_response_code(400);
    echo json_encode(["error" => "request year wajib dikirim"]);
    exit();
}

if (!isset($_POST['isComplete'])) {
    http_response_code(400);
    echo json_encode(["error" => "request isComplete wajib dikirim, dan harus ada isinya 1 atau 0"]);
    exit();
} else {
    // cek isComplete value harus 1 or 0
    if (($_POST['isComplete'] != 1 && $_POST['isComplete'] != 0)) {
        http_response_code(400);
        echo json_encode(["error" => "isComplete haru bernilai 1 atau 0"]);
        exit();
    }
}
// END PROSES VALIDASI REQUEST

require_once __DIR__ . "/koneksi.php";

$title = htmlspecialchars($_POST['title'], true);

// MEMASTIKAN TITLE TIDAK BOLEH SAMA
$sql = "SELECT * FROM book WHERE title = '$title' limit 1";
$query = mysqli_query($conn, $sql);
$cekTitle = mysqli_fetch_assoc($query);

if ($cekTitle) {
    http_response_code(400);
    echo json_encode([
        "error" => "Data $title sudah ada."
    ]);
    exit();
}
$author = $_POST['author'];
$year = $_POST['year'];
$isComplete = $_POST['isComplete'];
$sql = "INSERT INTO book (id, title, year, author, isComplete) VALUES(null, '$title','$year','$author','$isComplete')";
$query = mysqli_query($conn, $sql);

if ($query) {
    // INSERT DATA BERHASIL

    // Mendapatkan ID yang baru saja di-insert
    $insertedId = mysqli_insert_id($conn);

    // Mengambil data yang baru saja di-create
    $sql = "SELECT * FROM book WHERE id = $insertedId";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);

    $response = ["data" => []];
    foreach ($result as $row) {
        $response = [
            "data" => $row
        ];
    }
    http_response_code(200);
    echo json_encode($response);
    exit();
}
http_response_code(400);
echo json_encode([
    "error" => "Gagal memasukkan data ke dalam database"
]);
exit();
