<template>
    <section>
        <ul v-if="errores.length > 0">
            <li class="has-text-danger has-text-centered" v-for="error in errores" :key="error">{{ error }}</li>
        </ul>
        <b-field label="Tipo" >
            <b-select v-model="insumo.tipo" @change.native="obtenerCategorias">
<option value="" disabled selected>Selecciona el tipo de insumo</option>
<option value="PLATILLO">
 Platillo
 </option>
<option value="BEBIDA">
Bebida
 </option>
 </b-select>
        </b-field>

        <b-field label="Código" >
            <b-input type="text" placeholder="Código identificador del insumo" v-model="insumo.codigo"></b-input>
        </b-field>

        <b-field label="Nombre" >
           <b-input type="text" placeholder="Nombre del insumo" v-model="insumo.nombre"></b-input>
        </b-field>

        <b-field label="Descripción" >
            <b-input maxlength="200" type="textarea"
placeholder="Escribe una pequeña descripción del insumo" v-model="insumo.descripcion"></b-input>
        </b-field>

        <b-field label="Categoría" >
            <b-select v-model="insumo.categoria">
<option value="" selected disabled>Selecciona la categoría</option>
<option v-for="categoria in categorias" :key="categoria.id" :value="categoria.id">
{{ categoria.nombre }}
 </option>
 </b-select>
        </b-field>

        <b-field label="Precio" >
            <b-input type="number" placeholder="Precio de venta del insumo" v-model="insumo.precio"></b-input>
        </b-field>

        <b-field label="Foto del platillo" >
            <b-upload v-model="foto" accept="image/*">
                <a class="button is-primary">
                    <b-icon icon="upload"></b-icon>
                    <span>{{ foto ? foto.name : 'Click para subir foto' }}</span>
                </a>
            </b-upload>
        </b-field>
        <div class="has-text-centered">
 <b-button type="is-success" size="is-large" icon-left="check" @click="registrar">Registrar</b-button>
 </div>

    </section>
</template>
<script>
import Utiles from '../../Servicios/Utiles'
import HttpService from '../../Servicios/HttpService'

export default {
    name: "DatosInsumo",
    props: ["insumo"],

    data: () => ({
        errores: [],
        categorias: [],
        foto: null // <-- 1. AÑADIMOS LA FOTO AL DATA LOCAL
    }),

    methods: {
        registrar() {
            // 2. COMBINAMOS EL PROP 'insumo' CON LA 'foto' LOCAL
            const insumoCompleto = {
                tipo: this.insumo.tipo,
                codigo: this.insumo.codigo,
                nombre: this.insumo.nombre,
                descripcion: this.insumo.descripcion,
                categoria: this.insumo.categoria,
                precio: this.insumo.precio,
                foto: this.foto // <-- La foto (que es un objeto File)
            }

            // 3. VALIDAMOS EL OBJETO COMPLETO
            // (Asegúrate que tu 'Utiles.validar' sepa qué hacer con 'foto')
            this.errores = Utiles.validar(insumoCompleto)

            if(this.errores.length > 0) return

            // 4. EMITIMOS EL OBJETO COMPLETO AL PADRE
            this.$emit("registrado", insumoCompleto)
        },

        obtenerCategorias() {
            HttpService.obtenerConDatos(this.insumo.tipo, "obtener_categorias_tipo.php")
                .then(resultado => {
                    this.categorias = resultado
                })
        }
    }
}
</script>
