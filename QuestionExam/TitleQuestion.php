<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();
$ID_YEAR = isset($_GET['ID_YEAR']) ? intval($_GET['ID_YEAR']) : 0; 

$sql = "SELECT * from TitleQuestion WHERE ID_YEAR = ? FOR JSON PATH, ROOT('data')";

$stmt = sqlsrv_query($conn, $sql, [$ID_YEAR]);

if ($stmt) {
    $json_output = "";
    
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)) {
        $json_output .= $row[0]; 
    }

    if (!empty($json_output)) {
        echo $json_output;
    } else {
        echo json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(["error" => "Lỗi truy vấn SQL"], JSON_UNESCAPED_UNICODE);
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
