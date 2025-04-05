<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/toeicphp/connect.php';

$conn = connectDatabase();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; 

$sql = "SELECT 
            (SELECT 
                ID,
                NAMETENSES,
                DEFINE,
                Loai,

                (SELECT 
                    TYPE_WORD,
                    REGULAR,
                    TOBE
                 FROM VerbType 
                 WHERE VerbType.TENSES_ID = EnglishTenses.ID 
                 FOR JSON PATH) AS VerbType,

                (SELECT 
                    TYPE_WORD_EXAMPLE,
                    REGULAR_EXAMPLE,
                    TOBE_EXAMPLE
                 FROM Examples 
                 WHERE Examples.TENSEN_ID = EnglishTenses.ID 
                 FOR JSON PATH) AS Examples,

                (SELECT 
                    DESCRIPTION_DEFINE
                 FROM Usage 
                 WHERE Usage.TENSES_ID = EnglishTenses.ID 
                 FOR JSON PATH) AS Usage,

                (SELECT 
                    KEYWORD
                 FROM Keywords 
                 WHERE Keywords.TENSES_ID = EnglishTenses.ID 
                 FOR JSON PATH) AS Keywords

             FROM EnglishTenses 
             WHERE ID = ?
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
