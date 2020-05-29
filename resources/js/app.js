/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import JwPagination from 'jw-vue-pagination';
// import Datepicker from 'vuejs-datepicker';
// import VCalendar from 'v-calendar';
import Calendar from 'v-calendar/lib/components/calendar.umd';
import DatePicker from 'v-calendar/lib/components/date-picker.umd'


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
Vue.component('my-account', require('./components/MyAccount.vue').default);
Vue.component('change-password', require('./components/ChangePassword.vue').default);
Vue.component('profile-photo', require('./components/ProfilePhoto.vue').default);
Vue.component('test', require('./components/Test.vue').default);
Vue.component('test2', require('./components/Test2.vue').default);
Vue.component('studies', require('./components/Studies.vue').default);

Vue.component('study', require('./components/Study.vue').default);
Vue.component('year', require('./components/Year.vue').default);
Vue.component('subject', require('./components/Subject.vue').default);
Vue.component('subject-section', require('./components/SubjectSection.vue').default);
Vue.component('subject-page', require('./components/SubjectPage.vue').default);
Vue.component('agenda', require('./components/Agenda.vue').default);
Vue.component('planner', require('./components/Planner.vue').default);
Vue.component('planner-tag', require('./components/PlannerTag.vue').default);
Vue.component('planner-tag-dashboard', require('./components/PlannerTagDashboard.vue').default);
Vue.component('estudios-tag-dashboard', require('./components/EstudiosTagDashboard.vue').default);
Vue.component('todo-list-tag', require('./components/TodolistTag.vue').default);

Vue.component('jw-pagination', JwPagination);
// Vue.component('datepicker', Datepicker);
Vue.component('calendar', Calendar);
Vue.component('date-picker', DatePicker);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
