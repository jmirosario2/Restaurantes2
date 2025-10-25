<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


if (!isset($_GET['idmesa'])) {
    echo json_encode(['error' => 'No se especificó idmesa']);
    exit;
}

$idmesa = intval($_GET['idmesa']);
$csv_file = __DIR__ . "/mesas_ocupadas/{$idmesa}.csv";

if (!file_exists($csv_file)) {
    echo json_encode(['error' => 'Mesa no encontrada']);
    exit;
}

$insumos = [];
if (($handle = fopen($csv_file, 'r')) !== false) {
    $header = fgetcsv($handle); // Leer cabecera
    while (($data = fgetcsv($handle)) !== false) {
        $insumos[] = [
            'id' => $data[0],
            'nombre' => $data[1],
            'cantidad' => intval($data[2]),
            'precio' => floatval($data[3]),
        ];
    }
    fclose($handle);
}

echo json_encode($insumos);

