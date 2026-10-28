<template>
    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">            
              <h1>
                <i class="far fa-registered"></i>&nbsp;
                REGISTRAR CURSANTE
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
                    <div class="col-sm-4">
                      <button class="btn btn-danger btn-sm float-right" type="submit" @click="Atras()">
                        <i class="fas fa-arrow-left"></i>&nbsp; VOLVER
                      </button>
                    </div>
                  </div>  
                </div>
                <div class="card-body">
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
                            <select class="form-control" v-model="promocion" :class="{ 'is-invalid' : $v.promocion.$error, 'is-valid':!$v.promocion.$invalid }">
                                <option value="" disabled>SELECCIONE</option>
                                <option v-for="promocion in arrayPromocion" :key="promocion.id" :value="promocion.id"  v-text="promocion.promo"></option>
                            </select>
                            <div class="invalid-feedback">
                                <span v-if="!$v.promocion.required">Este campo es Requerido</span>
                            </div>
                        </div> 
                    </div>
                  <form>
                        <h3><i class="fas fa-inbox"></i>&nbsp;CURSANTE</h3>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Carnet de Identidad</label>
                                <input type="text" v-model="per_ci" class="form-control" :class="{ 'is-invalid' : $v.per_ci.$error, 'is-valid':!$v.per_ci.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_ci.required">Este campo es Requerido</span>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Carnet Militar</label>
                                <input type="text" v-model="per_cm" class="form-control" :class="{ 'is-invalid' : $v.per_cm.$error, 'is-valid':!$v.per_cm.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_cm.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Fuerza</label>
                                <select class="form-control" v-model="per_fuerza" @change="ListarGrado(per_fuerza), SelectFuerza(per_fuerza)"  :class="{ 'is-invalid' : $v.per_fuerza.$error, 'is-valid':!$v.per_fuerza.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="fuerza in arrayFuerza" :key="fuerza.id" :value="fuerza.id"  v-text="fuerza.fuerza"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_fuerza.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Grado</label>
                                <select class="form-control" v-model="per_grado" @change="SelectGrado(per_grado)" :class="{ 'is-invalid' : $v.per_grado.$error, 'is-valid':!$v.per_grado.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option v-for="grado in arrayGrado" :key="grado.id" :value="grado.id"  v-text="grado.abreviatura"></option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_grado.required">Este campo es Requerido</span>
                                </div>
                            </div>      
                        </div>
                        <div class="form-group row">          
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Nombres</label>
                                <input type="text" v-model.trim="per_nombre" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_nombre.$error, 'is-valid':!$v.per_nombre.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_nombre.required">Este campo es Requerido</span>
                                    <span v-else-if="!$v.per_nombre.letrasSpanish">Solo letras</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Ap. Paterno</label>
                                <input type="text" v-model.trim="per_appaterno" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_appaterno.$error, 'is-valid':!$v.per_appaterno.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_appaterno.required && !$v.per_appaterno.letrasSpanishVacio">Solo letras o Vacio</span>
                                    <span v-else-if="!$v.per_appaterno.letrasSpanishVacio">Solo letras</span>
                                </div>  
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Ap. Materno</label>
                                <input type="text" v-model.trim="per_apmaterno" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_apmaterno.$error, 'is-valid':!$v.per_apmaterno.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_apmaterno.required && !$v.per_apmaterno.letrasSpanishVacio">Solo letras o Vacio</span>
                                    <span v-else-if="!$v.per_apmaterno.letrasSpanishVacio">Solo letras</span>
                                </div>                                
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label" for="text-input">Fecha Nacimiento</label>
                                <input type="date" v-model="per_fechnac" class="form-control" :class="{ 'is-invalid' : $v.per_fechnac.$error, 'is-valid':!$v.per_fechnac.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_fechnac.required">Este campo es Requerido</span>
                                </div>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label class="form-control-label" for="text-input">Sexo</label>
                                <select class="form-control" v-model="per_sexo" :class="{ 'is-invalid' : $v.per_sexo.$error, 'is-valid':!$v.per_sexo.$invalid }">
                                    <option value="" disabled>SELECCIONE</option>
                                    <option value="MASCULINO">MASCULINO</option>
                                    <option value="FEMENINO">FEMENINO</option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_sexo.required">Este campo es Requerido</span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-control-label" for="text-input">Celular </label>
                                <input type="text" v-model.number="per_celular" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_celular.$error, 'is-valid':!$v.per_celular.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_celular.required">Este campo es Requerido</span>
                                    <span v-else-if="!$v.per_celular.numeric">Solo digitos</span>
                                    <span v-else-if="!$v.per_celular.length">Debe contener 8 digitos</span>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">E-mail </label>
                                <input type="text" v-model.trim="per_email" class="form-control" :class="{ 'is-invalid' : $v.per_email.$error, 'is-valid':!$v.per_email.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_email.required">Este campo es Requerido</span>
                                    <span v-else-if="!$v.per_email.email">Email Incorrecto</span>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <label class="form-control-label" for="text-input">Dirección</label>
                                <input type="text" v-model.trim="per_direccion" class="form-control" style="text-transform:uppercase;" :class="{ 'is-invalid' : $v.per_direccion.$error, 'is-valid':!$v.per_direccion.$invalid }">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.per_direccion.required">Este campo es Requerido</span>
                                </div>
                            </div>                          
                        </div>
                        <button type="button" class="btn btn-primary" @click="agregarPersonal()">
                            <i class="fas fa-plus"></i>&nbsp; AGREGAR
                        </button>             
                  </form>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-users"></i>
                            Lista de Cursantes
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>C.I.</th>
                                        <th>C.M.</th>
                                        <th>Fuerza</th>
                                        <th>Grado</th>
                                        <th>Nombres</th>
                                        <th>Ap. Paterno</th>
                                        <th>Ap. Materno</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-if="personasTemporales.length === 0">
                                        <td colspan="9" class="text-center">
                                            No hay personas agregadas.
                                        </td>
                                    </tr>

                                    <tr v-for="(persona, index) in personasTemporales" :key="persona.idTemporal">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ persona.per_ci }}</td>
                                        <td>{{ persona.per_cm }}</td>
                                        <td>{{ persona.per_fuerza }}</td>
                                        <td>{{ persona.per_grado }}</td>
                                        <td>{{ persona.per_nombre }}</td>
                                        <td>{{ persona.per_appaterno }}</td>
                                        <td>{{ persona.per_apmaterno }}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" @click="editarPersonal(index)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarPersonal(index)" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-right">
                            <strong>Total de Cursantes a ser registrados: {{ personasTemporales.length }}</strong>
                        </div>

                        <button type="button" class="btn btn-success mt-3" @click="registrarLista()" :disabled="personasTemporales.length === 0">
                            <i class="fas fa-save"></i>REGISTRAR LISTA
                        </button>
                    </div>
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
        personasTemporales: [],
        indiceEdicion: null,
        contadorTemporal: 0,

        // INICIO VARIABLES DGAE
        // per_foto : '',
        
        per_ci : '',
        per_cm : '',
        per_fuerza : '',
        per_grado : '',
        per_nombre : '',
        per_appaterno : '',
        per_apmaterno : '',
        per_sexo : '',
        per_celular : '',
        per_email : '',
        per_fechnac : '',
        per_direccion : '',
        diplomado : '',
        gestion : '',
        promocion : '',
        v : 0,
        arraySexo : ['MASCULINO','FEMENINO'],
        // FIN VARIABLES DGAE
        arrayDiplomado : [],
        arrayPersonal : [],
        arrayPromocion : [],
        arrayFuerza : [],
        arrayGrado : [],
        arrayGestiones : [],
        arraySelectFuerza : [],
        selectFuerza : '',
        
        listaPersonal: [],
        isHidden: true,
        datfis2 : 1,
  
          
        
        offset : 3,
        }
    },
  
    validations: {
        // per_foto: { required },
        per_ci: { required },
        per_cm: { required },
        per_fuerza: { required },
        per_grado: { required },
        per_nombre : { required, letrasSpanish: value => /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        per_appaterno : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },
        per_apmaterno : { letrasSpanishVacio: value => !value || /^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ ]+$/.test(value) },            
        per_sexo : { required },
        per_celular : { required, numeric, hasSpecificLength: value => value && value.toString().length === 8 },
        per_email : { required, email},
        per_fechnac : { required },
        per_direccion : { required },
        diplomado : { required },
        gestion : { required },
        promocion : { required },


        validationGroupRegTemp: [
        // 'per_foto',
        'per_ci',
        'per_cm',
        'per_fuerza',
        'per_grado',
        'per_nombre',
        'per_appaterno',
        'per_apmaterno',
        'per_sexo',
        'per_celular',
        'per_email',
        'per_fechnac',
        'per_direccion',
        'diplomado',
        'gestion',
        'promocion'
        ],
    },
  
   computed: {
        
    },

    mounted() {
        this.ListarDiplomados();
        this.ListarFuerzas();
    },

    methods: {
        Atras(){ //DGAE
            this.$router.push({
                name: "DatosCursantes",  
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

        ListarFuerzas(){
            let me = this;
            axios
            .post("/listarFuerza", {
            })
            .then(function (response) {
            me.arrayFuerza = response.data.fuerza
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        ListarGrado(fuerza){
            this.per_grado = '';
            this.arrayGrado = [];
            let me = this;
            axios
            .post("/listarGrado", {
                fuerza : fuerza,
            })
            .then(function (response) {
            me.arrayGrado = response.data.grado
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        SelectFuerza(fuerzaid) {
            let me = this;
            axios
            .post("/selectFuerza", {
                fuerzaid : fuerzaid,
            })
            .then(function (response) {
            me.arraySelectFuerza = response.data.fuerza;
            me.selectFuerza = me.arraySelectFuerza.abreviatura;
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        SelectGrado(gradoid) {
            axios
            .post("/selectGrado", {
                gradoid : gradoid,
            })
            .then(function (response) {
            me.arraySelectGrado = response.data.grado
            })
            .catch(function (error) {
            // handle error
            console.log(error);
            })
        },

        agregarPersonal() {
            if(!this.$v.validationGroupRegTemp.$invalid){
                const persona = {
                    idTemporal: this.indiceEdicion !== null
                        ? this.personasTemporales[this.indiceEdicion].idTemporal
                        : ++this.contadorTemporal,
                    // per_fuerza: this.per_fuerza,
                    per_fuerzaid: this.per_fuerza,
                    per_fuerza: this.selectFuerza,
                    per_grado: this.per_grado,
                    per_ci: this.per_ci,
                    per_cm: this.per_cm,
                    per_nombre: (this.per_nombre || '').toUpperCase(),
                    per_appaterno: (this.per_appaterno || '').toUpperCase(),
                    per_apmaterno: (this.per_apmaterno || '').toUpperCase(),
                    per_fechnac: this.per_fechnac,
                    per_sexo: this.per_sexo,
                    per_celular: this.per_celular,
                    per_email: this.per_email,
                    per_direccion: (this.per_direccion || '').toUpperCase(),
                };

                if (this.indiceEdicion !== null) {
                    // Actualizar la persona seleccionada
                    this.$set(
                        this.personasTemporales,
                        this.indiceEdicion,
                        persona
                    );

                    this.indiceEdicion = null;
                } else {
                    // Agregar una nueva persona
                    this.personasTemporales.push(persona);
                }

                this.limpiarFormulario();
            } else{
                this.$v.validationGroupRegTemp.$touch();
                Swal.fire({
                    icon: 'warning',
                    title: 'Ingrese todos los datos requeridos',
                    showConfirmButton: false,
                    timer: 2000
                })
                
            }        
        },

        editarPersonal(index) {
            const persona = this.personasTemporales[index];

            // this.per_diplomado = persona.per_diplomado;
            this.per_fuerza = persona.per_fuerzaid;
            this.per_ci = persona.per_ci;
            this.per_cm = persona.per_cm;
            this.per_grado = persona.per_grado;
            this.per_nombre = persona.per_nombre;
            this.per_appaterno = persona.per_appaterno;
            this.per_apmaterno = persona.per_apmaterno;
            this.per_fechnac = persona.per_fechnac;
            this.per_sexo = persona.per_sexo;
            this.per_celular = persona.per_celular;
            this.per_email = persona.per_email;
            this.per_direccion = persona.per_direccion;
            // this.per_foto = persona.per_foto;
            // this.imagen = persona.fotoVista;

            this.indiceEdicion = index;

            // Limpiar los errores anteriores de validación
            this.$v.validationGroupRegTemp.$reset();
        },

        eliminarPersonal(index) {
            this.personasTemporales.splice(index, 1);

            // Si se elimina el registro que se estaba editando,
            // cancelar el modo de edición.
            if (this.indiceEdicion === index) {
                this.indiceEdicion = null;
                this.limpiarFormulario();
            } else if (
                this.indiceEdicion !== null &&
                this.indiceEdicion > index
            ) {
                this.indiceEdicion--;
            }
        },

        limpiarFormulario() {
            this.per_fuerza = '';
            this.per_ci = '';
            this.per_cm = '';
            this.per_grado = '';
            this.per_nombre = '';
            this.per_appaterno = '';
            this.per_apmaterno = '';
            this.per_fechnac = '';
            this.per_sexo = '';
            this.per_celular = '';
            this.per_email = '';
            this.per_direccion = '';
            this.arraySelectFuerza = [],
            this.selectFuerza = '',

            this.$nextTick(() => {
                this.$v.$reset();
            });
        },

        registrarLista() {
            if (this.personasTemporales.length === 0) {
                return;
            }

            const datos = this.personasTemporales.map(persona => ({
                per_fuerza: persona.per_fuerzaid,
                per_ci: persona.per_ci,
                per_cm: persona.per_cm,
                per_grado: persona.per_grado,
                per_nombre: persona.per_nombre,
                per_appaterno: persona.per_appaterno,
                per_apmaterno: persona.per_apmaterno,
                per_fechnac: persona.per_fechnac,
                per_sexo: persona.per_sexo,
                per_celular: persona.per_celular,
                per_email: persona.per_email,
                per_direccion: persona.per_direccion
            }));
            let me = this;
            axios.post('/guardarListaCursantes', {
                cursantes : datos,
                diplopromo_id : me.promocion
            })
            .then(response => {
                // Vaciar la lista solo cuando el servidor confirme el registro.
                this.personasTemporales = [];
                this.indiceEdicion = null;
                this.limpiarFormulario();

                this.$swal(
                    'Correcto',
                    response.data.message || 'Personas registradas correctamente',
                    'success'
                );
            })
            .catch(error => {
                console.error(error);

                this.$swal(
                    'Error',
                    'No se pudo registrar la lista de personas.',
                    'error'
                );
            });
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
    },
  };
  
  </script>
  
  <style>
  </style>