<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$request = $_SERVER["REQUEST_METHOD"];

if ($request == "POST") {

    $name  = $_POST["name"];
    $age   = $_POST["age"];
    $city  = $_POST["city"];
    $hobby = $_POST["hobby"];

    if (empty($name) || empty($age) || empty($city) || empty($hobby)) {
        // IF FIELD IS EMPTY SHOW BAD REQUEST
        $data = [
            'status'  => 400,
            'message' => "Missing Values",
        ];
        header("HTTP/1.0 400 Failed");
        echo json_encode($data);
    } else {
        // IF FIELD IS NOT EMPTY THEN INSERT RECORD
        include "config.php";
        $config = new Config();
        $config->connect_db();
        $result = $config->insert_record($name, $age, $city, $hobby);

        $data = [
            "status" => $result ? 201 : 405,
            "result" => $result,
        ];
        header("HTTP/1.0 201 Inserted");
        echo json_encode($data);
    }

} else {
    $data = [
        "status"  => 405,
        "message" => $request . " Method Not Allowed",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
