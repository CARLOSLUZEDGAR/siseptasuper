<template>
    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">            
              <h1>
                <i class="far fa-registered"></i>&nbsp;
                REGISTRAR DIPLOMADO PROMOCIÓN
                <!-- <small>Personal</small> -->
              </h1>
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
                    <div class="col-sm-4">
                      <button class="btn btn-danger btn-sm float-right" type="submit" @click="Atras()">
                        <i class="fas fa-arrow-left"></i>&nbsp; VOLVER
                      </button>
                    </div>
                  </div>  
                </div>
                <div class="card-body">
                  <form>
                        <h3><i class="fas fa-inbox"></i>&nbsp;ASIGNAR GESTIÓN Y PROMOCIÓN</h3>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Diplomado en</label>
                                <select class="form-control" v-model="diplomado" :class="{ 'is-invalid' : $v.diplomado.$error, 'is-valid':!$v.diplomado.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="diplomado in arrayDiplomado" :key="diplomado.id" :value="diplomado.id"  v-text="diplomado.diplomado"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.diplomado.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Gestión</label>
                                <select class="form-control" v-model="gestion" :class="{ 'is-invalid' : $v.gestion.$error, 'is-valid':!$v.gestion.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="a in anios" :key="a" :value="a">{{ a }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.gestion.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Periodo</label>
                                <select class="form-control" v-model="periodo" :class="{ 'is-invalid' : $v.periodo.$error, 'is-valid':!$v.periodo.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="periodo in arrayPeriodo" :key="periodo.id" :value="periodo.id"  v-text="periodo.periodo"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.periodo.required">Este campo es Requerido</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Ultima Promoción</label>
                                <p style="font-weight: bold;" class="form-control-label">LXVI</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Promoción</label>
                                <input type="text" v-model.trim="promocion" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.promocion.$error, 'is-valid':!$v.promocion.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.promocion.required">Este campo es Requerido</span>
                                    <span v-else-if="!$v.promocion.letrasSpanish">Solo letras</span>
                                </div>
                            </div> 
                        </div>
                        <div class="form-group row justify-content-end">
                          <button type="button" class="btn btn-danger" @click="CrearPersonal()"><i class="fas fa-address-card"></i>&nbsp; REGISTRAR</button>
                        </div>
                                 
                  </form>
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
  import { required, between, minLength, maxLength, alpha, numeric, email, helpers, date} from "vuelidate/lib/validators";
  export default {
    data() {
      return {
        // INICIO VARIABLES
        diplomado : '',
        gestion : '',
        periodo : '',
        promocion : '',
       
        v : 0,
        
        // FIN VARIABLES DGAE
        arrayDiplomado : [],
        arrayPeriodo : [],
        anios: [],
        
        listaPersonal: [],
        criterio: "p.per_cm",
        buscar:"",
        setTiemoutBuscador: '',
        isHidden: true,
        datfis2 : 1,
  
          
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
        diplomado : { required },
        gestion: { required },
        promocion : { required, letrasSpanish: value => /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        periodo : { required },

        validationGroupReg: [
        'diplomado',
        'gestion',
        'promocion',
        'periodo'],
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
          imagen(){
              return this.per_foto;
          },
          imagenA(){
              return this.per_fotoA;
          }
      },
    mounted() {
        let actual = new Date().getFullYear();
        for (let i = actual - 5; i <= actual + 5; i++) {
            this.anios.push(i);
        }

        this.ListarDiplomados();
        this.ListarPeriodos();
    },

    methods: {

        Atras(){
            this.$router.push({
                name: "Gestion",  
            });
        },

        ListarDiplomados(){
            let me = this;
            axios
            .post("/listarDiplomado", {
            })
            .then(function (response) {
            me.arrayDiplomado = response.data.diplomado
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarPeriodos(){
            let me = this;
            axios
            .post("/listarPeriodo", {
            })
            .then(function (response) {
            me.arrayPeriodo = response.data.periodo
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

      obtenerImagen(e){
          try {
              var fileReader = new FileReader();
  
              fileReader.onload = (e) => {
                  this.per_foto = e.target.result;
              }
              fileReader.readAsDataURL(e.target.files[0])
              this.v = 1;
          } catch (error) {
              
          }
      },

      NuevoPersonal(){ //DGAE
        this.$v.validationGroupReg.$reset(),
        this.per_foto = '',
        this.per_ci = '',
        this.per_nombre = '',
        this.per_appaterno = '',
        this.per_apmaterno = '',
        this.per_sexo = '',
        this.per_celular = '',
        this.per_email = '',
        this.per_fechnac = '',
        this.per_direccion = '',
        this.v = 0
      },
  
      CrearPersonal(){ //DGAE
        if(!this.$v.validationGroupReg.$invalid){
          swal.fire({
              title: '¿Desea registrar?', // TITULO 
              icon: 'question', //ICONO (success, warnning, error, info, question)
              showCancelButton: true, //HABILITACION DEL BOTON CANCELAR
              confirmButtonColor: 'info', // COLOR DEL BOTON PARA CONFIRMAR
              cancelButtonColor: '#868077', // COLOR DEL BOTON CANCELAR
              confirmButtonText: 'Confirmar', //TITULO DEL BOTON CONFIRMAR
              cancelButtonText: 'Cancelar', //TIUTLO DEL BOTON CANCELAR
              buttonsStyling: true,
              reverseButtons: true
              }).then((result) => {
              if (result.value) {
                  let me = this;
                  axios
                  .post("/crearPersonal", {
                    foto : me.per_foto,
                    ci : me.per_ci,
                    nombre : me.per_nombre,
                    ap_paterno : me.per_appaterno,
                    ap_materno : me.per_apmaterno,
                    sexo : me.per_sexo,
                    celular : me.per_celular,
                    email : me.per_email,
                    fech_nac : me.per_fechnac,
                    direccion : me.per_direccion,
                  })
                  .then(function (response) {
                      
                      console.log(response);
                      swal.fire({
                          title: 'Se realizo el registro correctamente', //TITULO
                          // response.data.mensaje, //TEXTO DE MENSAJE
                          // response.data.tipo, // TIPO DE MODAL (success, warnning, error, info)
                          // response.personal
                      });
                      if (!response.data.code) {
                          // $('#NuevoUsuario').modal('hide');
                          // $('#ModalNewPersonal').modal('hide');
                          // me.nick = '';
                          // me.password = '';
                          me.arrayDatPer = response.data.personal;
                          // me.GenerarCarnet(me.arrayDatPer.id_personal);
                          me.Atras();
                          this.$v.$reset();
                      } 
                  })
                  .catch(function (error) {
                      // handle error
                      console.log(error);
                  })
              }else{
                    swal.fire(
                      "Informacion", //TITULO
                      "Solicitud cancelada.", //TEXTO DE MENSAJE
                      "info" // TIPO DE MODAL (success, warnning, error, info)
                  );
              }
          })
        }else{
            this.$v.validationGroupReg.$touch();
            Swal.fire({
                icon: 'warning',
                title: 'Ingrese todos los datos requeridos',
                showConfirmButton: false,
                timer: 2000
            })
            
        }
      },

      GenerarCarnet(id_personal){ //DGAE
          // this.$v.$reset();
          // if(!this.$v.$invalid){
          window.open('/carnet?id_p='+id_personal);
          // window.open('http://127.0.0.1:8000/carnet?id_p='+id_personal);
          // }else{
          //     this.$v.$touch();
          //     Swal.fire({
          //         icon: 'warning',
          //         title: 'Ingrese todos los datos requeridos',
          //         showConfirmButton: false,
          //         timer: 2000
          //     })
              
          // }
      },
  
    },
  };
  
  </script>
  
  <style>
  </style>