<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; 

$sql = "SELECT 
    (SELECT 
        Y.ID AS id,
        (SELECT 
            SQ.STT AS sothutubai,
            (SELECT 
                Q.IMAGE_QUESTION AS image,
                Q.AUDIO_QUESTION AS audio,
                Q.CORRECT_ASW AS correctAnswer
             FROM QuestionExam Q 
             WHERE Q.ID_STT_QUESTION = SQ.ID
             FOR JSON PATH) AS sothutucacbai
         FROM STT_Question SQ
         WHERE SQ.ID_YEAR = Y.ID
         FOR JSON PATH) AS question
     FROM YearExam Y
     WHERE Y.ID = ?
             FOR JSON PATH, ROOT('data')) AS json_output";

$stmt = sqlsrv_query($conn, $sql, [$id]);

if ($stmt) {
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
    if ($row && isset($row['json_output'])) {
        echo $row['json_output']; 
    } else {
        echo json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
    }
    
} else {
    echo json_encode(["error" => "Lỗi truy vấn SQL"], JSON_UNESCAPED_UNICODE);
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
