<?php

error_reporting(0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    include "config.php";
    $config = new Config();
    $config->connect_db();

    if (isset($_GET["id"])) {
        $id   = $_GET["id"];
        $data = $config->get_single_student($id);
        echo $data;
    } else {
        $data = $config->fetch_student_records();
        echo $data;
    }

} else {
    $data = [
        "status"  => 405,
        "message" => $requestMethod . " Method not Allowed!",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
