<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

$request = $_SERVER["REQUEST_METHOD"];

if ($request == "GET") {
    include "config.php";
    $config = new Config();
    $config->connect_db();

    if (isset($_GET["id"])) {
        $id      = $_GET["id"];
        $student = $config->get_single_student($id);

        $data = [
            "status"  => 200,
            "message" => "Student Record Fetched Successfully,",
            "data"    => $student,
        ];

        echo json_encode($data);
    } else {
        $data = $config->fetch_student_records();
        echo $data;
    }

} else {
    $data = [
        "status"  => 405,
        "message" => $request . " Method not Allowed!",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
