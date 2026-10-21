<template>
  <div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">            
            <h1>
              <i class="far fa-bookmark"></i>
              ESPECIALIDADES REGISTRADAS
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
                  <div class="col-sm-4" v-if="$auth.can('view-insert-cursante')">
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
                        <input type="text" style="text-transform:uppercase;" class="form-control" @keyup="BuscarPersona(1)" v-model="buscar">
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
                                    <th class="text-center">DESCRIPCION</th>
                                    <th class="text-center">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(especialidad,index) in arrayEspecialidad"> 
                                    <!-- <td v-text="personal.grado+' '+personal.complemento"></td> -->
                                    <td style="text-align:center; font-weight:bold;">{{ index + 1 }}</td>
                                    <td v-text="especialidad.diplomado"></td>
                                    <td v-text="especialidad.descripcion"></td>
                                    <td style="text-align:center">
                                      <div class="d-flex justify-content-center align-items-center">
                                        <div v-if="$auth.can('view-renew-per')" class="mr-1">
                                          <button type="button" @click="Editar(especialidad.id)" class="btn btn-success btn-sm">
                                              <i class="fa fa-retweet" aria-hidden="true"></i>
                                          </button>
                                        </div>
                                        <div v-if="$auth.can('view-delet-per')" class="mr-1">
                                          <button type="button" @click="Eliminar(especialidad.id)" class="btn btn-danger btn-sm">
                                              <i class="fa fa-trash" aria-hidden="true"></i>
                                          </button>
                                        </div>
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
      // INICIO VARIABLES DGAE

      // FIN VARIABLES DGAE
      
      arrayEspecialidad: [],
      // criterio: "p.per_cm",
      buscar:"",
      setTiemoutBuscador: '',
      pagination: {
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
    this.ListarEspecialidad(1);
  },
  
  methods: {

    Registro(){
      this.$router.push({
          name: "RegistroEspecialidad",  
      });
    },
    
    Editar(especialidadid){
      this.$router.push({
          name: "EditarEspecialidad",
          //ENVIO DE DATOS
          params:{
              especialidadid: especialidadid
          }
          
      });
    },

    cambiarPagina(page,buscar,criterio){
        let me = this;
        //actualizando la pagina actual
        me.pagination.current_page = page;
        me.ListarPersonal(page,buscar,criterio);
    },
    
    ListarEspecialidad(page){
        let me = this;
        axios
        .post("/listarEspecialidad", {
            page: page,
            buscar: me.buscar.toUpperCase(),
        })
        .then(function (response) {
            me.arrayEspecialidad = response.data.diplomado;
            // me.pagination =response.data.pagination
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
    },

    Eliminar(personalid){
      swal.fire({
          title: '¿Desea eliminar este personal?', // TITULO 
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
              axios
              .post("/eliminarPersonal", {
                  persona_id: personalid,
              })
              .then(function (response) {
              console.log(response);
                  swal.fire(
                      "ELIMINADO", //TITULO
                      "Se elimino correctamente al personal.", //TEXTO DE MENSAJE
                      "success" // TIPO DE MODAL (success, warnning, error, info)
                  );
                  me.ListarPersonal(1);
              })
              .catch(function (error) {
                  // handle error
                  console.log(error);
              })
          }else{
              let me = this;
              swal.fire(
                  "Informacion", //TITULO
                  "Solicitud cancelada.", //TEXTO DE MENSAJE
                  "info" // TIPO DE MODAL (success, warnning, error, info)
              );
              me.ListarPersonal(1);
          }
      })
    },

    BuscarPersona(){ //DGAE
        clearTimeout(this.setTiemoutBuscador);
        this.setTiemoutBuscador = setTimeout(() => {
            this.ListarPersonal(1)
        }, 360)
    },
  },
};

</script>

<style>
</style>