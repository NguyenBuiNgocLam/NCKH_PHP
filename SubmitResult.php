<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'C:/xampp/htdocs/testtoeic/connect.php';
$conn = connectDatabase();

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Dữ liệu đầu vào không hợp lệ"]);
    exit();
}

$ID_USER = $data["ID_USER"];
$ID_LOAI = $data["ID_LOAI"];
$NUMBER_DONE = $data["NUMBER_DONE"];
$NUMBER_RIGHT = $data["NUMBER_RIGHT"];
$ID_LOAI_BAI = $data["ID_LOAI_BAI"];
$ID_STT_QUESTION = $data["ID_STT_QUESTION"];

// Kiểm tra ID hợp lệ
if ($ID_USER === 0 || $ID_LOAI === 0 || $ID_LOAI_BAI === 0 || $ID_STT_QUESTION === 0) {
    echo json_encode(["success" => false, "message" => "Thiếu thông tin bắt buộc"]);
    exit();
}

// Kiểm tra bản ghi đã tồn tại chưa
$sql_check = "SELECT ID FROM Result WHERE ID_USER = ? AND ID_LOAI = ? AND ID_LOAI_BAI = ? AND ID_STT_QUESTION = ?";
$params_check = [$ID_USER, $ID_LOAI, $ID_LOAI_BAI, $ID_STT_QUESTION];
$stmt_check = sqlsrv_query($conn, $sql_check, $params_check);

if ($stmt_check === false) {
    echo json_encode(["success" => false, "message" => "Lỗi kiểm tra dữ liệu", "error" => sqlsrv_errors()]);
    exit();
}

if (sqlsrv_has_rows($stmt_check)) {
    // Nếu bản ghi đã tồn tại, thực hiện UPDATE
    $sql_update = "UPDATE Result 
                   SET NUMBER_DONE = ?, NUMBER_RIGHT = ? 
                   WHERE ID_USER = ? AND ID_LOAI = ? AND ID_LOAI_BAI = ? AND ID_STT_QUESTION = ?";
    $params_update = [$NUMBER_DONE, $NUMBER_RIGHT, $ID_USER, $ID_LOAI, $ID_LOAI_BAI, $ID_STT_QUESTION];
    $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);

    if ($stmt_update) {
        echo json_encode(["success" => true, "message" => "Cập nhật kết quả thành công"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật dữ liệu", "error" => sqlsrv_errors()]);
    }
} else {
    // Nếu bản ghi chưa tồn tại, thực hiện INSERT
    $sql_insert = "INSERT INTO Result (ID_USER, ID_LOAI, ID_LOAI_BAI, ID_STT_QUESTION, NUMBER_DONE, NUMBER_RIGHT) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $params_insert = [$ID_USER, $ID_LOAI, $ID_LOAI_BAI, $ID_STT_QUESTION, $NUMBER_DONE, $NUMBER_RIGHT];
    $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

    if ($stmt_insert) {
        echo json_encode(["success" => true, "message" => "Lưu kết quả thành công"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi lưu dữ liệu", "error" => sqlsrv_errors()]);
    }
}

// Giải phóng bộ nhớ
sqlsrv_free_stmt($stmt_check);
sqlsrv_close($conn);
?>
