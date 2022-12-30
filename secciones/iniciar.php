<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEDIDOS</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.21/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.1/axios.min.js"></script>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="../css/tabla.css" rel="stylesheet">
    <link href="../css/modal.css" rel="stylesheet">
    <link href="../css/opciones.css" rel="stylesheet">
    

</head>
<body>
    
    <div id="app">

        <?php require("../shared/opciones.php")?>

        <div class="container">
        
            <!-- START BOTON MIS PEDIDOS -->
            <button type="button" @click="iniciarPedido()" class="btn boton">
                Mis pedidos
            </button>
            <!-- START BOTON MIS PEDIDOS -->
            
            <!-- START COMPONENTE LOADING BUSCANDO ARTICULOS -->
            <div class="contenedorLoading" v-if="buscandoArticulos">
                <div class="loading">
                    <div class="spinner-border" role="status">
                        <span class="sr-only"></span>
                    </div>
                </div>
            </div>
            <!-- END COMPONENTE LOADING BUSCANDO ARTICULOS -->

            <!-- START TABLA ARTICULOS -->
            <div class="contenedorTabla" v-else>
                <table class="table table-hover">
                    <thead class="tituloColumna">
                        <th class="hide">
                            ID
                        </th>
                        <th >
                            Articulo
                        </th>
                        <th>
                            Cantidad
                        </th>
                        <th>
                            Categoria
                        </th>
                    </thead>
                    <tbody v-if="articulos.length != 0">
                        <tr v-for="articulo in articulos">
                            <td class="hide">
                                {{articulo.id}}
                            </td>
                            <td class="columnaArticulo">
                                <div class="descripcionArticulo">
                                    {{articulo.descripcion.toUpperCase()}}
                                    
                                </div>
                                <div class="descripcionMedida">
                                    {{medidas.filter(element => element.id == articulo.medida)[0]["descripcion"]}}
                                </div>
                            </td>
                            <td class="columnaCantidad">
                                <input class="form-control" type="number" min="0" maxlength="3" @keyup="validarCampo('articulo.id')">
                            </td>
                            <td class="columnaCategoria">
                                {{articulo.categoria}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="articulos.length == 0">
                    <span class="sinResultados">
                       NO SE ENCONTRÓ RESULTADOS PARA MOSTRAR
                    </span>
                </div>
            </div>
            <!-- END TABLA PEDIDOS -->

            <div v-if="modal">
                <!-- The Modal -->
                <div id="myModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <div class="">
                        <div class="row d-flex justify-content-center tituloModal">    
                            <h5 class="d-flex justify-content-center">CONFIRMACION</h5>
                        </div>

                        <div class="row d-flex justify-content-center my-3">
                            <b class="d-flex justify-content-center">¿Desea {{accionModal}} el articulo?</b>
                        </div>

                        <div class="row d-flex justify-content-center my-3">
                            Descripcion: {{app.descripcion}} 
                            <br>
                            Categoria: {{categorias.filter(element => element.id == app.categoria)[0]["descripcion"]}}
                            <br>
                            Medida: {{medidas.filter(element => element.id == app.medida)[0]["descripcion"]}}
                            <br>
                            Vigente: {{app.vigente == 0 ? "No" : "Sí"}}
                            <br>
                        </div>
                        
                        <div class="row d-flex justify-content-around">
                            <div class="col-sm-12 col-md-6 d-flex justify-content-center">
                                <button type="button" @click="cancelarModal()" class="btn boton" >Cancelar</button>
                            </div>

                            <div class="col-sm-12 col-md-6 d-flex justify-content-center mt-sm-3 mt-md-0">
                                <!-- BOTONES CONFIRMACION CREACION -->
                                <button type="button" @click="confirmarCreacion()" class="btn botonConfirm" v-if="accionModal == 'crear' && !creando">Crear</button>
                                <button type="button" class="btn botonConfirm" v-if="accionModal == 'crear' && creando">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </button>
                                <!-- BOTONES CONFIRMACION CREACION -->

                                <!-- BOTONES CONFIRMACION EDICION -->
                                <button type="button" @click="confirmarEdicion()" class="btn botonConfirm" v-if="accionModal == 'modificar' && !editando">Editar</button>
                                <button type="button" class="btn botonConfirm" v-if="accionModal == 'modificar' && editando">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </button>
                                <!-- BOTONES CONFIRMACION EDICION -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>

            <div role="alert" id="mitoast" aria-live="assertive" aria-atomic="true" class="toast">
                <div class="toast-header">
                    <!-- Nombre de la Aplicación -->
                    <div class="row tituloToast" id="tituloToast">
                        <strong class="mr-auto">{{tituloToast}}</strong>
                    </div>
                </div>
                <div class="toast-content">
                    <div class="row textoToast">
                        <strong >{{textoToast}}</strong>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <style>
        .hide{
            display: none;
        }
        #mitoast {
            visibility: hidden;
            position: fixed;
            z-index: 4;
            left:10px;
            bottom: 5%;
            border: 1px solid rgba(0,0,0,.1);
            border-radius: .25rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,.1);
            max-width: 250px;
            width: 250px;
            height: auto;
            /* background-color: #ffffff; */
            background-color: #F2F3F4;
            opacity: 1;
        }
        div.toast-content{
            min-height: 80px !important;
        }
        div.row.textoToast{
            min-height: 80px !important;
        }
        .bordeExito {
            border-left: 10px solid green !important;
        }
        .bordeError {
            border-left: 10px solid red !important;;
        }
        div.row.textoToast >> strong{
            display: flex;
            align-items: center
        }
        .exito {
            width: 100%;
            text-align: center;
            color: green;
            margin: 10px auto !important;
        }
        .errorModal {
            width: 100%;
            text-align: center;
            margin: 10px auto !important;
            color: red;
            border: none;
        }
        .errorInput {
            /* width: 100%;
            text-align: center;
            margin: 10px auto !important;
            color: red;
            border: none; */
            border: solid 1px red;
        }
        #mitoast.mostrar {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 4.6s;
            animation: fadein 0.5s, fadeout 0.5s 4.6s;
        }
        div.toast-header{
            text-align:center !important;
        }
 
        @keyframes fadein {
            0%   {background-color:white; left:10px; bottom:0px;}
            100% {background-color:white; left:10px; bottom:5%;}
        }
        .tituloToast{
            width: 100%;
            height: 20%;
            line-height: 20px;
            padding: 10px 0;
            margin: auto !important;
            text-align: center !important;
            color: green;
        }
        .textoToast{
            width: 100%;
            height: 80%;
            margin: auto;
            text-align: center;
            
        }
        .errorLabel{
            color: red;
            font-size: 10px;
        }






        /* ESTILOS LOADING */
        .contenedorLoading {
            color: rgb(107, 69, 142);
            border: solid 1px rgb(107, 69, 142);
            border-radius: 10px;
            padding: 10xp;
            margin-top: 24px;
            width: 100%;
            height: 200px;
        }
        .loading {
            width: 100%;
            height: 100% !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* ESTILOS LOADING */


      

           
        .descripcionMedida{
            font-size: 12px;
            height: 12px;
            text-align: left;
            margin-left: 10px;
            line-height:12px;
        }
        .descripcionArticulo{
            font-size: 16px;
            margin-top: 10px;
            font-weight: bolder;
            margin-left: 10px;
            text-align: left;
            height:20px;
            line-height:20px;
        }
        input.form-control{
            margin-top: 7px
        }
        td{
            height: 60px !important;
        }
        .columnaArticulo{
            width: 40%;
        }
        .columnaCantidad{
            width: 30%;
        }
        .columnaCategoria{
            width: 30%;
        }

    </style>
    <script>

        var app = new Vue({
            el: "#app",
            components: {
            },
            data: {
                tituloToast: "",
                textoToast: "",
                modal: false,
                idPedido:null,
                buscandoArticulos: false,
                //
                confirm: false,
                enviando: false,
                articulos: [],
                medidas: [
                    {
                        id: "kg",
                        descripcion: "Kilogramos"
                    },
                    {
                        id: "sq",
                        descripcion: "Saquitos"
                    },
                    {
                        id: "lt",
                        descripcion: "Latas"
                    },
                    {
                        id: "un",
                        descripcion: "Unidades"
                    },
                    {
                        id: "so",
                        descripcion: "Sobres"
                    },
                    {
                        id: "ca",
                        descripcion: "Cajas"
                    },
                    {
                        id: "ro",
                        descripcion: "Rollos"
                    }
                ]
            },
            mounted: function() {
                this.consultarArticulos();
            },
            methods:{
                iniciarPedido () {
                    window.location.href = 'http://localhost/proyectos/pedidos2/secciones/iniciar.php';   
                },
                cancelarModal(){
                    app.modal = false;
                },
                // confirmarCreacion () {
                //     app.creando = true;
                //     let formdata = new FormData();
                //     formdata.append("descripcion", app.descripcion);
                //     formdata.append("medida", app.medida);
                //     formdata.append("categoria", app.categoria);
                //     axios.post("http://localhost/proyectos/pedidos2/conexion/api.php?accion=insertarArticulo", formdata)
                //     .then(function(response){
                //         if (response.data.error) {
                //             app.mostrarToast("Error", response.data.mensaje);
                //         } else {
                //             app.mostrarToast("Éxito", response.data.mensaje);
                //             app.modal = false;
                //             app.mostrarABM = false;
                //             app.descripcion = null;
                //             app.categoria = null;
                //             app.medida = null;
                //             app.consultarArticulos();
                //         }
                //         app.creando = false;
                //     }).catch( error => {
                //         app.creando = false;
                //         app.mostrarToast("Error", response.data.mensaje);
                //     })
                // },
                // confirmarEdicion () {
                //     app.editando = true;
                //     let formdata = new FormData();
                //     formdata.append("id", app.idArticulo);
                //     formdata.append("categoria", app.categoria);
                //     formdata.append("descripcion", app.descripcion);
                //     formdata.append("medida", app.medida);
                //     formdata.append("vigente", app.vigente);
                    
                //     axios.post("http://localhost/proyectos/pedidos2/conexion/api.php?accion=editarArticulo", formdata)
                //     .then(function(response){
                //         if (response.data.error) {
                //             app.mostrarToast("Error", response.data.mensaje);
                //         } else {
                //             app.mostrarToast("Éxito", response.data.mensaje);
                //             app.modal = false;
                //             app.mostrarABM = false;
                //             app.idArticulo= null;
                //             app.descripcion = null;
                //             app.categoria = null;
                //             app.medida = null;
                //             app.vigente = null;
                //             app.consultarArticulos();
                //         }
                //         app.editando = false;
                //     }).catch( error => {
                //         app.editando = false;
                //         app.mostrarToast("Error", response.data.mensaje);
                //     })
                // },
                mostrarToast(titulo, texto) {
                    app.tituloToast = titulo;
                    app.textoToast = texto;
                    var toast = document.getElementById("mitoast");
                    var tituloToast = document.getElementById("tituloToast");
                    toast.classList.remove("toast");
                    toast.classList.add("mostrar");
                    setTimeout(function(){ toast.classList.toggle("mostrar"); }, 10000);
                    if (titulo == 'Éxito') {
                        toast.classList.remove("bordeError");
                        toast.classList.add("bordeExito");
                        tituloToast.className = "exito";
                    } else {
                        toast.classList.remove("bordeExito");
                        toast.classList.add("bordeError");
                        tituloToast.className = "errorModal";
                    }
                },
                consultarArticulos() {
                    this.buscandoArticulos = true;
                    axios.get("http://localhost/proyectos/pedidos2/conexion/api.php?accion=consultarArticulos")
                    .then(function(response){
                        app.buscandoArticulos = false;
                        if (response.data.error) {
                            app.mostrarToast("Error", response.data.mensaje);
                        } else {
                            app.articulos = response.data.articulos;
                        }
                    })
                    
                }
            }
        })
    </script>
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>