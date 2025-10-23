<template>
    <section>
        <p class="title is-1 has-text-weight-bold">
            <b-icon
                icon="order-bool-ascending-variant"
                size="is-large"
                type="is-primary">
            </b-icon>
            Realizar orden
        </p>

        <div class="columns is-multiline">
            <div class="column"
            v-for="mesa in mesas"
            :key="mesa.mesa.idMesa">

                <div class="box">
                    <p class="title is-2 has-text-grey">Mesa #{{ mesa.mesa.idMesa }}
<p v-if="mesa.insumos && mesa.insumos.length > 0">
  Total insumos: {{ mesa.insumos.length }}
</p>

                    </p>
                    <p v-if="mesa.mesa.atiende">
                        <strong>Atiende</strong>: {{ mesa.mesa.atiende }}
                    </p>
                    <p v-if="mesa.mesa.cliente">
                        <strong>Cliente</strong>: {{ mesa.mesa.cliente }}
                    </p>
                    <b-collapse
                        class="card"
                        animation="slide"
                        aria-id="contentIdForA11y3"
                        v-if="mesa.mesa.estado === 'ocupada'">
                        <template #trigger="props">
                            <div
                                class="card-header"
                                role="button"
                                aria-controls="contentIdForA11y3"
                                :aria-expanded="props.open">
                                <p class="card-header-title">
                                    Insumos en la orden
                                </p>
                                <a class="card-header-icon">
                                    <b-icon
                                        :icon="props.open ? 'menu-down' : 'menu-up'">
                                    </b-icon>
                                </a>
                            </div>
                        </template>

                        <div class="card-content">
                            <div class="content">
                                <b-table
                                :data="mesa.insumos"
                                :checked-rows.sync="checkedRows"
                                :is-row-checkable="(row) => row.estado !== 'entregado'"
                                checkable
                                :checkbox-position="checkboxPosition"
                                :checkbox-type="checkboxType">

                                    <b-table-column field="codigo" label="Código" v-slot="props">
                                        {{ props.row.codigo }}
                                    </b-table-column>

                                    <b-table-column field="nombre" label="Nombre" v-slot="props">
                                        {{ props.row.nombre }}
                                    </b-table-column>

                                    <b-table-column field="caracteristicas" label="Características" v-slot="props">
                                        {{ props.row.caracteristicas }}
                                    </b-table-column>

                                    <b-table-column field="cantidad" label="Cantidad" v-slot="props">
                                        {{ props.row.cantidad }} X ${{  props.row.precio }}
                                    </b-table-column>

                                    <b-table-column field="subtotal" label="Subtotal" v-slot="props">
                                        ${{ props.row.cantidad * props.row.precio }}
                                    </b-table-column>
                                    <b-table-column field="estado" label="" v-slot="props">
                                        <b-icon icon="alert" type="is-danger" v-if="props.row.estado ==='pendiente'"></b-icon>
                                        <b-icon icon="check" type="is-success" v-if="props.row.estado ==='entregado'"></b-icon>
                                    </b-table-column>
                                </b-table>
                            </div>
                        </div>
                    </b-collapse>
                    <br>
                    <div class="has-text-centered">
                      <b-button type="is-info" icon-left="plus" @click="ocuparMesa(mesa)">
  Agregar insumos
</b-button>

<b-button type="is-success" icon-left="cash-register" @click="cobrar(mesa)">
  Cobrar
</b-button>
                       <!--  <b-button type="is-primary" icon-left="check" @click="ocuparMesa(mesa)" v-if="mesa.mesa.estado === 'libre'">Ocupar</b-button>
                        <div class="field is-grouped is-centered" v-if="mesa.mesa.estado === 'ocupada'">
                            <p class="control">
                                <b-button type="is-success" icon-left="cash" @click="cobrar(mesa)">Cobrar</b-button>
                            </p> -->
                           <!--  <p class="control">
                                <b-button type="is-info" icon-left="plus" @click="ocuparMesa(mesa)">Agregar  insumos</b-button>
                            </p> -->
                            <p class="control">
                                <b-button type="is-warning" icon-left="check" v-if="checkedRows.length > 0" @click="marcarInsumosEntregados(mesa)">Marcar entrega</b-button>
                            </p>
                            <p class="control">
                                <b-button type="is-danger" icon-left="close"  @click="cancelarOrden(mesa.mesa.idMesa)">Cancelar</b-button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-loading :is-full-page="true" v-model="cargando" :can-cancel="false"></b-loading>
        <ticket @impreso="onImpreso" :venta="this.ventaSeleccionada" :insumos="insumosSeleccionados" :datosLocal="datos" :logo="logo" v-if="mostrarTicket"></ticket>

    </section>
</template>
<script>
import HttpService from "@/Servicios/HttpService";
import Ticket from "@/components/Ventas/Ticket.vue";

export default {
  name: "RealizarOrden",


  data: () => ({
    mesas: [],
    cargando: false,
    mostrarTicket: false,
    ventaSeleccionada: {},
    insumosSeleccionados: [],
    datos: {},
    logo: null,
    checkedRows: []
  }),

  mounted() {
    this.crearMesas();
  },

  methods: {
    crearMesas() {
      this.cargando = true;
      HttpService.obtener("obtener_mesas.php").then((resultado) => {
        this.mesas = resultado;
        this.cargando = false;
      });
    },

    ocuparMesa(mesa) {
      if (!mesa || !mesa.mesa || !mesa.mesa.idMesa) return;
      this.$router.push({
        name: "Ordenar",
        params: {
          id: mesa.mesa.idMesa,
          insumosEnLista: mesa.insumos,
          cliente: mesa.mesa.cliente,
          atiende: mesa.mesa.atiende,
          idUsuario: mesa.mesa.idUsuario
        }
      });
    },

    cancelarMesa(mesa) {
      console.log("Cancelando mesa:", mesa.mesa.idMesa);
      if (!mesa || !mesa.mesa || !mesa.mesa.idMesa) return;
      HttpService.eliminar("cancelar_mesa.php", mesa.mesa.idMesa).then((resultado) => {
        if (resultado) {
          this.$buefy.toast.open({
            message: "Mesa liberada correctamente",
            type: "is-success"
          });
          this.crearMesas();
        } else {
          this.$buefy.toast.open({
            message: "No se pudo liberar la mesa",
            type: "is-danger"
          });
        }
      });
    },

    cobrar(mesa) {
      const montoPagado = parseFloat(prompt("Monto pagado:"));
      const total = parseFloat(mesa.mesa.total);

      if (isNaN(montoPagado) || montoPagado < total) {
        this.$buefy.toast.open({
          message: "Monto insuficiente",
          type: "is-danger"
        });
        return;
      }

      const cambio = montoPagado - total;
      const payload = {
        idMesa: mesa.mesa.idMesa,
        total: total,
        cliente: mesa.mesa.cliente,
        atiende: mesa.mesa.atiende,
        idUsuario: mesa.mesa.idUsuario,
        insumos: mesa.insumos
      };

      HttpService.registrar(payload, "registrar_venta.php").then((registrado) => {
        if (registrado) {
          this.$buefy.dialog.alert({
            title: "Venta registrada",
            message: `Gracias por su compra, su cambio <b>$${cambio}</b>`,
            confirmText: "OK"
          });

          this.imprimirComprobante(payload);

          HttpService.eliminar("cancelar_mesa.php", mesa.mesa.idMesa).then(() => {
            this.crearMesas();
          });
        } else {
          this.$buefy.toast.open({
            message: "Error al registrar la venta",
            type: "is-danger"
          });
        }
      });
    },

    imprimirComprobante(payload) {
      this.ventaSeleccionada = payload;
      this.mostrarTicket = true;
      console.log("Comprobante generado:", payload);
    }
  }
};
</script>
