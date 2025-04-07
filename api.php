<?php 
    header("Content-Type: application/json");
    include("config.php");
    $config = new Config();
    $response = $config->fetchStudentRecords();
    echo $response;
    
?>