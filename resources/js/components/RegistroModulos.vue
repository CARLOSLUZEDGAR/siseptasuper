<template>
    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">            
              <h1>
                <i class="far fa-registered"></i>&nbsp;
                REGISTRAR/EDITAR MÓDULO
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
                            <h3><i class="fas fa-inbox"></i>&nbsp;SELECCIONAR ESPECIALIDAD, GESTIÓN Y PROMOCIÓN</h3>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label class="form-control-label" for="text-input">Especialidad</label>
                                    <select class="form-control" v-model="diplomado" @change="ListarGestiones(diplomado)" :class="{ 'is-invalid' : $v.diplomado.$error, 'is-valid':!$v.diplomado.$invalid }">
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
                                        <option v-for="gestion in arrayGestiones" :key="gestion.gestion" :value="gestion.gestion"  v-text="gestion.gestion"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.gestion.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control-label" for="text-input">Promoción</label>
                                    <select class="form-control" v-model="promocion" @change="BuscarModulo(promocion)" :class="{ 'is-invalid' : $v.promocion.$error, 'is-valid':!$v.promocion.$invalid }">
                                        <option value="" disabled>SELECCIONE</option>
                                        <option v-for="promocion in arrayPromociones" :key="promocion.id" :value="promocion.id"  v-text="promocion.promo"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.promocion.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                            </div>

                            <div v-if="mostrarModulos">
                                <!-- Botón para agregar módulos -->
                                <button type="button" class="btn btn-sm btn-outline-success mb-3" @click="crearModulos()">
                                    + Agregar Módulo
                                </button>

                                <!-- Tarjetas de módulos -->
                                <div class="card mb-3" v-for="(input, index) in inputs" :key="input.id || ('nuevo-modulo-' + index)">
                                    <!-- Encabezado de la tarjeta -->
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <strong>MÓDULO {{ input.modulo || index + 1 }}</strong>
                                        <!-- Solo permite eliminar módulos nuevos -->
                                        <button v-if="!input.id" type="button" class="btn btn-sm btn-danger" @click="eliminarModulos(index)" title="Eliminar módulo">
                                            <i class="fa fa-trash"></i>&nbsp; Eliminar
                                        </button>
                                    </div>
                                    <!-- Contenido de la tarjeta -->
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <!-- Fecha de inicio -->
                                            <div class="col-md-6">
                                                <label :for="'ini_mod' + index">
                                                    Fecha de inicio
                                                </label>
                                                <input type="date" v-model="input.ini_mod" class="form-control" :id="'ini_mod' + index"/>
                                            </div>
                                            <!-- Fecha de finalización -->
                                            <div class="col-md-6">
                                                <label :for="'fin_mod' + index">
                                                    Fecha de finalización
                                                </label>
                                                <input type="date" v-model="input.fin_mod" class="form-control" :id="'fin_mod' + index"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Mensaje cuando no existen módulos -->
                                <p v-if="inputs.length === 0" class="text-muted">Aun no hay módulos registrados. Presione "+ Agregar Módulo" para comenzar.</p>
                                <!-- Navegación -->
                                <div class="form-group row justify-content-end">
                                    <button type="button" class="btn btn-primary" @click="previousStep">
                                        <i class="fas fa-backward"></i>&nbsp; ANTERIOR
                                    </button>
                                    &nbsp;
                                    <button type="button" class="btn btn-danger" @click="nextStep">
                                        <i class="fas fa-forward"></i>&nbsp; SIGUIENTE
                                    </button>
                                </div>
                            </div>
                        </div> 

                        <div class="step" v-if="currentStep === 2">
                            <h3><i class="fas fa-book"></i>&nbsp;MATERIAS POR MÓDULO</h3>
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
                                    <div
                                        class="form-group row"
                                        v-for="(materia, indexMateria) in input.materias"
                                        :key="materia.id || ('nueva-materia-' + indexModulo + '-' + indexMateria)"
                                    >
                                        <div class="col-md-1">
                                            <label class="form-control-label">Nº</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                :value="materia.numero"
                                                readonly
                                            />
                                        </div>

                                        <div class="col-md-5">
                                            <label :for="'nombre-' + indexModulo + '-' + indexMateria">
                                                Materia
                                            </label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                style="text-transform:uppercase;"
                                                :id="'nombre-' + indexModulo + '-' + indexMateria"
                                                v-model="materia.nombre"
                                                placeholder="Ingrese el nombre de la materia"
                                            />
                                        </div>

                                        <div class="col-md-4">
                                            <label :for="'docente-' + indexModulo + '-' + indexMateria">
                                                Docente
                                            </label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                style="text-transform:uppercase;"
                                                :id="'docente-' + indexModulo + '-' + indexMateria"
                                                v-model="materia.docente"
                                                placeholder="Ingrese el nombre del docente"
                                            />
                                        </div>

                                        <!-- Solo mostrar para materias nuevas -->
                                        <div class="col-md-2" v-if="!materia.id">
                                            <label class="form-control-label d-block">Eliminar</label>
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                @click="eliminarMateria(indexModulo, indexMateria)"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p v-if="input.materias.length === 0" class="text-muted">Este módulo todavía no tiene materias registradas.</p>
                                </div>
                            </div>

                            <!-- Navegación del carrusel -->
                            <div class="form-group row justify-content-end">
                                <button type="button" class="btn btn-primary" @click="previousStep">
                                    <i class="fas fa-backward"></i>&nbsp; ANTERIOR
                                </button>
                                &nbsp;
                                <button type="button" class="btn btn-danger" @click="guardarModuloMateria()">
                                    <i class="fas fa-forward"></i>&nbsp; GUARDAR
                                </button>
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
        mostrarModulos: false,
        diplomado : '',
        gestion : '',
        promocion : '',

        arrayDiplomado : [],
        arrayGestiones : [],
        arrayPromociones : [],
        arrayPromocionModulo : [],
        
        currentStep : 1,
       
        v : 0,
        
        // FIN VARIABLES DGAE
        
        inputs : [],
        
        listaPersonal: [],
        isHidden: true,
        datfis2 : 1,
      }
    },
  
    validations: {
        diplomado : { required },
        gestion: { required },
        promocion : { required },

        validationGroup1: [
        'diplomado',
        'gestion',
        'promocion'
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
                name: "Modulos",  
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
            const materias = this.inputs[indexModulo].materias || [];

            const numerosExistentes = materias.map(materia =>
                Number(materia.numero) || 0
            );

            const siguienteNumero = Math.max(0, ...numerosExistentes) + 1;

            this.inputs[indexModulo].materias.push({
                id: null,
                numero: siguienteNumero,
                nombre: '',
                docente: ''
            });
        },

        eliminarMateria(indexModulo, indexMateria) {
            const materias = this.inputs[indexModulo].materias;
            const materia = materias[indexMateria];

            // No permitir eliminar materias registradas
            if (materia.id) {
                return;
            }

            // Eliminar únicamente la materia nueva
            materias.splice(indexMateria, 1);

            // Renumerar solamente las materias nuevas
            const numeroExistente = Math.max(
                0,
                ...materias
                    .filter(item => item.id)
                    .map(item => Number(item.numero) || 0)
            );

            let siguienteNumero = numeroExistente + 1;

            materias.forEach(item => {
                if (!item.id) {
                    item.numero = siguienteNumero++;
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

        BuscarModulo(diplopromo_id) {
            this.mostrarModulos = false;
            if (!this.$v.validationGroup1.$invalid) {
                axios.post('/listarPromocionModulo', {
                    diplopromo_id: diplopromo_id
                })
                .then(response => {
                    this.inputs = response.data.modulo.map(item => ({
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
                }));
                this.mostrarModulos = true;
                })
                .catch(error => {
                    console.error(error);
                    this.mostrarModulos = false;
                    this.$swal(
                        'Error',
                        'No se pudieron cargar los módulos de la promoción seleccionada.',
                        'error'
                    );
                });

            } else {
                this.$v.validationGroup1.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Seleccione todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        },

        guardarModuloMateria() {
            if (!this.$v.validationGroup1.$invalid) {
                swal.fire({
                    title: '¿Desea guardar los modulos y materias?', // TITULO 
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
                        .post("/guardarNuevosModulosMaterias", {
                            diplopromo_id : me.promocion,
                            modulos: me.inputs
                        })
                        .then(function (response) {
                            console.log(response);
                            if (response.data.success) {
                                swal.fire({
                                    icon: 'success',
                                    title: 'REGISTRO CORRECTO',
                                    text: response.data.mensaje
                                });
                                me.Atras();
                            }
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error);
                            swal.fire({
                                icon: 'error',
                                title: 'ERROR AL REGISTRAR',
                                text: error.response?.data?.mensaje ||
                                    'Ocurrió un error al registrar los modulos y materias'
                            });
                        })
                    }else{
                            swal.fire(
                            "Informacion", //TITULO
                            "Solicitud cancelada.", //TEXTO DE MENSAJE
                            "info" // TIPO DE MODAL (success, warnning, error, info)
                        );
                    }
                })
            } else {
                this.$v.validationGroup1.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                });
            }   
        },
    },
  };
  
  </script>
  
  <style>
  </style>