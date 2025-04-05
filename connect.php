<?php
function connectDatabase() {
    $serverName = "DESKTOP-I9RIAMK\\LAMPRO"; 
    $connectionOptions = array(
        "Database" => "Test",
        "Uid" => "sa",
        "PWD" => "140800",
        "CharacterSet" => "UTF-8"
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        die(json_encode(["error" => "Lỗi kết nối CSDL", "details" => sqlsrv_errors()], JSON_UNESCAPED_UNICODE));
    }

    return $conn;
}
?>
