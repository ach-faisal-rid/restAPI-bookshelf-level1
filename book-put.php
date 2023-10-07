<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "method tidak sesuai"]);
    exit();
}

require_once __DIR__ . "/koneksi.php";
$requiredFields = ['title', 'author', 'year', 'isComplete', 'id'];
$missingFields = [];

// Cek apakah setiap kunci yang diperlukan ada dalam $_POST dan tidak kosong
foreach ($requiredFields as $field) {
    if (empty($_REQUEST[$field])&& $_POST[$field]!=0) {
        $missingFields[] = $field;
    }
}
if (empty($missingFields)) {
    $id = htmlspecialchars($_REQUEST['id'], true);
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
    $title = htmlspecialchars($_POST['title'], true);
    // Mengambil data yang baru saja di-create
    $sql = "SELECT * FROM book WHERE title = '$title' AND id != '$id'";
    $query = mysqli_query($conn, $sql);
    $cekTitle = mysqli_fetch_all($query, MYSQLI_ASSOC);
    if (sizeof($cekTitle)) {
        http_response_code(400);
        echo json_encode([
            "error" => "Data $title sudah ada."
        ]);
        exit();
    }
    $author = $_POST['author'];
    $year = $_POST['year'];
    $isComplete = $_POST['isComplete'];
    $sql = "UPDATE book SET title='$title', year='$year', author='$author', isComplete='$isComplete' WHERE id='$id'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        // Mengambil data yang baru saja di-create
        $sql = "SELECT * FROM book WHERE id = '$id'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

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
}
// Data $_POST tidak lengkap, berikan pesan kesalahan dengan kunci yang tidak terpenuhi
$missingFieldsString = implode(', ', $missingFields);
http_response_code(400);
echo json_encode([
    "error" => "Data tidak lengkap. Kunci yang harus dipenuhi: " . $missingFieldsString
]);