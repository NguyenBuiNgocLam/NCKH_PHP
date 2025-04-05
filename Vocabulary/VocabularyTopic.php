<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; 

$sql = "SELECT 
            ID,
            TITLE_TOPIC,
            NUMBER_WORD,
            (SELECT 
                WORD,
                TYPE_VOCABULARY,
                MEANING,
                EXAMPLE_VOCABULARY,
                IMAGE_VOCABULARY
             FROM Vocabulary 
             WHERE Vocabulary.TOPIC_ID = Topic.ID 
             FOR JSON PATH) AS Vocabulary
        FROM Topic 
        WHERE ID = ?
        FOR JSON PATH, ROOT('data')";

$stmt = sqlsrv_query($conn, $sql, [$id]);

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
