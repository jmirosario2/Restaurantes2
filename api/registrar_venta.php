<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}


$datos = json_decode(file_get_contents("php://input"));

if (!$datos) {
  echo json_encode(['error' => 'JSON inválido']);
  exit;
}

if (!isset($datos->idUsuario)) {
  echo json_encode(['error' => 'Falta idUsuario']);
  exit;
}

// ... tu lógica de inserción aquí ...

echo json_encode(['success' => true]); // ✅ nunca omitas esto

include_once "funciones.php";

$venta = json_decode(file_get_contents("php://input"));

if (!$venta) {
  echo json_encode(['error' => 'JSON inválido']);
  exit;
}

registrarVenta($venta);
