<?php
/* include_once "encabezado.php";
$idMesa = json_decode(file_get_contents("php://input"));
if (!$idMesa) {
    http_response_code(500);
    exit;
}

include_once "funciones.php";

$resultado = cancelarMesa($idMesa);

echo json_encode($resultado); */


include_once "encabezado.php";

$idMesa = $_GET['id']; // o $_POST['id'] si usas POST

$ruta = "./mesas_ocupadas/" . $idMesa . ".csv";

if (file_exists($ruta)) {
    unlink($ruta);
    echo json_encode(true);
} else {
    echo json_encode(false);
}
