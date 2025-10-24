const RUTA_GLOBAL = "http://localhost/Restaurantes2/api/"

const HttpService = {
  async registrar(datos, ruta) {
    const respuesta = await fetch(RUTA_GLOBAL + ruta, {
      method: "POST",
     /*  headers: {
        "Content-Type": "application/json" // ✅ encabezado obligatorio
      }, */
      body: JSON.stringify(datos)
    });

    const resultado = await respuesta.json();
    return resultado;
  }
,

    async obtenerConDatos(datos, ruta){
        const respuesta = await fetch(RUTA_GLOBAL + ruta, {
            method: "post",
            body: JSON.stringify(datos),
        });
        let resultado = await respuesta.json()
        return resultado
    },


    async obtener(ruta){
        let respuesta = await fetch(RUTA_GLOBAL + ruta)
        let datos = await respuesta.json()
        return datos
    },

/*     async eliminar(ruta, id) {
        const respuesta = await fetch(RUTA_GLOBAL + ruta, {
            method: "post",
            body: JSON.stringify(id),
        });
        let resultado = await respuesta.json()
        return resultado
    } */

        eliminar(endpoint, id) {
  return fetch(`http://localhost/Restaurantes2/api/${endpoint}?id=${id}`, {
    method: "GET"
  })
    .then(res => res.json())
    .catch(err => {
      console.error("Error al eliminar:", err);
      return false;
    });
}


}

export default  HttpService
