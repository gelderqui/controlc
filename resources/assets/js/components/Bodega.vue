<template>
            <main class="main">
<br><br>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Bodega
                        <button type="button" @click="abrirModal('bodega','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="tipo">Tipo</option>
                                      <option value="nombre">Nombre</option>
                                      <option value="alias">Alias</option>
                                    </select>
                                    <select v-if="criterio==='tipo'" class="form-control col-md-3" v-model="buscar" @click="listarBodega(1,buscar,criterio)">
                                    <option value="Sucursal">Sucursal</option>
                                    <option value="Franquicia">Franquicia</option>
                                    </select>
                                    <input v-else type="text" v-model="buscar" @keyup.enter="listarBodega(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarBodega(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Tipo</th>
                                    <th>Nombre</th>
                                    <th>Alias</th>
                                    <th>Direccion</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="bodega in arrayBodega" :key="bodega.id">
                                    <td>
                                        <button type="button" @click="abrirModal('bodega','actualizar',bodega)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="bodega.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarBodega(bodega.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarBodega(bodega.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="bodega.id"></td>
                                    <td v-text="bodega.tipo"></td>
                                    <td v-text="bodega.nombre"></td>
                                    <td v-text="bodega.alias"></td>
                                    <td v-text="bodega.direccion"></td>
                                    <td v-text="bodega.cantidad"></td>
                                    <td>
                                        <div v-if="bodega.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                        
                                    </td>
                                </tr>                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
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
                                    <label class="col-md-3 form-control-label" for="email-input">Tipo</label>
                                    <div class="col-md-9">
                                    <select class="form-control col-md-3" v-model="tipo">
                                      <option value="sucursal">Sucursal</option>
                                      <option value="franquicia">Franquicia</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de bodega">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Alias</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="alias" class="form-control" placeholder="Ingrese alias">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Ingrese direccion">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Cantidad</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="cantidad" class="form-control" placeholder="Ingrese Cantidad">
                                    </div>
                                </div>
                                <div v-show="errorBodega" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjBodega" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarBodega()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarBodega()">Actualizar</button>
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
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                
                modal : 0,
                tipoAccion : 0,
                tituloModal : '',
                
                tipo : 'bodega',
                nombre : '',
                alias : '',
                direccion : '',
                cantidad: 0,

                arrayBodega : [],
                bodega_id: 0 , 
                errorBodega : 0,
                errorMostrarMsjBodega : []             
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarBodega (page,buscar,criterio){
                let me=this;
                var url=this.ruta + '/bodega?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBodega = respuesta.bodegas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarBodega(page,buscar,criterio);
            },
            registrarBodega(){
                if (this.validarBodega()){
                    return;
                }
                
                let me = this;

                axios.post(this.ruta + '/bodega/registrar',{
                    'tipo' : this.tipo,
                    'nombre': this.nombre,
                    'alias': this.alias,
                    'direccion': this.direccion,
                    'cantidad': this.cantidad
                    }).then(function (response) {
                    me.cerrarModal();
                    me.listarBodega(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarBodega(){
               if (this.validarBodega()){
                    return;
                }
                
                let me = this;

                axios.put(this.ruta + '/bodega/actualizar',{
                    'tipo' : this.tipo,
                    'nombre': this.nombre,
                    'alias': this.alias,
                    'direccion': this.direccion,
                    'cantidad': this.cantidad,
                    'id': this.bodega_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarBodega(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarBodega(id){
               swal({
                title: '¿Esta seguro de desactivar esta bodega?',
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
                    axios.put(this.ruta + '/bodega/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarBodega(1,'','nombre');
                        swal(
                        '¡Desactivado!',
                        'El registro ha sido desactivado con éxito.',
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
            activarBodega(id){
               swal({
                title: '¿Esta seguro de activar esta bodega?',
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

                    axios.put(this.ruta + '/bodega/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarBodega(1,'','nombre');
                        swal(
                        '¡Activado!',
                        'El registro ha sido activado con éxito.',
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
            validarBodega(){
                this.errorBodega=0;
                this.errorMostrarMsjBodega =[];

                if (!this.nombre) this.errorMostrarMsjBodega.push("El nombre de Bodega no debe estar vacio.");
                if (!this.tipo) this.errorMostrarMsjBodega.push("El tipo no debe estar vacio.");
                if (!this.alias) this.errorMostrarMsjBodega.push("El alias no debe estar vacio.");
                if (!this.direccion) this.errorMostrarMsjBodega.push("La direccion no debe estar vacio.");
                if (!this.cantidad) this.errorMostrarMsjBodega.push("La cantidad no debe estar vacio.");
                if (this.errorMostrarMsjBodega.length) this.errorBodega = 1;

                return this.errorSucursal;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
                this.codigo='';
                this.tipo='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "bodega":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tipoAccion = 1;
                                this.tituloModal = 'Registrar Bodega';
                                this.tipo= '';
                                this.nombre= '';
                                this.alias = '';
                                this.direccion = '';
                                this.cantidad = '';
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tipoAccion=2;
                                this.tituloModal='Actualizar Bodega';
                                this.bodega_id=data['id'];
                                this.tipo = data ['tipo'];
                                this.nombre = data['nombre'];
                                this.alias= data['alias'];
                                this.direccion = data['direccion'];
                                this.cantidad=data['cantidad'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarBodega(1,this.buscar,this.criterio);
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
