<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecoconecta";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["erro" => "Falha na conexão: " . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
?>