<?php
    $host = 'localhost';
    $user = 'root';
    $db = 'school';
    $pass = '';
    $port = '3306';

    $conn = new mysqli($host, $user, $pass, $db, $port);

    if($conn->connect_error){
        die("Connection error: ".$conn->connection_error);
    }
?>