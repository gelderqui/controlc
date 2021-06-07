<template>
            <main class="main">
<br><br>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"> </i> Recibido
                        <button v-if="bandera" type="button" @click="modalAgregar('ingreso','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Bodega</th>
                                    <th>Usuario</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayIngreso.length">
                                <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                    <td>
                                        <template v-if="ingreso.condicion==0">
                                            <button type="button" class="btn btn-danger btn-sm" @click="rechazarIngreso(ingreso.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-if="ingreso.condicion==0">
                                            <button type="button" class="btn btn-info btn-sm" @click="aceptarIngreso(ingreso.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="ingreso.bodega"></td>
                                    <td v-text="ingreso.usuario"></td>
                                    <td v-text="ingreso.cantidad"></td>
                                   
                                    <td>
                                        <div v-if="ingreso.condicion==0">
                                            <span class="badge badge-warning">Espera</span>
                                        </div>
                                    </td>
                                </tr>                                
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="5">
                                        Sin transacciones
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Bodega Entrada</label>
                                    <div class="col-md-9">
                                    <select class="form-control col-md-3" v-model="idbodegasalida">
                                      <option value="0">Seleccione Bodega</option>
                                      <option v-for="bodega in arrayBodega" :key="bodega.id" :value="bodega.id" v-text="bodega.alias"></option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cantidad</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="cantidad" class="form-control" placeholder="Nombre de sucursal">
                                        
                                    </div>
                                </div>


                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="registrarIngreso()">Guardar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        props: ['ruta'],
        data (){
            return {  
                idbodega : 0,
                idusuario:0,
                bandera : 0,
                arrayIngreso : [],
                arrayBodega:[],
                idbodegasalida:0,
                cantidad:0,
                modal:0,
                tituloModal:''
            }
        },
 
        methods : {
            listarIngreso(){
                let me=this;
                var url=this.ruta + '/transferir/listar';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayIngreso = respuesta.transferencias;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            rechazarIngreso(id){
               swal({
                title: '¿Esta seguro de rechazar?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.put(this.ruta + '/transferir/rechazar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIngreso();
                        swal(
                        '¡Rechazado!',
                        'Se ha rechazado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });  
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) { 
                }
                }) 
            },
            aceptarIngreso(id){
               swal({
                title: '¿Esta seguro de aceptar?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put(this.ruta + '/transferir/aceptar',{
                        'id': id
                    }).then(function (response) {
                        me.listarIngreso();
                        swal(
                        '¡Aceptado!',
                        'Se ha aceptado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            obtenerUsuario(){
                let me=this;
                var url=this.ruta + '/obtenerlogin';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.idbodega = respuesta.idbodega;
                    me.idusuario= respuesta.idusuario;
                    me.buscarFranquicia();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            buscarFranquicia(){
                let me=this;
                var url=this.ruta + '/bodega/buscaringreso?id=' + me.idbodega+'&idusuario='+me.idusuario;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.bandera = respuesta.bandera;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarFranquicia(){
                let me=this;
                var url=this.ruta + '/bodega/listarfranquicia';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBodega = respuesta.bodegas;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });

            },
            registrarIngreso(){
              /*  if (this.validarTransferencia()){
                    return;
                }*/
               swal({
                title: '¿Esta seguro de hacer la transferencia?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;
                    axios.post(this.ruta + '/transferir/registraringreso',{
                    'idusuario': this.idusuario,
                    'idbodegasalida': this.idbodegasalida,
                    'idbodegaentrada': this.idbodega,
                    'cantidad': this.cantidad
                    }).then(function (response) {
                        me.modal=0;
                        me.idbodegaentrada=0;
                        me.cantidad=0;
                        me.listarIngreso();
                        swal(
                        '¡Creado!',
                        'El registro ha sido creado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });  
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) { 
                }
                }) 
            },
            cerrarModal()
            {
                this.modal=0;
            },
            modalAgregar(modelo, accion){
                switch(modelo){
                    case "ingreso":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.tituloModal = 'Ingreso de franquicia';
                                this.modal = 1;
                                this.cantidad= 0;
                                this.idbodegaentrada=0;
                                break;
                            }
                        }
                    }
                }
                
            }

        },
        mounted() {
            this.listarIngreso();
            this.obtenerUsuario();
            this.listarFranquicia();
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
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
</style>
