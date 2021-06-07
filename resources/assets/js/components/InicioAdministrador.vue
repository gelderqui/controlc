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
                            <div class="contenido">
                            <table class="table-sm  col-md-1" border="1">
                                <thead>
                                    <tr>
                                        <th colspan="2">Canastas</th>
                                    </tr>
                                    <tr>
                                        <th>Inicio</th>
                                        <th>Hoy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td v-text="total"></td>
                                        <td v-text="suma"></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>        
                  <div class="card-body">  
                        <div>
                            <select class="form-control col-md-3" v-model="buscar" @click="buscarBodega()">
                                <option value="0">Seleccione Bodega</option>
                                <option v-for="bodega in arrayBodega" :key="bodega.id" :value="bodega.id" v-text="bodega.alias"></option>
                            </select>
                            <label for="">Historial del</label>
                        
                            <input type="date" v-model="fechainicio">
                            
                            <label for="">al  </label>
                            
                            <input type="date" v-model="fechafin">
                            
                            <button type="submit" @click="listarTransferencia()" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
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
                         <!--   <tbody v-if="arrayTransferencia.length">-->
                                <tbody>
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
                       <!--     <tbody v-else>
                                <tr>
                                    <td colspan="7">
                                        Sin transacciones
                                    </td>
                                </tr>
                            </tbody>-->
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
                cantidad:0,
                alias:'',

                fechainicio:'',
                fechafin:'',
                criterio:'id',
                buscar:0,

                arrayBodega: [],
                arrayBodega2: [],
                arrayTransferencia : [],
                arrayIngreso : [],

                total:0,
                suma:0
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
            obtenertotal(){
                let me=this;
                var url=this.ruta + '/transferir/total';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.total = respuesta.total;
                    me.suma=respuesta.suma;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarBodega (){
                let me=this;
                var url=this.ruta + '/bodega/listartodo';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBodega = respuesta.bodegas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            obtenerFecha(){
                let me=this;
                var url=this.ruta + '/transferir/fechahoy';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.fechainicio=respuesta.fechainicio;
                    me.fechafin=respuesta.fechafin;
                    me.listarTransferencia();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            buscarBodega (){
                let me=this;
                var url=this.ruta + '/bodega/buscar?criterio='+me.criterio+'&buscar='+me.buscar;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBodega2=respuesta.bodegas;
                    if(me.arrayBodega2.length>0){
                        me.idbodega = me.arrayBodega2[0]['id'];
                        me.bodega = me.arrayBodega2[0]['nombre'];
                        me.alias = me.arrayBodega2[0]['alias']
                        me.cantidad = me.arrayBodega2[0]['cantidad'];
                    }
                    else{
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarTransferencia(){
                let me=this;
                var url=this.ruta + '/transferir/indexfecha?fechainicio='+me.fechainicio+'&fechafin='+me.fechafin+'&idbodega='+me.idbodega;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTransferencia = respuesta.transferencias;
                   // me.arrayIngreso = respuesta.ingresos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.listarBodega();
            this.obtenerFecha();
            this.obtenerUsuario(); 
            this.obtenertotal();
            
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
	.contenido2{
		height:50px;
		width:15%;
		float:right;
		margin: 5px;
	}
</style>










