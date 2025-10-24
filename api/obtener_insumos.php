<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejar petición OPTIONS para preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
/* include_once "encabezado.php";
include_once "funciones.php";
$filtros = json_decode(file_get_contents("php://input"));

$insumos = obtenerInsumos($filtros);

echo json_encode($insumos);

$mesas = obtenerMesas();

foreach ($mesas as &$mesa) {
    if ($mesa['estado'] === 'ocupada') {
        $mesa['insumos'] = obtenerInsumosMesa($mesa['idMesa']); // ✅ nuevo
    } else {
        $mesa['insumos'] = [];
    }
}

echo json_encode($mesas); */

include_once "encabezado.php";
include_once "funciones.php";

$mesas = obtenerMesas();

foreach ($mesas as &$mesa) {
    $estado = isset($mesa['estado']) ? $mesa['estado'] : 'libre';
    if (in_array($estado, ['ocupada','pendiente'])) {
        $mesa['insumos'] = obtenerInsumosMesa($mesa['idMesa']); // ✅ aquí solo insumos
    } else {
        $mesa['insumos'] = [];
    }
}

echo json_encode($mesas);
