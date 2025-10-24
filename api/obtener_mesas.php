<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejar petición OPTIONS para preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
include_once "encabezado.php";
include_once "funciones.php";

/* $resultado = obtenerMesas();

echo json_encode($resultado);
 */

$resultado = obtenerMesas(); // ya es un array de mesas

foreach ($resultado as &$mesa) {
    $estado = isset($mesa['estado']) ? $mesa['estado'] : 'libre';
    if (in_array($estado, ['ocupada','pendiente'])) {
        // agregar solo los insumos de esta mesa
        $mesa['insumos'] = obtenerInsumosMesa($mesa['idMesa']);
    } else {
        $mesa['insumos'] = [];
    }
}

echo json_encode($resultado); // devolver el array completo de mesas con insumos
