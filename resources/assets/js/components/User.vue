<template>
            <main class="main">
<br><br>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="modalAgregarEditar('user','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <template v-if="listado">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" criterio="" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="usuario">Usuario</option>
                                      <option value="idrol">Rol</option>
                                    </select>
                                    <select v-if="criterio==='idrol'" class="form-control col-md-3" v-model="buscar" @click="listarUser(1,buscar,criterio)">
                                        <option value="1">Administrador</option>
                                        <option value="2">Vendedor</option>
                                        <option value="3">Almacenero</option>
                                    </select>
                                    <input v-else type="text" v-model="buscar" @keyup.enter="listarUser(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarUser(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in arrayUser" :key="user.id">
                                    <td>
                                        <button type="button" @click="modalAgregarEditar('user','actualizar',user)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>&nbsp;
                                        <template v-if="user.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarUser(user.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarUser(user.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="user.nombre"></td>
                                    <td v-text="user.usuario"></td>
                                    <td v-text="user.rol"></td>
                                    <td>
                                        <div v-if="user.condicion">
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
                    </template>
                    <template v-else>
                    <!--Agregar-->
                    <div v-if="listado2" class="card-body">
                        <!--Datos de user-->
                        <div class="form-group row border">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" v-model="nombre" placeholder="Ingrese nombre">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Rol</label>
                                    <select class="form-control" v-model="idrol">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>                                        
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Usuario</label>
                                    <input type="text" class="form-control" v-model="usuario" placeholder="Ingrese usuario">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label>Contraseña</label>
                                    <input type="password" class="form-control" v-model="password" placeholder="Contraseña">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Fin datos de user-->
                        <!--Inicio agregar bodega-->
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Bodega <span style="color:red;" v-show="idbodega==0">Seleccione una bodega</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="buscar2" @keyup.enter="buscarBodega()"  placeholder="Ingrese alias de bodega">
                                        <button @click="abrirModalBodega()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="bodega">                
                                        <button @click="agregarDetalleBodega()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Fin agregar bodega-->
                        <!--Lista de bodegas agregados-->
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Tipo</th>
                                            <th>Nombre</th>
                                            <th>Alias</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleBodega.length">
                                        <tr v-for="(detalleb,index) in arrayDetalleBodega" :key="detalleb.id">
                                            <td>
                                                <button @click="eliminarDetalleBodega(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalleb.idbodega"></td>
                                            <td v-text="detalleb.tipo"></td>
                                            <td v-text="detalleb.bodega"></td>
                                            <td v-text="detalleb.alias"></td>
                                            <td>
                                                <div v-if="detalleb.condicion">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay bodegas agregado
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Fin de lista de bodegas bodega-->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarIngreso2()">Guardar</button>
                            </div>
                        </div>
                    </div>
                    <!--Editar-->
                    <div v-if="listado2==0" class="card-body">
                        <!--Datos de user-->
                        <div class="form-group row border">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" v-model="nombre" placeholder="Ingrese nombre">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >Rol</label>
                                    <select class="form-control" v-model="idrol">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>                                        
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Usuario</label>
                                    <input type="text" class="form-control" v-model="usuario" placeholder="Ingrese usuario">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label>Contraseña</label>
                                    <input type="password" class="form-control" v-model="password" placeholder="Contraseña">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorIngreso" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjIngreso" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Fin datos de user-->
                        <!--Inicio agregar bodega-->
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Bodega <span style="color:red;" v-show="idbodega==0">Seleccione una bodega</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="buscar2" @keyup.enter="buscarBodega()"  placeholder="Ingrese alias de bodega">
                                        <button @click="abrirModalBodega()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="bodega">                
                                        <button @click="agregarDetalleBodega()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Fin agregar bodega-->
                        <!--Lista de bodegas agregados-->
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Tipo</th>
                                            <th>Nombre</th>
                                            <th>Alias</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleBodega.length">
                                        <tr v-for="(detalleb,index) in arrayDetalleBodega" :key="detalleb.id">
                                            <td>
                                                <button @click="eliminarDetalleBodega(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalleb.idbodega"></td>
                                            <td v-text="detalleb.tipo"></td>
                                            <td v-text="detalleb.bodega"></td>
                                            <td v-text="detalleb.alias"></td>
                                            <td>
                                                <div v-if="detalleb.condicion">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay bodegas agregado
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Fin de lista de bodegas bodega-->
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-secondary" @click="ocultarDetalle()">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="actualizarUsuario()" >Actualizar</button>
                            </div>
                        </div>
                    </div>
                    <!--Fin Editar-->
                    </template>
                    <!--Fin detalle-->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>


            <!--Inicio del modal Bodega-->
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
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio3">
                                        <option value="nombre">Nombre</option>
                                        <option value="alias">Alias</option>
                                        </select>
                                        <input type="text" v-model="buscar3" @keyup.enter="listarBodega(buscar3,criterio3)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarBodega(buscar3,criterio3)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div> 
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Tipo</th>
                                            <th>Nombre</th>
                                            <th>Alias</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>                        
                                    <tbody>
                                        <tr v-for="bodega in arrayBodega" :key="bodega.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleBodegaModal(bodega)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="bodega.id"></td>
                                            <td v-text="bodega.tipo"></td>
                                            <td v-text="bodega.nombre"></td>
                                            <td v-text="bodega.alias"></td>
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
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal Bodega-->

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
                criterio : '',
                buscar : '',
                listado: 1,
                listado2: 1,
                modal : 0,
                tipoAccion : 0,
                tituloModal : '',

                id:0,
                nombre : '',
                usuario : '',
                password : '',
                idrol: 0,

                arrayUser : [],
                user_id: 0 , 
                arrayRol : [],

                criterio2 : 'alias',
                buscar2 : '',
                criterio3 : 'nombre',
                buscar3 : '',
                arrayBodega: [],
                arrayDetalleBodega: [],
                bodega : '',
                tipo : '',
                idbodega : 0,
                alias : '',
                direccion: '',
                cantidad : 0,

                errorUser : 0,
                errorMostrarMsjUser : [],
                errorMostrarMsjIngreso:[],
                errorIngreso: 0        
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
            listarUser (page,buscar,criterio){
                let me=this;
                var url=this.ruta + '/user?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayUser = respuesta.usuarios.data;
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
                me.listarUser(page,buscar,criterio);
            },
            buscarRol(){
                let me=this;
                var url=this.ruta + '/rol';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRol = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            buscarBodega(){
                let me =this;
                var url=this.ruta + '/bodega/buscar?buscar='+ me.buscar2 + '&criterio='+ me.criterio2;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayBodega=respuesta.bodegas;
                    if(me.arrayBodega.length>0){
                        me.bodega=me.arrayBodega[0]['nombre'];
                        me.idbodega=me.arrayBodega[0]['id'];
                        me.tipo=me.arrayBodega[0]['tipo'];
                        me.alias=me.arrayBodega[0]['alias'];
                        me.direccion=me.arrayBodega[0]['direccion'];
                        me.cantidad=me.arrayBodega[0]['cantidad'];
                        me.condicion=me.arrayBodega[0]['condicion'];
                    }
                    else{
                        me.bodega='no existe bodega';
                        me.idbodega=0;
                        me.tipo='';
                        me.alias='';
                        me.direccion='';
                        me.cantidad=0;
                    }
                })
            },
            listarBodega (buscar3,criterio3){
                let me=this;
                var url=this.ruta + '/bodega/listar?buscar='+ me.buscar3 + '&criterio='+ me.criterio3;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayBodega = respuesta.bodegas.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            abrirModalBodega(){
                this.arrayBodega=[];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varias bodegas';
            },
            encuentraDetalleB(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalleBodega.length;i++){
                    if(this.arrayDetalleBodega[i].idbodega==id){
                        sw=true;
                    }
                }
                return sw;
            },
            agregarDetalleBodega(){
                let me = this;

                if(me.idbodega==0){
                //if(me.idproducto==0){
                }
                else{
                    if(me.encuentraDetalleB(me.idbodega)){
                        swal({
                            type:'error',
                            title:'error...',
                            text: 'Bodega ya ingresado',
                        })
                    }
                    else{
                        me.arrayDetalleBodega.push({
                        idbodega: me.idbodega,
                        bodega: me.bodega,
                        tipo: me.tipo,
                        direcciion: me.direccion,
                        alias: me.alias,
                        condicion:me.condicion
                        });
                        me.alias="",
                        me.idbodega=0,
                        me.bodega="",
                        me.direccion="",
                        me.tipo="",
                        me.condicion=0
                    }                    

                }
            },
            agregarDetalleBodegaModal(data=[]){
                let me = this;
                if(me.encuentraDetalleB(data['id'])){
                        swal({
                            type:'error',
                            title:'error...',
                            text: 'Bodega ya ingresado',
                        })
                    }
                    else{
                        me.arrayDetalleBodega.push({
                        idbodega: data['id'],
                        bodega: data['nombre'],
                        alias: data['alias'],
                        tipo: data['tipo'],
                        direccion: data['direccion'],
                        condicion: data['condicion']
                        });
                    } 

            },
            eliminarDetalleBodega(index){
                let me= this;
                me.arrayDetalleBodega.splice(index,1);
            },
            ocultarDetalle(){
                this.listado=1;
                this.arrayDetalleBodega=[];
                this.bodega='';
                this.buscar2='';
            },
            validarIngreso(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso =[];
                
                if (!this.nombre) this.errorMostrarMsjIngreso.push("El nombre no puede estar vacío.");
                if (!this.usuario) this.errorMostrarMsjIngreso.push("El usuario no puede estar vacío.");
                if (!this.password) this.errorMostrarMsjIngreso.push("La contraseña no puede estar vacío.");
                if (this.idrol==0) this.errorMostrarMsjIngreso.push("Seleccione Rol.");
                if (this.arrayDetalleBodega.length<=0) this.errorMostrarMsjIngreso.push("Ingrese bodega")
                if (this.errorMostrarMsjIngreso.length) this.errorIngreso = 1;

                return this.errorIngreso;
            },
            registrarIngreso2(){
                    if (this.validarIngreso()){
                        return;
                    }

               swal({
                title: '¿Esta seguro de crear el usuario?',
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
                    axios.post(this.ruta + '/user/registrar',{
                    'nombre': this.nombre,
                    'usuario': this.usuario,
                    'password': this.password,
                    'idrol': this.idrol,
                    'data':this.arrayDetalleBodega
                    }).then(function (response) {
                        me.listado=1;
                        me.listarUser(1,'','nombre');
                        me.nombre='';
                        me.descripcion='';
                        me.buscar2='';
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
            desactivarUser(id){
               swal({
                title: '¿Esta seguro de descativar este usuario?',
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
                    axios.put(this.ruta + '/user/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUser(1,'','nombre');
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
            activarUser(id){
               swal({
                title: '¿Esta seguro de activar este usuario?',
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

                    axios.put(this.ruta + '/user/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUser(1,'','nombre');
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
            cerrarModal(){
                this.modal=0;
            },
            buscarBodegaData(){
                let me =this;
                var url=this.ruta + '/user/obtenerdetalle?id=' + me.id;
                axios.get(url).then(function(response){
                    var respuesta=response.data;
                    me.arrayDetalleBodega=respuesta.detalles;
                })
                
            },
            actualizarUsuario(){
               if (this.validarIngreso()){
                    return;
                }
                swal({
                title: '¿Esta seguro de aztualizar el usuario?',
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
                    axios.put(this.ruta + '/user/actualizar',{
                        'nombre': this.nombre,
                        'usuario': this.usuario,
                        'password': this.password,
                        'idrol': this.idrol,
                        'data':this.arrayDetalleBodega,
                        'id': this.id
                    }).then(function (response) {
                            me.listado=1;
                            me.listarUser(1,'','nombre');
                            me.nombre='';
                            me.descripcion='';
                            me.buscar2='';
                            swal(
                            '¡Actualizado!',
                            'El registro ha sido actualizado con éxito.',
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
            modalAgregarEditar(modelo, accion, data = []){
                switch(modelo){
                    case "user":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.listado=0;
                                this.listado2=1;
                                this.tituloModal = 'Registrar Usuario';
                                this.nombre= '';
                                this.usuario= '';
                                this.password = '';
                                this.idrol=0;
                                this.arrayBodega=[];
                                this.arrayDetalleBodega=[];
                                break;
                            }
                            case 'actualizar':
                            {
                                this.listado=0;
                                this.listado2=0;
                                this.tituloModal='Actualizar Usuario';
                                this.id = data['id'];
                                this.nombre = data ['nombre'];
                                this.usuario = data['usuario'];
                                this.password= data['password'];
                                this.idrol = data['idrol'];
                                this.buscarBodegaData();
                                break;
                            }
                        }
                    }
                }
                this.buscarRol();
                
            }

        },
        mounted() {
            this.listarUser(1,this.buscar,this.criterio);
         
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
