<template>
    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">            
              <h1>
                <i class="far fa-registered"></i>&nbsp;
                REGISTRAR/EDITAR MATERIA
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
                        <div class="step" v-if="currentStep === 1">
                            <h3><i class="fas fa-inbox"></i>&nbsp;SELECCIONAR ESPECIALIDAD, GESTIÓN, PROMOCIÓN Y MÓDULO</h3>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="form-control-label" for="text-input">Especialidad</label>
                                    <select class="form-control" v-model="diplomado" @change="ListarGestiones(diplomado)" :class="{ 'is-invalid' : $v.diplomado.$error, 'is-valid':!$v.diplomado.$invalid }">
                                        <option value="" disabled>SELECCIONE</option>
                                        <option v-for="diplomado in arrayDiplomado" :key="diplomado.id" :value="diplomado.id"  v-text="diplomado.diplomado"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.diplomado.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-control-label" for="text-input">Gestión</label>
                                    <select class="form-control" v-model="gestion" @change="ListarPromociones(diplomado, gestion)" :class="{ 'is-invalid' : $v.gestion.$error, 'is-valid':!$v.gestion.$invalid }">
                                        <option value="" disabled>SELECCIONE</option>
                                        <option v-for="gestion in arrayGestiones" :key="gestion.gestion" :value="gestion.gestion"  v-text="gestion.gestion"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.gestion.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-control-label" for="text-input">Promoción</label>
                                    <select class="form-control" v-model="promocion" @change="ListarModulos(promocion)" :class="{ 'is-invalid' : $v.promocion.$error, 'is-valid':!$v.promocion.$invalid }">
                                        <option value="" disabled>SELECCIONE</option>
                                        <option v-for="promocion in arrayPromociones" :key="promocion.id" :value="promocion.id"  v-text="promocion.promo"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.promocion.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-control-label" for="text-input">Módulo</label>
                                    <select class="form-control" v-model="modulo" @change="BuscarMateria(modulo)" :class="{ 'is-invalid' : $v.modulo.$error, 'is-valid':!$v.modulo.$invalid }">
                                        <option value="" disabled>SELECCIONE</option>
                                        <option v-for="modulo in arrayModulos" :key="modulo.id" :value="modulo.id"  v-text="modulo.modulo"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.modulo.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                            </div>

                            <div v-if="mostrarMaterias">
                                <!-- Recorrer todos los módulos creados en el paso anterior -->
                                <div class="card mb-3" v-for="(input, indexModulo) in inputs" :key="'modulo-' + indexModulo">
                                    <div class="card-header">
                                        <strong>MÓDULO {{ input.modulo }}</strong>
                                        <span class="ml-2">{{ input.ini_mod || 'Sin fecha de inicio' }} al {{ input.fin_mod || 'Sin fecha de fin' }}</span>
                                    </div>
                                    <div class="card-body">
                                        <!-- Botón para agregar materias a este módulo -->
                                        <button type="button" class="btn btn-sm btn-outline-success mb-3" @click="crearMateria(indexModulo)">
                                            + Agregar Materia
                                        </button>

                                        <!-- Recorrer las materias del módulo actual -->
                                        <div class="form-group row" v-for="(materia, indexMateria) in input.materias" :key="materia.id || ('nueva-materia-' + indexModulo + '-' + indexMateria)">
                                            <div class="col-md-1">
                                                <label class="form-control-label">Nº</label>
                                                <input type="number" class="form-control" :value="materia.numero" readonly/>
                                            </div>

                                            <div class="col-md-5">
                                                <label :for="'nombre-' + indexModulo + '-' + indexMateria">
                                                    Materia
                                                </label>
                                                <input type="text" class="form-control" style="text-transform:uppercase;" :id="'nombre-' + indexModulo + '-' + indexMateria" v-model="materia.nombre" placeholder="Ingrese el nombre de la materia"/>
                                            </div>

                                            <div class="col-md-4">
                                                <label :for="'docente-' + indexModulo + '-' + indexMateria">
                                                    Docente
                                                </label>
                                                <input type="text" class="form-control" style="text-transform:uppercase;" :id="'docente-' + indexModulo + '-' + indexMateria" v-model="materia.docente" placeholder="Ingrese el nombre del docente"/>
                                            </div>

                                            <!-- Solo mostrar para materias nuevas -->
                                            <div class="col-md-2" v-if="!materia.id">
                                                <label class="form-control-label d-block">Eliminar</label>
                                                <button type="button" class="btn btn-danger" @click="eliminarMateria(indexModulo, indexMateria)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <p v-if="!input.materias || input.materias.length === 0"
                                        class="text-muted">
                                            Este módulo todavía no tiene materias registradas.
                                        </p>
                                    </div>
                                </div>

                                <!-- Navegación del carrusel -->
                                <div class="form-group row justify-content-end">
                                    <!-- <button type="button" class="btn btn-primary" @click="previousStep">
                                        <i class="fas fa-backward"></i>&nbsp; ANTERIOR
                                    </button>
                                    &nbsp; -->
                                    <button type="button" class="btn btn-danger" @click="guardarMateria()">
                                        <i class="fas fa-forward"></i>&nbsp; GUARDAR
                                    </button>
                                </div>
                            </div>
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
        mostrarMaterias: false,
        diplomado : '',
        gestion : '',
        promocion : '',
        modulo : '',

        arrayDiplomado : [],
        arrayGestiones : [],
        arrayPromociones : [],
        arrayModulos : [],
        
        currentStep : 1,
       
        v : 0,
        
        // FIN VARIABLES DGAE
        
        inputs : [],
        
        isHidden: true,
        datfis2 : 1,

      }
    },
  
    validations: {
        diplomado : { required },
        gestion: { required },
        promocion : { required },
        modulo : { required },

        validationGroup1: [
        'diplomado',
        'gestion',
        'promocion',
        'modulo'
        ],
    },
  
   computed: {
          
      },
    mounted() {
        this.ListarDiplomados();
    },

    methods: {
        Atras(){
            this.$router.push({
                name: "Materias",  
            });
        },

        nextStep() { 
            switch (this.currentStep) {
                case 1:
                    if(!this.$v.validationGroup1.$invalid){
                        this.currentStep++;
                    }else{
                        this.$v.validationGroup1.$touch();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ingrese todos los datos requeridos',
                            showConfirmButton: false,
                            timer: 2000
                        })   
                    }
                    break;   
                default:
                    this.currentStep++;
                    break;
            }
        },

        previousStep() { //DGAE
            this.currentStep--;
        },

        crearModulos() {
            let siguienteModulo = this.inputs.length + 1;

            this.inputs.push({
                id: null,
                modulo: siguienteModulo,
                ini_mod: '',
                fin_mod: '',
                materias: [],
            });
        },

        eliminarModulos(index) {
            // Verificar que el módulo sea nuevo
            if (!this.inputs[index].id) {

                // Eliminar únicamente el módulo nuevo
                this.inputs.splice(index, 1);

                // Renumerar solamente los módulos nuevos
                let numeroModulo = 1;

                this.inputs.forEach(input => {
                    if (!input.id) {
                        // Buscar el siguiente número después de los módulos existentes
                        let maximo = Math.max(
                            0,
                            ...this.inputs
                                .filter(mod => mod.id)
                                .map(mod => Number(mod.modulo) || 0)
                        );

                        input.modulo = maximo + numeroModulo;
                        numeroModulo++;
                    }
                });
            }
        },

        crearMateria(indexModulo) {
            if (!Array.isArray(this.inputs[indexModulo].materias)) {
                this.$set(this.inputs[indexModulo], 'materias', []);
            }

            const materias = this.inputs[indexModulo].materias;

            const siguienteNumero = materias.length > 0
                ? Math.max(...materias.map(m => Number(m.numero) || 0)) + 1
                : 1;

            materias.push({
                id: null,
                numero: siguienteNumero,
                nombre: '',
                docente: ''
            });
        },

        eliminarMateria(indexModulo, indexMateria) {
            const materias = this.inputs[indexModulo].materias;
            const materia = materias[indexMateria];

            // No permitir eliminar materias que ya están en la BD
            if (materia.id) {
                return;
            }

            materias.splice(indexMateria, 1);

            // Reordenar únicamente las materias nuevas
            let siguienteNumero = materias.reduce((max, m) => {
                return Math.max(max, Number(m.numero) || 0);
            }, 0);

            materias.forEach(m => {
                if (!m.id) {
                    m.numero = ++siguienteNumero;
                }
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

        ListarGestiones(diplomadoid) {
            this.gestion = '';
            this.arrayGestiones = [];
            this.promocion = '';
            this.arrayPromociones = [];
            this.modulo = '';
            this.arrayModulos = [];
            let me = this;
            axios
            .post("/selectGestion", {
                diplomadoid : diplomadoid
            })
            .then(function (response) {
            me.arrayGestiones = response.data.gestion
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarPromociones(diplomadoid, gestion) {
            this.promocion = '';
            this.arrayPromociones = [];
            this.modulo = '';
            this.arrayModulos = [];
            let me = this;
            axios
            .post("/selectPromocion", {
                diplomadoid : diplomadoid,
                gestion : gestion
            })
            .then(function (response) {
            me.arrayPromociones = response.data.promocion
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarModulos(diplopromo_id) {
            this.modulo = '';
            this.arrayModulos = [];
            let me = this;
            axios
            .post("/selectModulo", {
                diplopromo_id : diplopromo_id
            })
            .then(function (response) {
            me.arrayModulos = response.data.modulo
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        BuscarMateria(diplomod_id) {
            this.mostrarMaterias = false;

            if (this.$v.validationGroup1.$invalid) {
                this.$v.validationGroup1.$touch();

                Swal.fire({
                    icon: 'warning',
                    title: 'Seleccione todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                });

                return;
            }

            axios.post('/listarModuloMateria', {
                diplomod_id: diplomod_id
            })
            .then(response => {
                const item = response.data.modulo;

                this.inputs = [{
                    id: item.id,
                    modulo: item.modulo,
                    ini_mod: item.inicio_mod,
                    fin_mod: item.fin_mod,
                    materias: (item.materias || []).map(materia => ({
                        id: materia.id,
                        numero: materia.numero,
                        nombre: materia.nombre,
                        docente: materia.docente || ''
                    }))
                }];

                this.mostrarMaterias = true;
            })
            .catch(error => {
                console.error(error);

                this.inputs = [];
                this.mostrarMaterias = false;

                this.$swal(
                    'Error',
                    error.response?.data?.mensaje ||
                    'No se pudieron cargar las materias del módulo seleccionado.',
                    'error'
                );
            });
        },
        
        guardarMateria() {
            if(!this.$v.validationGroup1.$invalid){
                swal.fire({
                    title: '¿Desea guardar los cambios de las materias?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#868077',
                    buttonsStyling: true,
                    reverseButtons: true
                }).then((result) => {
                    console.log('Resultado SweetAlert:', result);
                    if (result.value) {
                        const me = this;
                        axios.post('/guardarNuevasMaterias', {
                            diplomod_id: me.modulo,
                            modulos: me.inputs
                        })
                        .then(response => {
                            if (response.data.success) {
                                swal.fire({
                                    icon: 'success',
                                    title: 'REGISTRO CORRECTO',
                                    text: response.data.mensaje
                                }).then(() => {
                                    me.Atras();
                                });
                            } else {
                                swal.fire({
                                    icon: 'warning',
                                    title: 'Atención',
                                    text: response.data.mensaje ||
                                        'No se pudieron guardar los cambios.'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al guardar:', error);

                            swal.fire({
                                icon: 'error',
                                title: 'ERROR AL REGISTRAR',
                                text: error.response?.data?.mensaje ||
                                    'Ocurrió un error al guardar las materias.'
                            });
                        });
                    } else {
                        swal.fire(
                            'Información',
                            'Solicitud cancelada.',
                            'info'
                        );
                    }
                });

            } else{
                this.$v.validationGroup1.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                })
                
            }
        },
    },
  };
  
  </script>
  
  <style>
  </style>