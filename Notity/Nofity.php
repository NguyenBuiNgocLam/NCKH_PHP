<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();

$sql = "SELECT * FROM notify";
$stmt = sqlsrv_query($conn, $sql);

$notify = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $notify[] = $row;
    }
}

echo json_encode(['notify' => $notify], JSON_UNESCAPED_UNICODE); 

sqlsrv_free_stmt($stmt); 
sqlsrv_close($conn); 

?>