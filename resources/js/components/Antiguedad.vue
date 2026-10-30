<template>
    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-12">            
              <h1>
                <i class="far fa-registered"></i>&nbsp;
                ANTIGUEDAD
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
                        <h3><i class="fas fa-inbox"></i>&nbsp;ELEGIR TIPO REPORTE</h3>
                        <div class="form-group row">
                             <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Antiguedad</label>
                                <select class="form-control" v-model="reporte" @change="limpiarFormulario()">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option value="1">POR MODULO</option>
                                    <option value="2">EN GENERAL</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="reporte == 1">
                            <h3><i class="fas fa-inbox"></i>&nbsp;ELEGIR ESPECIALIDAD, PROMOCIÓN y MÓDULO</h3>
                            <br>
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
                                        <option v-for="promocion in arrayPromocion" :key="promocion.id" :value="promocion.id"  v-text="promocion.promo"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.promocion.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-control-label" for="text-input">Módulo</label>
                                    <select class="form-control" v-model="modulo" @change="BuscarPromediosModulo()" :class="{ 'is-invalid' : $v.modulo.$error, 'is-valid':!$v.modulo.$invalid }">
                                        <option value="" disabled>SELECCIONE</option>
                                        <option v-for="modulo in arrayModulo" :key="modulo.id" :value="modulo.id"  v-text="modulo.modulo"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.modulo.required">Este campo es Requerido</span>
                                    </div>
                                </div> 
                            </div>
                            <!-- <button type="button" class="btn btn-primary" @click="BuscarPromediosModulo()">
                                <i class="fas fa-plus"></i>&nbsp; BUSCAR
                            </button> -->
                            <div class="table-wrapper-scroll-y my-custom-scrollbar" id="myTable" style="font-size: 12pt;" >
                                <!-- <template v-if="datfis2==1"> -->
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>GRADO</th>
                                            <th>AP. PATERNO</th>
                                            <th>AP. MATERNO</th>
                                            <th>NOMBRES</th>
                                            <th v-for="materia in arrayMaterias" :key="materia.id">
                                                {{ materia.materia }}
                                            </th>
                                            <th>PROM. FINAL</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(personal, index) in arrayCursantesPromocion" :key="personal.diplopromocursante_id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ personal.abreviatura }}</td>
                                            <td>{{ personal.per_paterno }}</td>
                                            <td>{{ personal.per_materno }}</td>
                                            <td>{{ personal.per_nombre }}</td>
                                            <td v-for="materia in arrayMaterias" :key="materia.id" :style="{ color: Number(personal.promedios_materias[materia.id] || 0) < 51 ? 'red' : 'inherit'}">
                                                {{ Number(personal.promedios_materias[materia.id] || 0).toFixed(2) }}
                                            </td>
                                            <td>
                                                <strong :style="{ color: Number(personal.promedio_final || 0) < 51 ? 'red' : 'inherit'}">
                                                    {{ Number(personal.promedio_final || 0).toFixed(2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr v-if="arrayCursantesPromocion.length === 0">
                                            <td :colspan="6 + arrayMaterias.length" class="text-center">
                                                No hay cursantes para mostrar.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div>
                                    <!-- Aquí va tu tabla completa -->
                                    <div class="text-right mt-3 mb-4">
                                        <strong>
                                            Total de cursantes: {{ arrayCursantesPromocion.length }}
                                        </strong>
                                    </div>
                                    <button type="button" class="btn btn-success ml-3" @click="GenerarReporte(1)" :disabled="arrayCursantesPromocion.length === 0 || arrayMaterias.length === 0">
                                        <i class="fas fa-save"></i>&nbsp; GENERAR REPORTE
                                    </button>
                                </div>
                                <!-- </template> -->
                                <br>
                            </div>
                        </div>
                        <div v-if="reporte == 2">
                            <h3><i class="fas fa-inbox"></i>&nbsp;ELEGIR ESPECIALIDAD, PROMOCIÓN</h3>
                            <br>
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
                                    <select class="form-control" v-model="promocion" @change="BuscarPromediosGral()" :class="{ 'is-invalid' : $v.promocion.$error, 'is-valid':!$v.promocion.$invalid }">
                                        <option value="" disabled>SELECCIONE</option>
                                        <option v-for="promocion in arrayPromocion" :key="promocion.id" :value="promocion.id"  v-text="promocion.promo"></option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.promocion.required">Este campo es Requerido</span>
                                    </div>
                                </div>
                            </div>
                            <!-- <button type="button" class="btn btn-primary" @click="BuscarPromediosGral()">
                                <i class="fas fa-plus"></i>&nbsp; BUSCAR
                            </button> -->

                            <div class="table-wrapper-scroll-y my-custom-scrollbar" id="myTable" style="font-size: 12pt;">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>GRADO</th>
                                            <th>AP. PATERNO</th>
                                            <th>AP. MATERNO</th>
                                            <th>NOMBRES</th>
                                            <th v-for="modulo in arrayModulos" :key="modulo.id">
                                                MÓDULO {{ modulo.modulo }}
                                            </th>
                                            <th>PROM. FINAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(personal, index) in arrayCursantesPromocion" :key="personal.diplopromocursante_id">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ personal.abreviatura }}</td>
                                            <td>{{ personal.per_paterno }}</td>
                                            <td>{{ personal.per_materno }}</td>
                                            <td>{{ personal.per_nombre }}</td>
                                            <td v-for="modulo in arrayModulos" :key="modulo.id" class="text-center" :style="{color: Number(personal.promedios_modulos[modulo.id] || 0) < 51 ? 'red' : 'inherit'}">
                                                {{ Number(personal.promedios_modulos[modulo.id] || 0).toFixed(2) }}
                                            </td>
                                            <td class="text-center">
                                                <strong :style="{color: Number(personal.promedio_final || 0) < 51 ? 'red' : 'inherit'}">
                                                    {{ Number(personal.promedio_final || 0).toFixed(2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr v-if="arrayCursantesPromocion.length === 0">
                                            <td :colspan="6 + arrayModulos.length" class="text-center">
                                                No hay cursantes para mostrar.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="text-right mt-3 mb-4">
                                    <strong>
                                        Total de cursantes: {{ arrayCursantesPromocion.length }}
                                    </strong> 
                                </div>
                                <button type="button" class="btn btn-success ml-3" @click="GenerarReporte(2)" :disabled="arrayCursantesPromocion.length === 0 || arrayModulos.length === 0">
                                    <i class="fas fa-save"></i>&nbsp; GENERAR REPORTE
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
        diplomado : '',
        gestion : '',
        promocion : '',
        modulo : '',
        reporte : '',
        // materia : '',
        // evaluacion : '',

        v : 0,
        
        // FIN VARIABLES DGAE
        arrayDiplomado : [],
        arrayPromocion : [],
        arrayGestiones : [],
        anios: [],
        arrayModulo : [],
        arrayModulos : [],
        // arrayMateria : [],
        arrayCursantesPromocion : [],
        arrayMaterias : [],
        arrayMateriasModulo : [],
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
        // materia : { required },
        // evaluacion : { required },

        validationGroupRepModulo: [
            'diplomado',
            'gestion',
            'promocion',
            'modulo',
        ],

        validationGroupRepGral: [
            'diplomado',
            'gestion',
            'promocion',
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
        // this.ListarPeriodos();
    },

    methods: {

        Atras(){ //DGAE
            this.$router.push({
                name: "Notas",  
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
            this.arrayModulo = [];
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

        ListarPromociones(especialidad, gestion){
            this.promocion = '';
            this.arrayPromocion = [];
            this.modulo = '';
            this.arrayModulo = [];
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

        BuscarPromediosModulo() {
            axios.post('/listarPromediosModulo', {
            promocion: this.promocion,
            modulo: this.modulo
            })
            .then(response => {
            this.arrayMaterias = response.data.materias;
            this.arrayCursantesPromocion = response.data.cursantes;
            })
            .catch(error => {
            console.error(error);

                this.$swal(
                    'Error',
                    'No se pudieron consultar los promedios.',
                    'error'
                );
            });
        },

        BuscarPromediosGral() {
            axios.post('/listarPromediosPromocion', {
                promocion: this.promocion
            })
            .then(response => {
                this.arrayModulos = response.data.modulos || [];
                this.arrayCursantesPromocion = response.data.cursantes || [];
            })
            .catch(error => {
                console.error(error);

                this.arrayModulos = [];
                this.arrayCursantesPromocion = [];

                this.$swal(
                    'Error',
                    error.response?.data?.message ||
                    'No se pudieron obtener los promedios de la promoción.',
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
            this.arrayCursantesPromocion = [];
            this.arrayModulos = [];
            this.arrayMaterias = [];
            // this.per_foto = null;

            // Ajusta esta ruta a la imagen predeterminada de tu proyecto.
            // this.imagen = '/img/avatar.jpg';

            this.$nextTick(() => {
                this.$v.$reset();
            });
        },

        RegistrarNotas(){
            if (this.arrayCursantesPromocion.length === 0) {
                return;
            }

            const datos = this.arrayCursantesPromocion.map(cursante => ({
                // per_diplomado: cursante.per_diplomado,
                diplopromocursante_id: cursante.diplopromocursante_id,
                personals_id: cursante.personals_id,
                nota: cursante.nota,
            }));
            let me = this;
            axios.post('/guardarNotasCursantes', {
                cursantes : datos,
                materia : me.materia,
                evaluacion : me.evaluacion
            })
            .then(response => {
                // Vaciar la lista solo cuando el servidor confirme el registro.
                this.arrayCursantesPromocion = [];
                // this.indiceEdicion = null;
                this.limpiarFormulario();

                this.$swal(
                    'Correcto',
                    response.data.message || 'Notas de cursantes registradas correctamente',
                    'success'
                );
            })
            .catch(error => {
                console.error(error);

                this.$swal(
                    'Error',
                    'No se pudo registrar las notas de los cursantes.',
                    'error'
                );
            });
        },

        GenerarReporte(tiporeporte){ //DGAE
            switch (tiporeporte) {
                case 1:
                    if (!this.$v.validationGroupRepModulo.$invalid) {
                        var promocion = this.promocion;
                        var modulo = this.modulo;
                        var diplomado = this.diplomado;
                        var gestion = this.gestion;
                        window.open('/ant_mod?diplomado='+diplomado+'&gestion='+gestion+'&promocion='+promocion+'&modulo='+modulo);
                    } else {
                        this.$v.validationGroupRepModulo.$touch();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Ingrese todos los datos requeridos',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                    break;

                case 2:
                    if (!this.$v.validationGroupRepGral .$invalid) {
                        var promocion = this.promocion;
                        var diplomado = this.diplomado;
                        var gestion = this.gestion;
                        window.open('/ant_gral?diplomado='+diplomado+'&gestion='+gestion+'&promocion='+promocion);
                    } else {
                        this.$v.validationGroupRepGral.$touch();
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
  
    },
  };
  
  </script>
  
  <style>
  </style>