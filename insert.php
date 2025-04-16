<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "POST") {

    $inputData = json_decode(file_get_contents("php://input"), true);
    $name      = null;
    $age       = null;
    $city      = null;
    $hobby     = null;
    $data      = [];

    if (empty($inputData)) {
        $name  = $_POST["name"];
        $age   = $_POST["age"];
        $city  = $_POST["city"];
        $hobby = $_POST["hobby"];

        $data = [
            "name"  => $name,
            "age"   => $age,
            "city"  => $city,
            "hobby" => $hobby,
        ];

    } else {
        $name  = $inputData['name'];
        $age   = $inputData['age'];
        $city  = $inputData['city'];
        $hobby = $inputData['hobby'];

        $data = [
            "name"  => $name,
            "age"   => $age,
            "city"  => $city,
            "hobby" => $hobby,
        ];

    }

    if (empty($name) || empty($age) || empty($city) || empty($hobby)) {
        // IF FIELD IS EMPTY SHOW BAD REQUEST
        $response = [
            'status'  => 422,
            'message' => "Missing Values",
            'data'    => $data,
        ];
        header("HTTP/1.0 422 Failed");
        echo json_encode($response);
    } else {
        // IF FIELD IS NOT EMPTY THEN INSERT RECORD
        include "config.php";
        $config = new Config();
        $config->connect_db();
        $result = $config->insert_record($name, $age, $city, $hobby);

        $response = [
            "status" => $result ? 201 : 405,
            "result" => $result,
            'data'   => $data,
        ];
        header("HTTP/1.0 201 Inserted");
        echo json_encode($response);
    }

} else {
    $data = [
        "status"  => 405,
        "message" => $requestMethod . " Method Not Allowed",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}
