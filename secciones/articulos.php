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
 

</head>
<body>
    
    <div id="app">

        <!-- <nav class="nav d-flex justify-content-center opciones">
            <a class="nav-link" href="usuarios.html">Usuarios</a>
            <a class="nav-link active" aria-current="page" href="sedes.html">Sedes</a>
            <a class="nav-link" href="articulos.html">Articulos</a>
            <a class="nav-link" href="pedidos.html">Pedidos</a>
        </nav> -->
        <?php require("../shared/opciones.php")?>

        <div class="container">
            
            <button class="botonNuevo">
                Nuevo articulo
            </button>
            
            
            <div class="contenedorTabla">
                <table class="table table-hover">
                    <thead class="tituloColumna">
                        <th>
                            ID
                        </th>
                        <th>
                            Provincia
                        </th>
                        <th>
                            Localidad
                        </th>
                        <th>
                            Casas
                        </th>
                        <th style="width: 150px">
                            ACCION
                        </th>
                    </thead>
                    <tbody v-if="sedes.length != 0">
                        <tr v-for="sede in sedes">
                            <td>
                                {{sede.id}}
                            </td>
                            <td>
                                {{sede.provincia}}
                            </td>
                            <td>
                                {{sede.localidad}}
                            </td>
                            <td>
                                {{sede.casas}}
                            </td>
                            <td>
                                <button class="botonAccion botonEdit" @click="editarUsuario = true, elegir(usuario)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                                    </svg>
                                </button>
                                <button class="botonAccion botonDelete" @click="eliminarUsuario = true, elegir(usuario)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eraser-fill" viewBox="0 0 16 16">
                                        <path d="M8.086 2.207a2 2 0 0 1 2.828 0l3.879 3.879a2 2 0 0 1 0 2.828l-5.5 5.5A2 2 0 0 1 7.879 15H5.12a2 2 0 0 1-1.414-.586l-2.5-2.5a2 2 0 0 1 0-2.828l6.879-6.879zm.66 11.34L3.453 8.254 1.914 9.793a1 1 0 0 0 0 1.414l2.5 2.5a1 1 0 0 0 .707.293H7.88a1 1 0 0 0 .707-.293l.16-.16z"/>
                                    </svg>
                                </button>
                                <button class="botonAccion botonReset" @click="eliminarUsuario = true, elegir(usuario)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="sedes.length == 0">
                    <span class="sinResultados">
                       NO SE ENCONTRÓ RESULTADOS PARA MOSTRAR
                    </span>
                </div>
            </div>
        </div>
        <boton-contador></boton-contador>
    </div>
   
     

    <style>
        .opciones a{
            height: 30px;
            margin: 20px 0 20px;
        }
        .opciones a{
            color: rgb(107, 69, 142);
            font-weight: bolder;
            display: flex;
            align-items: center;
        }
        .opciones a:hover{
            color: white;
            border-radius: 3px;
            background-color: rgb(107, 69, 142);
        }
        .active{
            border-bottom: 1px solid rgb(107, 69, 142);; 
        }
    </style>
    <script>
        Vue.component("boton-contador", {
            data() {
                return {
                    contador: 0
                }
            },
            template: "<button>Contador</button>"
        })
        var app = new Vue({
            el: "#app",
            components: {
                
            },
            data: {
                sedes: [
                    { id: 1, provincia: 'Córdoba', localidad: 'Córdoba', casas: 2 },
                    { id: 2, provincia: 'La Rioja', localidad: 'La Rioja', casas: 1 },
                    { id: 3, provincia: 'Santiago del estero', localidad: 'Santiago del estero', casas: 3 },
                    { id: 4, provincia: 'Catamarca', localidad: 'Catamarca  ', casas: 1 }
                ]
            },
            mounted: function() {
               this.consultar()
            },
            methods:{
                consultar() {
                    axios.get("http://localhost/proyectos/pedidos2/conexion/api.php?accion=mostrar")
                    .then(function(response){
                        console.log(response.data);
                    })
                }
            }
        })
    </script>
    <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>