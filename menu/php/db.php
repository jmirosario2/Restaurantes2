<?php
$host = "localhost";
$user = "root"; // cambia según tu configuración
$pass = "";
$db = "restaurantesnl";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>
