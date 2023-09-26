<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(["error" => "method tidak sesuai"]);
    exit();
}

require_once __DIR__ . "/koneksi.php";

if (isset($_REQUEST['q'])) {
    $sql = "SELECT * FROM book where title LIKE '%" . $_REQUEST['q'] . "%' ORDER BY title ASC";
} else {
    $sql = "SELECT * FROM book ORDER BY id DESC";
}
$query = mysqli_query($conn, $sql);

if ($query) {
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $rows = [];
    foreach ($result as $row) {
        $rows[] = $row;
    }

    $jmlData = count($rows);

    $resMeta = array(
        "page" => isset($_GET['page']) ? $_GET['page'] : 0,
        "limit" => isset($_GET['limit']) ? $_GET['limit'] : null
    );
    $resMeta['total'] = sizeof($rows);

    $response = array(
        "data" => $rows,
    );

    http_response_code(200);
    echo json_encode($response);
    exit();
}

// ketika query error
http_response_code(500);
echo json_encode(array("error" => "Gagal mengambil data ke database."));
exit();