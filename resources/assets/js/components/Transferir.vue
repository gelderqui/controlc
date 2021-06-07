<template>
        <main class="main">
            <br><br>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"> </i> Transferencia
                        <button type="button" @click="abrirModal('transferencia','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Bodega</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayTransferencia.length">
                                <tr v-for="transferencia in arrayTransferencia" :key="transferencia.id">
                                    <td v-text="transferencia.bodega"></td>
                                    <td v-text="transferencia.cantidad"></td>
                                    <td>
                                        <div v-if="transferencia.condicion==0">
                                            <span class="badge badge-warning">Espera</span>
                                        </div>
                                    </td>
                                </tr>                                
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3">
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
                                    <select class="form-control col-md-3" v-model="idbodegaentrada">
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
                                <div v-show="errorTransferencia" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjTransferencia" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="registrarTransferencia()">Guardar</button>
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
                modal : 0,
                tipoAccion : 0,
                tituloModal : '',

                idusuario : 0,
                idbodegasalida : 0,
                idbodegaentrada : 0,
                cantidad: 0,

                iduser: 0,
                arrayTransferencia : [],
                arrayBodega : [],

                errorTransferencia : 0,
                errorMostrarMsjTransferencia : []             
            }
        },
 
        methods : {
            listarTransferencia(){
                let me=this;
              //  me.obtenerUsuario();
                var url=this.ruta + '/transferir';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTransferencia = respuesta.transferencias;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            obtenerUsuario(){
                let me=this;
                var url=this.ruta + '/obtenerlogin';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.idusuario = respuesta.idusuario;
                    me.idbodegasalida = respuesta.idbodega;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            seleccionarBodega(){
                let me=this;
                var url=this.ruta + '/bodega/select';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBodega = respuesta.bodegas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarTransferencia(){
                if (this.validarTransferencia()){
                    return;
                }
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
                    axios.post(this.ruta + '/transferir/registrar',{
                    'idusuario': this.idusuario,
                    'idbodegasalida': this.idbodegasalida,
                    'idbodegaentrada': this.idbodegaentrada,
                    'cantidad': this.cantidad
                    }).then(function (response) {
                        me.modal=0;
                        me.idbodegaentrada=0;
                        me.cantidad=0;
                        me.listarTransferencia();
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
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "transferencia":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Transferencia';
                                this.idbodegaentrada = 0;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                         /*       this.modal=1;
                                this.tituloModal='Actualizar categoría';
                                this.tipoAccion=2;
                                this.categoria_id=data['id'];
                                this.nombre = data['nombre'];
                                this.descripcion= data['descripcion'];
                                break;*/
                            }
                        }
                    }
                }
                this.seleccionarBodega();
            },
            validarTransferencia(){
                this.errorTransferencia=0;
                this.errorMostrarMsjTransferencia =[];

                if (!this.idbodegaentrada) this.errorMostrarMsjTransferencia.push("LA bodega salida no debe estar vacio.");
                if (!this.cantidad) this.errorMostrarMsjTransferencia.push("La cantidad no debe estar vacio.");
                if (this.errorMostrarMsjTransferencia.length) this.errorTransferencia = 1;

                return this.errorTransferencia;
            },
            cerrarModal(){
                this.modal=0;
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
