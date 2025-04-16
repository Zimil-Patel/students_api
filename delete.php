<?php

function error422($msg)
{
    $data = [
        "status"  => 422,
        "message" => $msg,
    ];
    header("HTTP/1.0 422 Failed");
    echo json_encode($data);
}

function delete_record($id)
{
    include "config.php";
    $config = new Config();
    $config->connect_db();
    $result = $config->delete_record($id);

    if ($result) {

        $response = [
            "status"  => 200,
            "message" => "Student Record Deleted Successfully",
        ];
        header("HTTP/1.0 200 Success");
        echo json_encode($response);

    } else {
        $response = [
            "status"  => 500,
            "message" => "Internal Server Error",
        ];
        header("HTTP/1.0 500 Server Error");
        echo json_encode($response);
    }
}

error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "DELETE") {

    $inputData = json_decode(file_get_contents("php://input"), true);

    if (empty($inputData)) {

        if (! isset($_GET['id'])) {
            return error422("Student Id Not Found");
        } elseif ($_GET['id'] == null) {
            return error422("Enter Student Id");
        } else {
            return delete_record($_GET['id']);
        }

    } else {

        $id = $inputData['id'];
        if ($id == null) {
            return error422("Enter Student Id");
        }

        return delete_record($id);
    }

} else {

    $data = [
        "status"  => 405,
        "message" => $requestMethod . " Method not Allowed!",
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);

}
