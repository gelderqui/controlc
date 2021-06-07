<template>
    <main class="main">
            <br><br>
            <div class="container-fluid">
                
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                 
                    <div class="card-header">
                        <div class="contenedor">
                            <div class="contenido">
                                <img src="img/user.jpg" width="50" height="50" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <u><i>Usuario:</i></u>
                                <strong><i v-text="usuario"></i></strong>
                            </div>
                            <div class="contenido">
                                <img src="img/bodega.png" width="50" height="50" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <u><i>Bodega:</i></u>
                                <strong><i v-text="alias"></i></strong> 
                            </div>
                            <div class="contenido">
                                <img src="img/canasta.jpg" width="50" height="50" class="img-avatar" alt="admin@bootstrapmaster.com">
                                <u><i>Canastas:</i></u>
                                <strong><i v-text="cantidad"></i></strong>
                            </div>
                        </div>
                    </div>        
                  <div class="card-body">
                        <label for="">Historial del dia</label>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th colspan="2">Envios</th>
                                    <th colspan="2">Recibido</th>
                                    <th colspan="3"></th>
                                </tr>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Bodega</th>
                                    <th>Usuario</th>
                                    <th>Bodega</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <!--<tbody v-if="arrayIngreso.length || arrayTransferencia.length">-->
                            <tbody v-if="arrayTransferencia.length">
                                <tr v-for="transferencia in arrayTransferencia" :key="transferencia.id">
                                    <td v-text="transferencia.usuario"></td>
                                    <td v-text="transferencia.bodegasalida"></td>
                                    <td v-text="transferencia.usuariorecibido"></td>
                                    <td v-text="transferencia.bodegaentrada"></td>
                                   <td v-text="transferencia.cantidad"></td>
                                   <td v-text="transferencia.updated_at"></td>
                                    <td>
                                        <div v-if="transferencia.condicion==1">
                                            <span class="badge badge-success">Aceptado</span>
                                        </div>
                                        <div v-if="transferencia.condicion==2">
                                            <span class="badge badge-danger">Rechazado</span>
                                        </div>
                                    </td>
                                </tr> 
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="6">
                                        Sin transacciones
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<script>
    export default {
        props: ['ruta'],
        data (){
            return {  
                idusuario : 0,
                usuario : '',
                idbodega:0,
                bodega:'',
                alias:'',
                cantidad:0,
                arrayTransferencia : [],
                arrayIngreso : [],
            }
        },
        methods : {

            obtenerUsuario(){
                let me=this;
                var url=this.ruta + '/obtenerlogin';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.idusuario = respuesta.idusuario;
                    me.usuario=respuesta.usuario;
                    me.idbodega=respuesta.idbodega;
                    me.bodega=respuesta.bodega;
                    me.cantidad=respuesta.cantidad;
                    me.alias=respuesta.alias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarTransferencia(){
                let me=this;
              //  me.obtenerUsuario();
                var url=this.ruta + '/transferir/indexhoy';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTransferencia = respuesta.transferencias;
                    me.arrayIngreso = respuesta.ingresos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }

        },
        mounted() {
            this.obtenerUsuario(); 
            this.listarTransferencia();
     
        }
    }
</script>
<style>    
    .modal-content{
        width: 70% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    div{
		border-radius: 10px;
	}
	.contenedor{
		background-color:#CCC;
		width:100%;
		height:100px;
	}
	.contenido{
		height:50px;
		width:15%;
		float:left;
		margin: 5px;
	}
</style>
