<?php 
    header("Content-Type: application/json");
    include("config.php");
    $config = new Config();
    $config->connect_db();
    $response = $config->fetch_student_records();
    echo $response;
    
?>