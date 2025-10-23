<?php
header('Content-Type: application/json');


include_once "encabezado.php";

if (!isset($datos->idUsuario)) {
  echo json_encode(['error' => 'Falta idUsuario']);
  exit;
}

$venta = json_decode(file_get_contents("php://input"));
if (!$venta) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = registrarVenta($venta);
echo json_encode($resultado);


