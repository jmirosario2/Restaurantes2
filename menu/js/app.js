let carrito = [];
let total = 0;

document.addEventListener("DOMContentLoaded", () => {
  cargarInsumos();

  document.getElementById("btn-generar").addEventListener("click", generarTicket);
});

function cargarInsumos() {
  axios.get("php/get_insumos.php").then(res => {
    console.log("Respuesta del servidor:", res.data);

    // Si el backend devuelve un objeto, extrae el arreglo
    let datos = Array.isArray(res.data) ? res.data : res.data.data || [];

    const cont = document.getElementById("menu-container");
    cont.innerHTML = "";

    datos.forEach(item => {
      // Manejo seguro de imágenes base64
      let foto = item.foto ? item.foto.trim() : "";
      let imgSrc = foto
        ? (foto.startsWith("data:image") ? foto : `data:image/png;base64,${foto}`)
        : "https://cdn-icons-png.flaticon.com/512/17185/17185463.png"; // Imagen por defecto

      cont.innerHTML += `
        <div class="col-md-3 mb-4">
          <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
            <img src="${imgSrc}" class="card-img-top" alt="${item.descripcion}" style="height:180px;object-fit:cover;">
            <div class="card-body text-center">
              <h6 class="fw-bold mb-2">${item.descripcion}</h6>
              <p class="text-primary fw-semibold mb-3">${parseFloat(item.precio).toFixed(2)} RD$</p>
              <button class="btn btn-success w-100 rounded-pill" onclick='agregar("${item.id}", "${item.descripcion}", ${item.precio})'>Agregar</button>
            </div>
          </div>
        </div>`;
    });
  }).catch(err => {
    console.error("Error cargando insumos:", err);
  });
}



function agregar(id, descripcion, precio) {
  carrito.push({ id, descripcion, precio });
  actualizarTicket();
}

function actualizarTicket() {
  const ticket = document.getElementById("ticket");
  if (carrito.length === 0) {
    ticket.innerHTML = "<p class='text-muted'>No hay elementos seleccionados.</p>";
    return;
  }

  let subtotal = 0;
  carrito.forEach(item => {
    subtotal += parseFloat(item.precio);
  });
  const itbis = subtotal * 0.18;
  const total = subtotal + itbis;

  let html = `
  <div class="ticket-header text-center">
    <h5><strong>Restaurante NL</strong></h5>
    <p class="small text-muted">Ticket de Orden</p>
    <hr>
  </div>
  <div class="ticket-cliente">
    <p><strong>Cliente:</strong> ${document.getElementById("nombre").value || ''} ${document.getElementById("apellido").value || ''}</p>
    <p><strong>Tel:</strong> ${document.getElementById("telefono").value || ''}</p>
    <p><strong>Correo:</strong> ${document.getElementById("correo").value || ''}</p>
    <p><strong>Dirección:</strong> ${document.getElementById("direccion").value || ''}</p>
  </div>
  <hr>
  <table class="table table-sm mb-2">
    <thead>
      <tr><th>Descripción</th><th class="text-end">Precio</th></tr>
    </thead>
    <tbody>`;

  carrito.forEach(item => {
    html += `
      <tr>
        <td>${item.descripcion}</td>
        <td class="text-end">${item.precio.toFixed(2)} RD$</td>
      </tr>`;
  });

  html += `
    </tbody>
  </table>
  <hr>
  <div class="ticket-totales">
    <div class="d-flex justify-content-between"><span>Subtotal:</span><span>${subtotal.toFixed(2)} RD$</span></div>
    <div class="d-flex justify-content-between"><span>ITBIS (18%):</span><span>${itbis.toFixed(2)} RD$</span></div>
    <div class="d-flex justify-content-between total-line"><strong>Total:</strong><strong>${total.toFixed(2)} RD$</strong></div>
  </div>`;

  ticket.innerHTML = html;
}

function generarTicket() {
  const cliente = {
    nombre: document.getElementById("nombre").value,
    apellido: document.getElementById("apellido").value,
    telefono: document.getElementById("telefono").value,
    correo: document.getElementById("correo").value,
    direccion: document.getElementById("direccion").value
  };

  let subtotal = carrito.reduce((acc, item) => acc + parseFloat(item.precio), 0);
  let itbis = subtotal * 0.18;
  let total = subtotal + itbis;

  axios.post("php/generate_ticket.php", { cliente, items: carrito, total, subtotal, itbis })
    .then(res => {
      const opt = {
        margin: 10,
        filename: "ticket.pdf",
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
      };
      html2pdf().from(res.data).set(opt).save();
    });
}

