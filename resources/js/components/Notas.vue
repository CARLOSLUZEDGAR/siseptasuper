<template>
    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">            
              <h1>
                <i class="far fa-registered"></i>&nbsp;
                NOTAS
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
                    <div class="col-sm-4" v-if="$auth.can('view-antiguedad')">
                        <button class="btn btn-primary btn-sm float-right" type="submit" @click="VerAntiguedad()">
                        <i class="fas fa-user-plus"></i>&nbsp; VER ANTIGUEDAD
                        </button>
                    </div>
                  </div>  
                </div>
                <div class="card-body">
                    <form>
                        <h3><i class="fas fa-inbox"></i>&nbsp;ELEGIR PROMOCION Y MATERIA</h3>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Especialidad</label>
                                <select class="form-control" v-model="diplomado" @change="ListarPromociones(diplomado, gestion)" :class="{ 'is-invalid' : $v.diplomado.$error, 'is-valid':!$v.diplomado.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="diplomado in arrayDiplomado" :key="diplomado.id" :value="diplomado.id"  v-text="diplomado.diplomado"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.diplomado.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Gestión</label>
                                <select class="form-control" v-model="gestion" @change="ListarPromociones(diplomado, gestion)" :class="{ 'is-invalid' : $v.gestion.$error, 'is-valid':!$v.gestion.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="a in anios" :key="a" :value="a">{{ a }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.gestion.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Promoción</label>
                                <select class="form-control" v-model="promocion" @change="ListarModulos(promocion)" :class="{ 'is-invalid' : $v.promocion.$error, 'is-valid':!$v.promocion.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="promocion in arrayPromocion" :key="promocion.id" :value="promocion.id"  v-text="promocion.promo"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.promocion.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Módulo</label>
                                <select class="form-control" v-model="modulo" @change="ListarMaterias(modulo)" :class="{ 'is-invalid' : $v.modulo.$error, 'is-valid':!$v.modulo.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="modulo in arrayModulo" :key="modulo.id" :value="modulo.id"  v-text="modulo.modulo"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.modulo.required">Este campo es Requerido</span>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Materia</label>
                                <select class="form-control" v-model="materia" @change="ListarEvaluaciones()" :class="{ 'is-invalid' : $v.materia.$error, 'is-valid':!$v.materia.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="materia in arrayMateria" :key="materia.id" :value="materia.id"  v-text="materia.materia"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.materia.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Evaluación</label>
                                <select class="form-control" v-model="evaluacion" :class="{ 'is-invalid' : $v.evaluacion.$error, 'is-valid':!$v.evaluacion.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="evaluacion in arrayEvaluacion" :key="evaluacion.id" :value="evaluacion.id"  v-text="evaluacion.evaluacion"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.evaluacion.required">Este campo es Requerido</span>
                                </div>
                            </div> -->
                            <button type="button" class="btn btn-primary" @click="BuscarCursantes(promocion)">
                                <i class="fas fa-plus"></i>&nbsp; BUSCAR
                            </button>
                        </div>
                    </form>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar" id="myTable" style="font-size: 12pt;" >

                        <!-- <template v-if="datfis2==1"> -->
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Grado</th>
                                    <th class="text-center">Ap. Paterno</th>
                                    <th class="text-center">Ap. Materno</th>
                                    <th class="text-center">Nombres</th>
                                    <th class="text-center">1er Parcial</th>
                                    <th class="text-center">2do Parcial</th>
                                    <th class="text-center">Ex Final</th>
                                    <th class="text-center">Apr Docente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(personal,index) in arrayCursantesPromocion"> 
                                    <!-- <td v-text="personal.grado+' '+personal.complemento"></td> -->
                                    <td style="text-align:center; font-weight:bold;">{{ index + 1 }}</td>
                                    <td v-text="personal.abreviatura"></td>
                                    <td v-text="personal.per_paterno"></td>
                                    <td v-text="personal.per_materno"></td>
                                    <td v-text="personal.per_nombre"></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" min="0" max="100" step="0.01" v-model.number="personal.pri_parcial" :readonly="personal.pri_parcial_registrada" placeholder="Ingrese nota">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control form-control-sm" min="0" max="100" step="0.01" v-model.number="personal.seg_parcial" :readonly="personal.seg_parcial_registrada" placeholder="Ingrese nota">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control form-control-sm" min="0" max="100" step="0.01" v-model.number="personal.ex_final" :readonly="personal.ex_final_registrada" placeholder="Ingrese nota">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control form-control-sm" min="0" max="100" step="0.01"  v-model.number="personal.apr_docente" :readonly="personal.apr_docente_registrada" placeholder="Ingrese nota">
                                    </td>
                                </tr>
                                <tr v-if="arrayCursantesPromocion.length === 0">
                                    <td colspan="9" class="text-center">
                                        No hay cursantes para mostrar.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-right mt-3 mb-4">
                            <strong>
                                Total de cursantes: {{ arrayCursantesPromocion.length }}
                            </strong>

                            <button type="button" class="btn btn-success ml-3" @click="RegistrarNotas()" :disabled="arrayCursantesPromocion.length === 0">
                                <i class="fas fa-save"></i>&nbsp;REGISTRAR
                            </button>
                        </div>
                        <!-- </template> -->
                        <br>
                    </div>
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
        promocion : '',
        modulo : '',
        materia : '',
        // evaluacion : '',

        v : 0,
        
        // FIN VARIABLES DGAE
        arrayDiplomado : [],
        arrayPromocion : [],
        anios: [],
        arrayModulo : [],
        arrayMateria : [],
        arrayCursantesPromocion : [],
        // arrayEvaluacion : [],
        
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
        promocion : { required },
        modulo : { required },
        materia : { required },
        // evaluacion : { required },

        validationGroupReg: [
        'diplomado',
        'gestion',
        'promocion',
        'modulo',
        'materia',
        // 'evaluacion'
        ],
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

        VerAntiguedad(){
            this.$router.push({
                name: "Antiguedad",  
            });
        },

        nextStep() { //DGAE
            // if (this.currentStep < 4) {
            //     if(this.currentStep == 1){
            //         if(!this.$v.validationGroupReg.$invalid){
            //             this.currentStep++;
            //         }else{
            //             this.$v.validationGroupReg.$touch();
            //             Swal.fire({
            //                 icon: 'warning',
            //                 title: 'Ingrese todos los datos requeridos',
            //                 showConfirmButton: false,
            //                 timer: 2000
            //             })   
            //         }
            //     }
            //     if(this.currentStep == 2){
            //             this.currentStep++;
            //     }
            // }
            this.currentStep++;
        },

        previousStep() { //DGAE
            this.currentStep--;
        },

        crearModulos(){
            this.inputs.push({
                modulo : this.inputs.length + 1,
                ini_mod : '',
                fin_mod : '',
                materias : []
            });
        },

        eliminarModulos(index) {
            this.inputs.splice(index, 1);
            this.inputs.forEach((input, i) => {
                input.modulo = i + 1;
            });
        },

        crearMateria(indexModulo) {
            this.inputs[indexModulo].materias.push({
                numero: this.inputs[indexModulo].materias.length + 1,
                nombre: '',
                docente: ''
            });
        },

        eliminarMateria(indexModulo, indexMateria) {
            this.inputs[indexModulo].materias.splice(indexMateria, 1);

            this.inputs[indexModulo].materias.forEach((materia, i) => {
                materia.numero = i + 1;
            });
        },

        ListarDiplomados(){
            let me = this;
            axios
            .post("/selectEspecialidad", {
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

        ListarPromociones(especialidad, gestion){
            this.promocion = '';
            this.arrayPromocion = [];
            let me = this;
            axios
            .post("/listarPromocion", {
                especialidad : especialidad,
                gestion : gestion
            })
            .then(function (response) {
            me.arrayPromocion = response.data.promocion
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarModulos(promocion){
            this.modulo = '';
            this.arrayModulo = [];
            let me = this;
            axios
            .post("/selectModulo", {
                diplopromo_id : promocion,
            })
            .then(function (response) {
            me.arrayModulo = response.data.modulo
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarMaterias(modulo){
            this.materia = '';
            this.arrayMateria = [];
            let me = this;
            axios
            .post("/selectMateria", {
                modulo : modulo,
            })
            .then(function (response) {
            me.arrayMateria = response.data.materia
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarEvaluaciones(){
            this.evaluacion = '';
            this.arrayEvaluacion = [];
            let me = this;
            axios
            .post("/listarEvaluacion", {
            })
            .then(function (response) {
            me.arrayEvaluacion = response.data.evaluacion
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        BuscarCursantes(promocion) {
            axios.post("/listarCursantesPromocion", {
                promocion: promocion,
                materia: this.materia
            })
            .then(response => {
                this.arrayCursantesPromocion = response.data.cursante;
            })
            .catch(error => {
                console.error(error);

                this.$swal(
                    'Error',
                    'No se pudieron cargar los cursantes y sus notas.',
                    'error'
                );
            });
        },

        limpiarFormulario() {
            // this.per_diplomado = '';
            this.diplomado = '';
            this.gestion = '';
            this.promocion = '';
            this.modulo = '';
            this.materia = '';
            this.evaluacion = '';
            // this.per_foto = null;

            // Ajusta esta ruta a la imagen predeterminada de tu proyecto.
            // this.imagen = '/img/avatar.jpg';

            this.$nextTick(() => {
                this.$v.$reset();
            });
        },

        RegistrarNotas() {
            if (!this.arrayCursantesPromocion || this.arrayCursantesPromocion.length === 0) {
                return;
            }

            const evaluaciones = [
                { campo: 'pri_parcial', evaluacion_id: 1, registrada: 'pri_parcial_registrada' },
                { campo: 'seg_parcial', evaluacion_id: 2, registrada: 'seg_parcial_registrada' },
                { campo: 'ex_final', evaluacion_id: 3, registrada: 'ex_final_registrada' },
                { campo: 'apr_docente', evaluacion_id: 4, registrada: 'apr_docente_registrada' }
            ];

            const notas = [];

            this.arrayCursantesPromocion.forEach(cursante => {
                evaluaciones.forEach(evaluacion => {
                    const valor = cursante[evaluacion.campo];

                    // Registrar solo notas ingresadas y todavía no registradas.
                    if (
                        !cursante[evaluacion.registrada] &&
                        valor !== null &&
                        valor !== undefined &&
                        valor !== ''
                    ) {
                        notas.push({
                            diplopromocursante_id: cursante.diplopromocursante_id,
                            personals_id: cursante.personals_id,
                            evaluacion_id: evaluacion.evaluacion_id,
                            nota: valor
                        });
                    }
                });
            });

            if (notas.length === 0) {
                this.$swal(
                    'Atención',
                    'No hay notas nuevas para registrar.',
                    'warning'
                );
                return;
            }

            axios.post('/guardarNotasCursantes', {
                promocion: this.promocion,
                materia: this.materia,
                notas: notas
            })
            .then(response => {
                // Volver a consultar las notas para bloquear las ya registradas.
                this.BuscarCursantes(this.promocion);

                this.$swal(
                    'Correcto',
                    response.data.mensaje || 'Notas registradas correctamente.',
                    'success'
                );
            })
            .catch(error => {
                console.error(error);

                const mensaje = error.response &&
                                error.response.data &&
                                error.response.data.mensaje
                    ? error.response.data.mensaje
                    : 'No se pudieron registrar las notas.';

                this.$swal('Error', mensaje, 'error');
            });
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

    //   NuevoPersonal(){ //DGAE
    //     this.$v.validationGroupReg.$reset(),
    //     this.per_foto = '',
    //     this.per_ci = '',
    //     this.per_nombre = '',
    //     this.per_appaterno = '',
    //     this.per_apmaterno = '',
    //     this.per_sexo = '',
    //     this.per_celular = '',
    //     this.per_email = '',
    //     this.per_fechnac = '',
    //     this.per_direccion = '',
    //     this.v = 0
    //   },

        guardarPromocion() {
            swal.fire({
                title: '¿Desea guardar la promoción?', // TITULO 
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
                    .post("/guardarPromocion", {
                        diplomado : me.diplomado,
                        gestion : me.gestion,
                        periodo : me.periodo,
                        promocion : me.promocion,
                        modulos: me.inputs
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
                            // me.arrayDatPer = response.data.personal;
                            // me.GenerarCarnet(me.arrayDatPer.id_personal);
                            me.Atras();
                            // this.$v.$reset();
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