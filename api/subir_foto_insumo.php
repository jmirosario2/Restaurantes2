<?php
// subir_foto_insumo.php
header('Content-Type: application/json');

$uploadDir = __DIR__ . '/uploads/fotomenu/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No file or upload error']);
    exit;
}

$file = $_FILES['foto'];
$allowed = ['image/jpeg' => '.jpg', 'image/png' => '.png'];
if (!array_key_exists($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'error' => 'Formato no permitido']);
    exit;
}

$ext = $allowed[$file['type']];
$nombre = uniqid('insumo_', true) . $ext;
$rutaDestino = $uploadDir . $nombre;

if (!move_uploaded_file($file['tmp_name'], $rutaDestino)) {
    echo json_encode(['success' => false, 'error' => 'No se pudo mover el archivo']);
    exit;
}

// Aquí puedes insertar el registro en la tabla de insumos y guardar 'uploads/fotomenu/'.$nombre
// Ejemplo: guardar referencia en base de datos en columna 'referenciafoto'
// Suponiendo que tu lógica actual crea el registro, podrías devolver la referencia para que el frontend
// la agregue al objeto insumo antes de emitir o que el backend haga la inserción completa.

$referenciaPublica = 'uploads/fotomenu/' . $nombre;
echo json_encode(['success' => true, 'referenciafoto' => $referenciaPublica]);
