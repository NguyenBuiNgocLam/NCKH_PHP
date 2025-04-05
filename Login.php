<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();

$data = json_decode(file_get_contents("php://input"), true);
$identifier = $data['identifier'];
$password = $data['password'];

if (empty($identifier) || empty($password)) {
    echo json_encode(["error" => "Thiếu thông tin đăng nhập"], JSON_UNESCAPED_UNICODE);
    exit();
}

$sql = "SELECT ID, EMAIL, USERNAME FROM USERS WHERE EMAIL = ? AND PASS = ?";
$params = array($identifier, $password);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(["error" => "Lỗi truy vấn CSDL", "details" => sqlsrv_errors()], JSON_UNESCAPED_UNICODE);
    exit();
}

$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
if (!$user) {
    echo json_encode(["error" => "Sai tài khoản hoặc mật khẩu"], JSON_UNESCAPED_UNICODE);
    exit();
}

echo json_encode(["message" => "Đăng nhập thành công", "user" => $user], JSON_UNESCAPED_UNICODE);
sqlsrv_close($conn);


?>
