<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">            
            <h1>
              <i class="far fa-bookmark"></i>
              MÓDULOS REGISTRADOS
              <!-- <small>Personal</small> -->
            </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="/">Inicio</a></li> -->
              
              <!-- <li class="breadcrumb-item active">Datos del Personal</li> -->
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-8">
                    <h3 class="card-title">
                      <!-- <i class="fas fa-search"></i> -->
                      <!-- Buscar Personal -->
                    </h3>  
                  </div>                  
                  <div class="col-sm-4" v-if="$auth.can('insert-modulo')">
                    <button class="btn btn-primary btn-sm float-right" type="submit" @click="Registro()">
                      <i class="fas fa-user-plus"></i>&nbsp; REGISTRAR NUEVO
                    </button>
                  </div>
                </div>  
              </div>
              <div class="card-body">
                <div class="row d-flex justify-content-center"> 
                    <div class="col-md-4">
                        <label for="">BUSCAR:</label>
                        <input type="text" style="text-transform:uppercase;" class="form-control" @keyup="BuscarModulo(1)" v-model="buscar">
                    </div>
                </div>
                <br>

                  <div class="table-wrapper-scroll-y my-custom-scrollbar" id="myTable" style="font-size: 12pt;" >

                    <!-- <template v-if="datfis2==1"> -->
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">ESPECIALIDAD</th>
                                    <th class="text-center">GESTIÓN</th>                 
                                    <th class="text-center">PROMOCIÓN</th>
                                    <th class="text-center">MÓDULO</th>
                                    <th class="text-center">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(modulo,index) in arrayModulo"> 
                                    <!-- <td v-text="personal.grado+' '+personal.complemento"></td> -->
                                    <td style="text-align:center; font-weight:bold;">{{ index + 1 }}</td>
                                    <td>{{ modulo.diplomado }}</td>
                                    <td style="text-align:center">{{ modulo.gestion }}</td>
                                    <td style="text-align:center">{{ modulo.promo }}</td>
                                    <td style="text-align:center">{{ modulo.modulo || 'SIN MODULOS' }}</td>
                                    <td style="text-align:center">
                                      <div class="d-flex justify-content-center align-items-center">
                                        <template v-if="modulo.estado == 1">
                                            <div v-if="$auth.can('edit-espe')" class="mr-1">
                                                <button type="button" @click="Mostrar(modulo.id)" class="btn btn-success btn-sm">
                                                    <i class="fa fa-retweet" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <template v-if="modulo.estado == 1">
                                            <button type="button" class="btn btn-danger btn-sm" @click="CambiarEstado(modulo.id, modulo.estado)" :disabled="procesando">
                                                <i class="fas fa-lock-open"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-success btn-sm" @click="CambiarEstado(modulo.id, modulo.estado)" :disabled="procesando">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </template>
                                      </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <!-- </template> -->
                    <br>
                  </div>
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
              <!-- /.card -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- ./row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</template>

<script>
import { required, between, minLength, maxLength, alpha, numeric, email} from "vuelidate/lib/validators";
export default {
  data() {
    return {
      
    //   especialidad : '',
    //   abreviatura : '',
    //   descripcion : '',
    //   idE : '',
    //   especialidadE : '',
    //   abreviaturaE : '',
    //   descripcionE : '',

    //   procesando : false,
    //   modal : '',
      arrayModulo : [],
    //   arrayMostrarEspecialidad : [],
      // criterio: "p.per_cm",
      buscar :"",
      setTimeoutBuscador : '',
      pagination : {
            'total'         :   0,
            'current_page'  :   0,
            'per_page'      :   0,
            'last_page'     :   0,
            'from'          :   0,
            'to'            :   0,
        },
      offset : 3,
    }
  },

  validations: {
    // especialidad : { required },
    // abreviatura : { required },
    // especialidadE : { required },
    // abreviaturaE : { required },

    // validationsGroupReg : [
    //   'especialidad',
    //   'abreviatura'
    // ],

    // validationsGroupMod : [
    //   'especialidadE',
    //   'abreviaturaE'
    // ]    
  },

 computed: {
      isActived   : function(){
          return this.pagination.current_page;
      },
      //creando funcion para calcular los elementos de la paginacion
      pagesNumber : function(){
          //si la pagina llega hasta la ultima pagina esta me va retornar un arrary vacio
          if(!this.pagination.to){
              return [];
          }

          var from =  this.pagination.current_page - this.offset;
          if(from < 1){
              from = 1;
          }

          var to = from + (this.offset * 2);
          if(to >= this.pagination.last_page){
              to = this.pagination.last_page;
          }

          var pagesArray = [];
          while(from <= to){
              pagesArray.push(from);
              from++;
          }
          return pagesArray;
      },
  },

  mounted() {
    this.ListarModulo(1);
  },
  
  methods: {

    Registro(){
        this.$router.push({
            name: "RegistroModulos",  
        });
    },
    
    // Mostrar(especialidadid){
    //     this.$v.validationsGroupMod.$reset();
    //     let me = this;
    //     me.modal = 1;
    //     axios
    //         .post("/mostrarEspecialidad", {
    //             especialidadid: especialidadid
    //         })
    //         .then((response) => {
    //             me.arrayMostrarEspecialidad = response.data.especialidad;
    //             me.idE = me.arrayMostrarEspecialidad.id;
    //             me.especialidadE = me.arrayMostrarEspecialidad.diplomado;
    //             me.abreviaturaE = me.arrayMostrarEspecialidad.abreviatura;
    //             me.descripcionE = me.arrayMostrarEspecialidad.descripcion;
    //         })
    //         .catch(function (error) {
    //             // handle error
    //             console.log(error);
    //         })
    //     $('#ModalEspecialidad').modal('show');
    //     $(".modal-header").css("background-color", "#007bff");
    //     $(".modal-header").css("color", "white" );
    // },

    cambiarPagina(page,buscar,criterio){
        let me = this;
        //actualizando la pagina actual
        me.pagination.current_page = page;
        me.ListarModulo(page,buscar,criterio);
    },
    
    ListarModulo(page){
        let me = this;
        axios
        .post("/listarModulo", {
            page: page,
            buscar: me.buscar.toUpperCase(),
        })
        .then(function (response) {
            me.arrayModulo = response.data.diplomado.data;
            me.pagination =response.data.pagination
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
    },

    // Cerrar(){
    //     this.especialidad = '';
    //     this.abreviatura = '';
    //     this.descripcion = '';

    //     this.idE = '';
    //     this.especialidadE = '';
    //     this.abreviaturaE = '';
    //     this.descripcionE = '';
    // },

    // Guardar() {
    //     if (this.procesando) {
    //         return;
    //     }
    //     if (!this.$v.validationsGroupReg.$invalid) {
    //         swal.fire({
    //             title: '¿Desea registrar esta especialidad?',
    //             icon: 'question',
    //             showCancelButton: true,
    //             confirmButtonColor: 'info',
    //             cancelButtonColor: '#868077',
    //             confirmButtonText: 'Confirmar',
    //             cancelButtonText: 'Cancelar',
    //             buttonsStyling: true,
    //             reverseButtons: true
    //         }).then((result) => {
    //             if (result.value) {
    //                 this.procesando = true;
    //                 axios.post('/registrarEspecialidad', {
    //                     especialidad: this.especialidad.toUpperCase(),
    //                     abreviatura: this.abreviatura.toUpperCase(),
    //                     descripcion: this.descripcion
    //                         ? this.descripcion.toUpperCase()
    //                         : 'NINGUNA'
    //                 })
    //                 .then((response) => {
    //                     this.procesando = false;
    //                     if (response.data.success) {
    //                         Swal.fire({
    //                             icon: 'success',
    //                             title: 'CORRECTO',
    //                             text: response.data.mensaje
    //                         });
    //                         $('#ModalEspecialidad').modal('hide');
    //                         this.Cerrar();
    //                         this.ListarEspecialidad(1);
    //                     } else {
    //                         Swal.fire({
    //                             icon: 'warning',
    //                             title: 'ADVERTENCIA',
    //                             text: response.data.mensaje
    //                         });
    //                     }
    //                 })
    //                 .catch((error) => {
    //                     this.procesando = false;
    //                     console.log(error);
    //                     Swal.fire({
    //                         icon: 'error',
    //                         title: 'ERROR',
    //                         text: 'Ocurrió un error inesperado'
    //                     });
    //                 });
    //             } else {
    //                 swal.fire(
    //                     'Información',
    //                     'Solicitud cancelada.',
    //                     'info'
    //                 );
    //                 $('#ModalEspecialidad').modal('hide');
    //                 this.Cerrar();
    //             }
    //         });
    //     } else {
    //         this.$v.validationsGroupReg.$touch();
    //         Swal.fire({
    //             icon: 'warning',
    //             title: 'Ingrese todos los datos requeridos',
    //             showConfirmButton: false,
    //             timer: 2000
    //         });
    //     }
    // },

    // Editar() {
    //     if (this.procesando) {
    //             return;
    //         }
    //         if(!this.$v.validationsGroupMod.$invalid){
    //             swal.fire({
    //                 title: '¿Desea editar esta especialidad?', // TITULO 
    //                 icon: 'question', //ICONO (success, warnning, error, info, question)
    //                 showCancelButton: true, //HABILITACION DEL BOTON CANCELAR
    //                 confirmButtonColor: 'info', // COLOR DEL BOTON PARA CONFIRMAR
    //                 cancelButtonColor: '#868077', // CLOR DEL BOTON CANCELAR
    //                 confirmButtonText: 'Confirmar', //TITULO DEL BOTON CONFIRMAR
    //                 cancelButtonText: 'Cancelar', //TIUTLO DEL BOTON CANCELAR
    //                 buttonsStyling: true,
    //                 reverseButtons: true
    //                 }).then((result) => {
    //                 if (result.value) {
    //                     let me = this;
    //                     this.procesando = true;
    //                     axios.post('/editarEspecialidad', {
    //                         id: this.idE,
    //                         especialidad: this.especialidadE.toUpperCase(),
    //                         abreviatura: this.abreviaturaE
    //                             ? this.abreviaturaE.toUpperCase()
    //                             : '',
    //                         descripcion: this.descripcionE
    //                             ? this.descripcionE.toUpperCase()
    //                             : 'NINGUNA'
    //                     })
    //                     .then((response) => {
    //                         if(response.data.success){
    //                             Swal.fire({
    //                                 icon: 'success',
    //                                 title: 'CORRECTO',
    //                                 text: response.data.mensaje
    //                             });
    //                             $('#ModalEspecialidad').modal('hide');
    //                             this.Cerrar();
    //                             this.ListarEspecialidad(1);
    //                         }else{
    //                             Swal.fire({
    //                                 icon: 'warning',
    //                                 title: 'ADVERTENCIA',
    //                                 text: response.data.mensaje
    //                             });
    //                         }
    //                     })
    //                     .catch((error) => {
    //                         console.log(error);
    //                         Swal.fire({
    //                             icon: 'error',
    //                             title: 'ERROR',
    //                             text: 'Ocurrió un error inesperado'
    //                         });
    //                     })
    //                     .finally(() => {
    //                         this.procesando = false;
    //                     });
    //                 }else{
    //                     let me = this;
    //                     swal.fire(
    //                         "Informacion", //TITULO
    //                         "Solicitud cancelada.", //TEXTO DE MENSAJE
    //                         "info" // TIPO DE MODAL (success, warnning, error, info)
    //                     );
    //                     $('#ModalEspecialidad').modal('hide');
    //                     me.Cerrar();
    //                 }
    //             })
    //         }else{
    //             this.$v.validationsGroupMod.$touch();
    //             Swal.fire({
    //                 icon: 'warning',
    //                 title: 'Ingrese todos los datos requeridos',
    //                 showConfirmButton: false,
    //                 timer: 2000
    //             }) 
    //         }
    // },

    CambiarEstado(especialidadid, estado){
        if (this.procesando) {
                    return;
                }
                if (estado == 1) {
                    var titulo = '¿Desea deshabilitar esta especialidad?';
                } else {
                    var titulo = '¿Desea habilitar esta especialidad?';
                }
                swal.fire({
                    title: titulo, // TITULO 
                    icon: 'question', //ICONO (success, warnning, error, info, question)
                    showCancelButton: true, //HABILITACION DEL BOTON CANCELAR
                    confirmButtonColor: 'info', // COLOR DEL BOTON PARA CONFIRMAR
                    cancelButtonColor: '#868077', // CLOR DEL BOTON CANCELAR
                    confirmButtonText: 'Confirmar', //TITULO DEL BOTON CONFIRMAR
                    cancelButtonText: 'Cancelar', //TIUTLO DEL BOTON CANCELAR
                    buttonsStyling: true,
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value) {
                        let me = this;
                        this.procesando = true;
                        axios.post('/cambiarEstadoEspecialidad', {
                            id: especialidadid,
                            estado: estado
                        })
                        .then((response) => {
                            if(response.data.success){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'CORRECTO',
                                    text: response.data.mensaje
                                });
                                this.ListarEspecialidad(1);
                            }else{
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'ADVERTENCIA',
                                    text: response.data.mensaje
                                });
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'ERROR',
                                text: 'Ocurrió un error inesperado'
                            });
                        })
                        .finally(() => {
                            this.procesando = false;
                        });
                    }else{
                        let me = this;
                        swal.fire(
                            "Informacion", //TITULO
                            "Solicitud cancelada.", //TEXTO DE MENSAJE
                            "info" // TIPO DE MODAL (success, warnning, error, info)
                        );
                    }
                })
    },

    BuscarModulo(){ //DGAE
        clearTimeout(this.setTimeoutBuscador);
        this.setTimeoutBuscador = setTimeout(() => {
            this.ListarModulo(1)
        }, 360)
    },
  },
};

</script>

<style>
</style>