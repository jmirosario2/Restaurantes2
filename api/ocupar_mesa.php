<?php
include_once "encabezado.php";
include_once "funciones.php";

$datos = json_decode(file_get_contents("php://input"));

if (!$datos || !isset($datos->insumos) || !is_array($datos->insumos)) {
    error_log("Payload inválido o incompleto");
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

ocuparMesa($datos);
echo json_encode(true);
