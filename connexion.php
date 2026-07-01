<?php
$host     = "127.0.0.1";
$user     = "root";
$password = "";
$dbname   = "orderflow";
$port     = 3307;

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>