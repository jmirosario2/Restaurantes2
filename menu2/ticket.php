<?php
$items = json_decode($_POST['items'], true);
$total = $_POST['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .ticket { border: 1px dashed #000; padding: 20px; width: 400px; margin: auto; }
        .ticket h3 { text-align: center; }
        .ticket ul { list-style: none; padding: 0; }
        .ticket li { display: flex; justify-content: space-between; }
    </style>
</head>
<body>
<div class="ticket">
    <h3>Ticket de Orden</h3>
    <p><strong>Cliente:</strong> <?= $_POST['nombre'] . ' ' . $_POST['apellido'] ?></p>
    <p><strong>Tel:</strong> <?= $_POST['telefono'] ?> | <strong>Email:</strong> <?= $_POST['correo'] ?></p>
    <p><strong>Dirección:</strong> <?= $_POST['direccion'] ?></p>
    <hr>
    <ul>
        <?php foreach ($items as $item): ?>
        <li><span><?= $item['descripcion'] ?></span><span>RD$ <?= number_format($item['precio'], 2) ?></span></li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <p><strong>Total:</strong> RD$ <?= number_format($total, 2) ?></p>
    <button onclick="window.print()">🖨️ Imprimir</button>
</div>
</body>
</html>
