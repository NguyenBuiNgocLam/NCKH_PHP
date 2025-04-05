<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/toeicphp/connect.php';

$conn = connectDatabase();

$sql = "SELECT ID, NAMETENSES, Loai FROM EnglishTenses";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    echo json_encode(["error" => "Lỗi truy vấn SQL"], JSON_UNESCAPED_UNICODE);
    die();
}

$data = [];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

echo json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
?>
