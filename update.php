<?php

error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == 'PUT') {

    $inputData = json_decode(file_get_contents("php://input"), true);
    $id        = null;
    $name      = null;
    $age       = null;
    $city      = null;
    $hobby     = null;
    $data      = [];

    if (empty($inputData)) {
        $id    = $_GET["id"];
        $name  = $_GET["name"];
        $age   = $_GET["age"];
        $city  = $_GET["city"];
        $hobby = $_GET["hobby"];

        $data = [
            "id"    => $id,
            "name"  => $name,
            "age"   => $age,
            "city"  => $city,
            "hobby" => $hobby,
        ];

    } else {
        $id    = $inputData["id"];
        $name  = $inputData["name"];
        $age   = $inputData["age"];
        $city  = $inputData["city"];
        $hobby = $inputData["hobby"];

        $data = [
            "id"    => $id,
            "name"  => $name,
            "age"   => $age,
            "city"  => $city,
            "hobby" => $hobby,
        ];
    }

    if (empty($id) || empty($name) || empty($age) || empty($city) || empty($hobby)) {
        // IF FIELD IS EMPTY SHOW BAD REQUEST
        $response = [
            'status'  => 422,
            'message' => "Missing Values",
            'data'    => $data,
        ];
        header("HTTP/1.0 422 Failed");
        echo json_encode($response);
    } else {

        // UPDATE RECORD
        include "config.php";
        $config = new Config();
        $config->connect_db();
        $result = $config->update_record($id, $name, $age, $city, $hobby);

        if ($result) {
            $response = [
                "status"  => 200,
                "message" => "Student Record Updated Successfully",
                "data"    => $data,
            ];
            header("HTTP/1.0 200 Updated");
            echo json_encode($response);
        } else {
            $response = [
                "status"  => 500,
                "message" => "Internal Server Error",
                "data"    => $data,
            ];
            header("HTTP/1.0 500 Server Error");
            echo json_encode($response);
        }
    }

} else {

    $data = [
        "status"  => 405,
        "message" => $requestMethod . " Method not Allowed!",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);

}
