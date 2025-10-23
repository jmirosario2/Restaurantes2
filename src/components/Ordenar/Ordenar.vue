<template>
  <section>
    <p class="title is-1 has-text-weight-bold">
      <b-icon icon="pen" size="is-large" type="is-primary" />
      Tomar orden para la mesa #{{ idMesa }}
      <b-field label="Nombre del cliente (Opcional)" class="is-pulled-right" expanded>
        <b-input placeholder="Ej. Don Paco" v-model="cliente" />
      </b-field>
    </p>

    <div class="title is-3 has-text-weight-bold has-text-grey">
      <div class="is-pulled-right">
        <span class="has-text-weight-bold has-text-primary" style="font-size: 2.5em">
          ${{ total }}
        </span>
        <b-button
          type="is-success"
          size="is-large"
          icon-left="basket-check"
          style="margin-top: 18px"
          @click="procesarOrden"
        >
          {{ estaAgregandoInsumos ? 'Añadir' : 'Ordenar' }}
        </b-button>
      </div>
    </div>

    <b-field>
      <b-autocomplete
        size="is-large"
        v-model="nombre"
        placeholder="Nombre del platillo o bebida"
        :data="filteredDataObj"
        field="nombre"
        @input="buscarInsumo"
        @select="agregarInsumoAOrden"
        :clearable="true"
        keep-first
        id="busqueda"
      />
    </b-field>

    <div class="columns is-desktop">
      <div class="column" v-if="insumosOrden.length || insumosAnteriores.length">
        <p class="has-text-primary size-is-4" v-if="insumosOrden.length">
          <b-icon icon="plus" /> Insumos agregados
        </p>
        <productos-orden
          :lista="insumosOrden"
          tipo="nuevo"
          @modificado="calcularTotal"
          @quitar="eliminar"
        />

        <p class="has-text-primary size-is-4" v-if="insumosAnteriores.length">
          <b-icon icon="basket" /> Insumos servidos
        </p>
        <productos-orden :lista="insumosAnteriores" tipo="entregado" />
      </div>

      <div class="column is-2" v-if="insumos.length">
        <p class="title is-6 has-text-weight-bold has-text-grey">También te puede interesar</p>
        <div v-for="insumo in insumos" :key="insumo.id" class="card">
          <header class="card-header">
            <div class="card-header-title is-size-7">
              <b-icon
                size="is-small"
                icon="noodles"
                class="has-text-info"
                v-if="insumo.tipo === 'PLATILLO'"
              />
              <b-icon
                icon="cup"
                size="is-small"
                class="has-text-success"
                v-if="insumo.tipo === 'BEBIDA'"
              />
              {{ insumo.nombre }}
            </div>
            <b-button
              class="mb-1 is-pulled-right"
              type="is-primary"
              size="is-small"
              icon-left="plus"
              @click="agregarInsumoAOrden(insumo)"
            />
          </header>
          <div class="card-content">
            <div class="content is-size-7">
              {{ insumo.descripcion }}
              <div class="has-text-centered has-text-weight-bold">
                ${{ insumo.precio }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import HttpService from "../../Servicios/HttpService";
import ProductosOrden from "./ProductosOrden.vue";

export default {
  name: "Ordenar",
  components: { ProductosOrden },
  data: () => ({
    idMesa: "",
    insumos: [],
    nombre: "",
    insumosAnteriores: [],
    insumosOrden: [],
    total: 0,
    cliente: "",
    estaAgregandoInsumos: false,
  }),

  mounted() {
    this.$nextTick(() => {
      const input = document.querySelector("#busqueda");
if (input) input.focus();
    });
    this.idMesa = this.$route.params.id;
    this.cliente = this.$route.params.cliente || "";
    this.insumosAnteriores = this.$route.params.insumosEnLista || [];
    this.estaAgregandoInsumos = this.insumosAnteriores.length > 0;
    if (this.estaAgregandoInsumos) {
      this.calcularTotal();
    }
  },

  methods: {
    procesarOrden() {
      const payload = {
        id: this.idMesa,
        insumos: this.estaAgregandoInsumos
          ? [...this.insumosAnteriores, ...this.insumosOrden]
          : this.insumosOrden,
        total: this.total,
        atiende: localStorage.getItem("nombreUsuario"),
        idUsuario: localStorage.getItem("idUsuario"),
        cliente: this.cliente,
      };

      const endpoint = this.estaAgregandoInsumos ? "editar_mesa.php" : "ocupar_mesa.php";

      HttpService.registrar(payload, endpoint).then((resultado) => {
        if (resultado) {
          this.$buefy.toast.open({
            message: "Insumos agregados",
            type: "is-success",
          });
          this.$router.push({ name: "RealizarOrden" });
        }
      });
    },

    eliminar(idInsumo) {
      this.insumosOrden = this.insumosOrden.filter((insumo) => insumo.id !== idInsumo);
      this.calcularTotal();
    },

    calcularTotal() {
      const todos = [...this.insumosAnteriores, ...this.insumosOrden];
      this.total = todos.reduce((acc, insumo) => {
        const cantidad = parseFloat(insumo.cantidad) || 0;
        const precio = parseFloat(insumo.precio) || 0;
        return acc + cantidad * precio;
      }, 0);
    },

    buscarInsumo() {
      if (this.nombre) {
        HttpService.obtenerConDatos(this.nombre, "obtener_insumo_nombre.php").then((resultado) => {
          this.insumos = resultado;
        });
      }
    },

    agregarInsumoAOrden(insumo) {
      if (!insumo) return;
      const indice = this.verificarSiExisteEnLista(insumo.id);
      if (indice >= 0) {
        this.aumentarCantidad(indice);
      } else {
        this.insumosOrden.push({
          id: insumo.id,
          codigo: insumo.codigo,
          nombre: insumo.nombre,
          precio: insumo.precio,
          caracteristicas: "",
          cantidad: 1,
          estado: "pendiente",
        });
      }
      this.$nextTick(() => (this.nombre = ""));
      this.calcularTotal();
    },

    verificarSiExisteEnLista(idInsumo) {
      return this.insumosOrden.findIndex((insumo) => insumo.id === idInsumo);
    },

    aumentarCantidad(indice) {
      this.insumosOrden[indice].cantidad++;
    },
  },

  computed: {
    filteredDataObj() {
      return this.insumos.filter((option) =>
        option.nombre.toLowerCase().includes(this.nombre.toLowerCase())
      );
    },
  },
};
</script>
