<?php
include 'db.php';
header('Content-Type: application/json; charset=utf-8');

$sql = "SELECT * FROM insumos";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    // Aseguramos que se mantenga la imagen base64 completa
    $data[] = [
        "id" => $row["id"],
        "codigo" => $row["codigo"],
        "descripcion" => $row["descripcion"],
        "precio" => $row["precio"],
        "tipo" => $row["tipo"],
        "categoria" => $row["categoria"],
        "foto" => $row["foto"]
    ];
}

echo json_encode($data);
$conn->close();
?>
