
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('bodega', require('./components/Bodega.vue'));
Vue.component('usuario', require('./components/User.vue'));
Vue.component('transferir', require('./components/Transferir.vue'));
Vue.component('transferiradmin', require('./components/TransferirAdministrador.vue'));
Vue.component('inicio', require('./components/Inicio.vue'));
Vue.component('inicioadmin', require('./components/InicioAdministrador.vue'));
Vue.component('ingreso', require('./components/Ingreso.vue'));


const app = new Vue({
    el: '#app',
    data :{
        menu : 0,
        //ruta: 'http://panaderia.atwebpages.com/public_html'
         // ruta: 'http://localhost:80/sistemaprestamo/public'
         //ruta: 'http://192.168.0.105:80/sistemaprestamo/public'
         
         ruta: 'http://control.mayahonh.com/public_html'
        

    }
});
