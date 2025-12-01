<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $data = json_decode(file_get_contents("php://input"), true);

  $cliente = $data["cliente"];
  $items = $data["items"];
  $subtotal = $data["subtotal"];
  $itbis = $data["itbis"];
  $total = $data["total"];

  $html = "
  <div style='font-family: Poppins, sans-serif; max-width:400px; margin:auto; border:1px solid #ddd; padding:20px; border-radius:10px;'>
    <h3 style='text-align:center;'>Restaurante NL</h3>
    <p style='text-align:center; color:#666;'>Ticket de Orden</p>
    <hr>
    <p><strong>Cliente:</strong> {$cliente['nombre']} {$cliente['apellido']}</p>
    <p><strong>Teléfono:</strong> {$cliente['telefono']}</p>
    <p><strong>Correo:</strong> {$cliente['correo']}</p>
    <p><strong>Dirección:</strong> {$cliente['direccion']}</p>
    <hr>
    <table width='100%' style='border-collapse:collapse;'>
      <thead>
        <tr style='border-bottom:1px solid #ddd;'>
          <th align='left'>Descripción</th>
          <th align='right'>Precio</th>
        </tr>
      </thead>
      <tbody>";

  foreach ($items as $item) {
    $html .= "<tr>
      <td>{$item['descripcion']}</td>
      <td align='right'>{$item['precio']} RD$</td>
    </tr>";
  }

  $html .= "
      </tbody>
    </table>
    <hr>
    <div style='text-align:right;'>
      <p>Subtotal: <strong>" . number_format($subtotal,2) . " RD$</strong></p>
      <p>ITBIS (18%): <strong>" . number_format($itbis,2) . " RD$</strong></p>
      <h4>Total: " . number_format($total,2) . " RD$</h4>
    </div>
    <hr>
    <p style='text-align:center; font-size:12px; color:#777;'>¡Gracias por su compra!</p>
  </div>";

  echo $html;
}
?>
