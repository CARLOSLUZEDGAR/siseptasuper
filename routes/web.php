<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/**
 * Rutas de funciones estandarizadas
 */

// RUTAS DGAE //

//**************************RUTAS DE ACCESO AL SISTEMA******************************* */


/**
 * Rutas para personal
 */
Route::post('/datPer','PersonalController@DatosPersonalesAcceso');

/**
 * RUTAS PARA CREACION DE USUARIOS
 */
Route::post('/crearUsuario','UsuarioController@CrearUsuario'); //BASE
Route::post('/listarUsuarios','UsuarioController@ListarUsuarios');
Route::post('/datosUsuarios','UsuarioController@DatosUsuarios'); 
Route::put('/editarUsuarios','UsuarioController@EditarUsuario'); //BASE
Route::put('/cambiarEstadoUsuario','UsuarioController@CambiarEstadoUsuario');
Route::get('/datosUsuario','UsuarioController@DatosUsuario');
Route::post('/editContrasena','UsuarioController@EditContrasena');

Route::post('/authenticate/ingreso','Auth\LoginController@login');
Route::get('/authenticate/salir','Auth\LoginController@logout')->name('logout');
Route::get('/listarPermisos','Auth\LoginController@ListarPermisos');
Route::get('/login','Auth\LoginController@VistaLogin')->name('login');

/**
 * rutas para la admnitraciond e datos
 */
Route::post('/listarol','RoleController@ListarRole');
Route::post('/listarol2','RoleController@ListarRole2'); //Roles qu no tiene aasignado el usuario
Route::post('/listarolus','RoleController@ListarRoleUsuario');
Route::post('/listarPermisos','RoleController@ListarPermisos');
Route::post('/listaRolPermiso','RoleController@ListaRolPermiso');
Route::post('/guardarRol','RoleController@GuardarRol');
Route::post('/editarRol','RoleController@EditarRol');
Route::get('/listarRoles','RoleController@ListarRoles'); //BASE
//rutas que permiten adicionar y quitar roles a los usuarios
Route::post('/agregarRol','RoleController@AgregarRol');
Route::post('/quitarRol','RoleController@QuitarRol');

/**
 * Rutas para listar permisos
 */
Route::post('/listapermisos','PermisoController@ListarPermisos');
Route::post('/guadarPermiso','PermisoController@GuardarPermisos');
Route::post('/editarPermiso','PermisoController@EditarPermisos');
Route::post('/datosPermiso','PermisoController@DatosPermiso');
Route::get('/listarModulos','PermisoController@ListarModulos');

//NOMBRE DEL USUARIO
Route::get('/datosP','DatosController@datosP');
Route::post('/crearPersonal','PersonalController@CrearPersonal');
Route::post('/listarPersonal','PersonalController@ListarPersonal');
Route::post('/renovarPersonal','PersonalController@RenovarPersonal');
Route::post('/eliminarPersonal','PersonalController@EliminarPersonal');
Route::get('/verificarPersonal/{id_per_lic}','PersonalController@VerificarPersonal');
Route::post('/datosPersonal','PersonalController@DatosPersonal');

Route::post('/listarDiplomadoPromociones', 'DiplomadoPromocionController@ListarDiplomadoPromocion');
Route::post('/selectEspecialidad', 'DiplomadoController@SelectDiplomado');
Route::post('/listarPeriodo', 'PeriodoController@ListarPeriodo');
Route::post('/guardarPromocion', 'DiplomadoPromocionController@CrearDiplomadoPromocion');
Route::post('/ultimaPromocion', 'DiplomadoPromocionController@UltimoDiplomadoPromocion');
Route::post('/listarPromocion', 'DiplomadoPromocionController@SelectDiplomadoPromocion');
Route::post('/listarFuerza', 'FuerzaController@ListarFuerza');
Route::post('/listarGrado', 'GradoController@ListarGrado');
Route::post('/guardarListaCursantes','PersonalController@CrearPersonal');
Route::post('/selectModulo', 'DiplomadoModuloController@SelectDiplomadoModulo');
Route::post('/selectMateria', 'ModuloMateriaController@SelectModuloMateria');
Route::post('/listarCursantesPromocion', 'DiplomadoPromocionCursanteController@ListarDiplomadoPromocionCursante');
Route::post('/listarEvaluacion', 'EvaluacionController@ListarEvaluacion');
Route::post('/guardarNotasCursantes','CursanteNotaController@CrearCursanteNota');
Route::post('/listarEspecialidad', 'DiplomadoController@ListarDiplomado');
Route::post('/listarCursantesPromocionNotas', 'CursanteNotaController@ListarDiplomadoPromocionCursante');
Route::post('/listarPromediosModulo', 'CursanteNotaController@ListarPromediosModulo');
Route::get('/ant_mod', 'ReporteController@ReporteAntiguedadModulo');
Route::get('/ant_gral', 'ReporteController@ReporteAntiguedadGral');
Route::post('/registrarEspecialidad', 'DiplomadoController@CrearDiplomado');
Route::post('/mostrarEspecialidad', 'DiplomadoController@MostrarDiplomado');
Route::post('/editarEspecialidad', 'DiplomadoController@EditarDiplomado');
Route::post('/cambiarEstadoEspecialidad', 'DiplomadoController@CambiarEstadoDiplomado');
Route::post('/listarModulo', 'DiplomadoModuloController@ListarDiplomadoModulo');
Route::post('/selectGestion', 'DiplomadoModuloController@SelectGestionDiplomadoModulo');
Route::post('/selectPromocion', 'DiplomadoModuloController@SelectPromocionDiplomadoModulo');
Route::post('/listarPromocionModulo', 'DiplomadoModuloController@BuscarDiplomadoModulo');
Route::post('/guardarNuevosModulosMaterias', 'DiplomadoModuloController@CrearNuevosModulosMaterias');
Route::post('/listarMateria', 'ModuloMateriaController@ListarModuloMateria');
Route::post('/listarModuloMateria', 'ModuloMateriaController@BuscarModuloMateria');
Route::post('/guardarNuevasMaterias', 'ModuloMateriaController@GuardarNuevasMaterias');
Route::post('/listarPromediosPromocion', 'CursanteNotaController@ListarPromediosPromocion');
Route::post('/selectFuerza', 'FuerzaController@SelectFuerza');
Route::post('/selectGrado', 'GradoController@SelectGrado');






/****************************** FUNCIONES DE EXPORTACION DE EXCEL *******************************************/

Route::get('/indGenDest','Excel\IndiceGenDestController@export');

Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath')
   ->where('optional','.*');

