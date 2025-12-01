<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FoodTruck Delivery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <style>
        /* Hero section */
        .hero {
            background: url('uploads_foodtruck/12525.jpg') center/cover no-repeat;
            color: #fff;
            text-align: center;
            padding: 120px 20px;
            position: relative;
        }
        .hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .hero .btn {
            margin-top: 20px;
            font-size: 1.1rem;
            padding: 12px 30px;
        }

        /* Cards */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card {
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }

        /* Ticket box */
        .ticket-box {
            background: #f8f9fa;
            border: 2px dashed #ccc;
            padding: 20px;
            border-radius: 10px;
        }

        /* Footer */
        footer {
            background: #212529;
            color: #fff;
            padding: 40px 20px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-light">

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>🍔 Bienvenido a FoodTruck Delivery</h1>
        <p>Ordena tu comida favorita y recíbela en la puerta de tu casa</p>
        <a href="#menu" class="btn btn-warning">Ver Menú</a>
    </div>
</section>

<!-- Beneficios -->
<section class="py-5 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>🚀 Rápido</h3>
                <p>Tu pedido llega en minutos, siempre fresco y caliente.</p>
            </div>
            <div class="col-md-4">
                <h3>🍴 Delicioso</h3>
                <p>Ingredientes de calidad y recetas únicas que te encantarán.</p>
            </div>
            <div class="col-md-4">
                <h3>📦 Seguro</h3>
                <p>Empaque higiénico y entrega confiable hasta tu puerta.</p>
            </div>
        </div>
    </div>
</section>

<!-- Menú -->
<div class="container py-5" id="menu">
    <h2 class="mb-4 text-center">Menú Interactivo</h2>
    <div class="row">
        <?php
        $sql = "SELECT * FROM insumos";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
            $rutaImagen = "uploads_foodtruck/" . htmlspecialchars($row['foto']);
        ?>
        <div class="col-md-3 mb-3">
            <div class="card h-100 shadow-sm">
                <img src="<?= $rutaImagen ?>" class="card-img-top" alt="<?= htmlspecialchars($row['foto']) ?>">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
                    <p class="card-text">
                        <span class="badge bg-secondary"><?= htmlspecialchars($row['tipo']) ?></span><br>
                        <strong>RD$ <?= number_format($row['precio'], 2) ?></strong>
                    </p>
                    <button class="btn btn-primary w-100 add-item"
                            data-id="<?= $row['id'] ?>"
                            data-descripcion="<?= htmlspecialchars($row['descripcion']) ?>"
                            data-precio="<?= $row['precio'] ?>">
                        Seleccionar
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <?php

    ?>
    <!-- Ticket -->
    <h4 class="mt-5 text-center">🧾 Tu Orden</h4>
    <form action="ticket.php" method="POST" target="_blank">
        <div class="row justify-content-center">
           <div class="col-md-6 ticket-box" id="ticket">
    <ul class="list-group" id="ticket-items"></ul>
    <p class="mt-3 fw-bold">Subtotal: RD$ <span id="subtotal">0.00</span></p>
    <p class="fw-bold">ITBIS (18%): RD$ <span id="itbis">0.00</span></p>
    <p class="fw-bold">Total: RD$ <span id="total">0.00</span></p>
    <input type="hidden" name="items" id="items-input">
    <input type="hidden" name="subtotal" id="subtotal-input">
    <input type="hidden" name="itbis" id="itbis-input">
    <input type="hidden" name="total" id="total-input">
</div>


            <div class="col-md-6">
    <div class="row">
        <!-- Primera fila -->
        <div class="col-md-6 mb-2">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
            <label>Apellido:</label>
            <input type="text" name="apellido" class="form-control" required>
        </div>

        <!-- Segunda fila -->
        <div class="col-md-6 mb-2">
            <label>Teléfono:</label>
            <input type="text" name="telefono" class="form-control" required>
        </div>
        <div class="col-md-6 mb-2">
            <label>Correo:</label>
            <input type="email" name="correo" class="form-control" required>
        </div>

        <!-- Fila completa -->
        <div class="col-12 mb-2">
            <label>Dirección de envío:</label>
            <textarea name="direccion" class="form-control" required></textarea>
        </div>

        <!-- Botón -->
        <div class="col-12">
            <button type="submit" class="btn btn-warning mt-2 w-100">Generar Ticket</button>
        </div>
    </div>
</div>
        </div>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>© 2025 RestaurantesNL - Todos los derechos reservados</p>
    <p>Síguenos en
        <a href="#" class="text-warning">Instagram</a> |
        <a href="#" class="text-warning">Facebook</a>
    </p>
</footer>

<script>
let items = [];
let subtotal = 0;
let itbis = 0;
let total = 0;

function actualizarTotales() {
    itbis = subtotal * 0.18;
    total = subtotal + itbis;

    document.getElementById('subtotal').textContent = subtotal.toLocaleString('es-DO', { minimumFractionDigits: 2 });
    document.getElementById('itbis').textContent = itbis.toLocaleString('es-DO', { minimumFractionDigits: 2 });
    document.getElementById('total').textContent = total.toLocaleString('es-DO', { minimumFractionDigits: 2 });

    document.getElementById('items-input').value = JSON.stringify(items);
    document.getElementById('subtotal-input').value = subtotal.toFixed(2);
    document.getElementById('itbis-input').value = itbis.toFixed(2);
    document.getElementById('total-input').value = total.toFixed(2);
}

document.querySelectorAll('.add-item').forEach(btn => {
    btn.addEventListener('click', () => {
        const desc = btn.dataset.descripcion;
        const precio = parseFloat(btn.dataset.precio);

        const item = { descripcion: desc, precio: precio };
        items.push(item);
        subtotal += precio;

        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';

        const spanDesc = document.createElement('span');
        spanDesc.textContent = desc;

        const spanPrecio = document.createElement('span');
        spanPrecio.textContent = 'RD$ ' + precio.toLocaleString('es-DO', { minimumFractionDigits: 2 });

        const btnRemove = document.createElement('button');
        btnRemove.className = 'btn btn-sm btn-danger ms-2';
        btnRemove.textContent = '✖';
        btnRemove.addEventListener('click', () => {
            // Eliminar del array
            const index = items.findIndex(i => i.descripcion === desc && i.precio === precio);
            if (index !== -1) {
                items.splice(index, 1);
                subtotal -= precio;
            }
            li.remove();
            actualizarTotales();
        });

        const rightBox = document.createElement('div');
        rightBox.className = 'd-flex align-items-center';
        rightBox.appendChild(spanPrecio);
        rightBox.appendChild(btnRemove);

        li.appendChild(spanDesc);
        li.appendChild(rightBox);
        document.getElementById('ticket-items').appendChild(li);

        actualizarTotales();
    });
});
</script>

</body>
</html>
