import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

export default new Router ({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [

        // INICIO RUTAS

        {
            path: '/usuarios',
            name: 'Usuarios',
            component:  require('./components/Usuarios/Index.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper') && per.includes('view-user')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/DatosCursantes',
            name: 'DatosCursantes',
            component: require('./components/DatosCursantes.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-cursantes')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },
        
        {
            path: '/RegistroCursantes',
            name: 'RegistroCursantes',
            component: require('./components/RegistroCursantes.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-insert-cursante')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/Antiguedad',
            name: 'Antiguedad',
            component: require('./components/Antiguedad.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-antiguedad')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RenovarPersonal/:personalid',
            name: 'RenovarPersonal',
            component: require('./components/RenovarPersonal.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-renew-per')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/Gestion',
            name: 'Gestion',
            component: require('./components/Gestion.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-gestion')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/Especialidades',
            name: 'Especialidades',
            component: require('./components/Especialidad.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-diplomados')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/Modulos',
            name: 'Modulos',
            component: require('./components/Modulo.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-modulos')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/Materias',
            name: 'Materias',
            component: require('./components/Materia.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-materias')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RegistroDiplomadoPromocion',
            name: 'RegistroDiplomadoPromocion',
            component: require('./components/RegistroDiplomadoPromocion.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-reg-diplopromo')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/Notas',
            name: 'Notas',
            component: require('./components/Notas.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-notas')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/RegistroModulos',
            name: 'RegistroModulos',
            component: require('./components/RegistroModulos.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('insert-modulo')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        // FIN RUTAS

        {
            path: '/notFound',
            name: 'Notfound',
            component: require('./components/Notfound.vue').default
        },
        
        {
            path: '/',
            name: 'Inicio',
            component: require('./components/Bienvenida.vue').default
        },

        {
            path: '/passwordChange',
            name: 'PasswordChange',
            component: require('./components/PasswordChange.vue').default
        },

        /**
         * Ruta para creacion de usuarios
         */
        
        {
            path: '/datosUser',
            name: 'DatosUser',
            component:  require('./components/Usuarios/DatosUser.vue').default
        },
        /**
         * Rutas para cracion de roles
         */
        {
            path: '/roles',
            name: 'Roles',
            component:  require('./components/Roles/Index.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper') && per.includes('view-rol')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/nuevoRol',
            name: 'NuevoRol',
            component:  require('./components/Roles/Create.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/editarRol/:id',
            name: 'EditarRol',
            component:  require('./components/Roles/Editar.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },
        /**
         * Rutas para los permisos
         */
        {
            path: '/permisos',
            name: 'Permisos',
            component:  require('./components/Permisos/Index.vue').default,
            beforeEnter: (to, from, next) => {
                let per = window.user.permissions.map(permission=>permission.name);
                if (per.includes('view-rolper') && per.includes('view-permi')) {
                    next();
                } else {
                    next(from.path);
                }
            }
        },

        {
            path: '/acercade',
            name: 'Acercade',
            component:  require('./components/AcercaDe.vue').default
        },

        {
            path: '/ayuda',
            name: 'Ayuda',
            component:  require('./components/Ayuda.vue').default
        },
    ]
})