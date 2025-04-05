<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();

$ID_USER = isset($_GET['ID_USER']) ? intval($_GET['ID_USER']) : 0;
$ID_LOAI = isset($_GET['ID_LOAI']) ? intval($_GET['ID_LOAI']) : 0;
$ID_LOAI_BAI = isset($_GET['ID_LOAI_BAI']) ? intval($_GET['ID_LOAI_BAI']) : 0;
$ID_STT_QUESTION = isset($_GET['ID_STT_QUESTION']) ? intval($_GET['ID_STT_QUESTION']) : 0;

if ($ID_USER === 0) {
    echo json_encode(["error" => "ID_USER is required"], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($ID_LOAI === 0) {
    echo json_encode(["error" => "ID_LOAI is required"], JSON_UNESCAPED_UNICODE);
    exit();
}

$sql = "SELECT (
    SELECT 
        CacLoaiBai.TOTAL_QUESTION, 
        CacLoaiBai.INSTRUCT,
        CacLoaiBai.TITLE,
        ISNULL(Result.NUMBER_DONE, 0) AS NUMBER_DONE, 
        ISNULL(Result.NUMBER_RIGHT, 0) AS NUMBER_RIGHT,

        (SELECT * FROM QuestionP1 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP1.ID_LOAI_BAI = ? AND QuestionP1.ID_STT_QUESTION = ? FOR JSON PATH) AS QuestionP1,
        (SELECT * FROM QuestionP2 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP2.ID_LOAI_BAI = ? AND QuestionP2.ID_STT_QUESTION = ? FOR JSON PATH) AS QuestionP2,
        (SELECT * FROM QuestionP3 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP3.ID_LOAI_BAI = ? AND QuestionP3.ID_STT_QUESTION = ? FOR JSON PATH) AS QuestionP3,
        (SELECT * FROM QuestionP4 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP4.ID_LOAI_BAI = ? AND QuestionP4.ID_STT_QUESTION = ? FOR JSON PATH) AS QuestionP4,
        (SELECT * FROM QuestionP5 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP5.ID_LOAI_BAI = ? AND QuestionP5.ID_STT_QUESTION = ? FOR JSON PATH) AS QuestionP5,
        (SELECT * FROM QuestionP6 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP6.ID_LOAI_BAI = ? AND QuestionP6.ID_STT_QUESTION = ? FOR JSON PATH) AS QuestionP6,
        (SELECT * FROM QuestionP7 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP7.ID_LOAI_BAI = ? AND QuestionP7.ID_STT_QUESTION = ? FOR JSON PATH) AS QuestionP7,
        (SELECT * FROM QuestionP8 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP8.ID_LOAI_BAI = ?  FOR JSON PATH) AS QuestionP8,
        (SELECT * FROM QuestionP9 WHERE ID_LOAI = CacLoaiBai.ID_LOAI AND QuestionP9.ID_LOAI_BAI = ? FOR JSON PATH) AS QuestionP9
    FROM CacLoaiBai 
    LEFT JOIN Result ON CacLoaiBai.ID_LOAI = Result.ID_LOAI AND Result.ID_USER = ?
    WHERE CacLoaiBai.ID_LOAI = ?
    FOR JSON PATH, ROOT('data')
) AS json_output";

$params = [
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_LOAI_BAI, $ID_STT_QUESTION, 
    $ID_USER, $ID_LOAI

]; 
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(["error" => "Lỗi truy vấn SQL", "details" => sqlsrv_errors()], JSON_UNESCAPED_UNICODE);
    exit();
}

$json_result = "";
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $json_result .= $row['json_output'];
}

echo $json_result ?: json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
