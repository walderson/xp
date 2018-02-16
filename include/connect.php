<?php
$serverName = "localhost";
$userName = "root";
$password = "";

// Cria a conexão
$conn = new mysqli($serverName, $userName, $password);

// Checa a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
} 
?>