
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/*use Vue Route*/
import VueRouter from 'vue-router';
Vue.use(VueRouter);
import router from './routes';

/*Vue ProgressBar*/
import VueProgressBar from 'vue-progressbar'

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})

/*VForm*/
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
// Validate
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError) 

/*Filter*/
import './filter.js';

/*Use vue-sweetalert2 */
import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

/*Use Component local for hook*/
window.Fire = new Vue();

/*Use pagination*/
Vue.component('pagination', require('laravel-vue-pagination'));

/* use multiple select */
import Multiselect from 'vue-multiselect'
/*register globally*/
Vue.component('multiselect', Multiselect)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data: {
      updateProfile: 0,
      username: 'username',
    }
});
