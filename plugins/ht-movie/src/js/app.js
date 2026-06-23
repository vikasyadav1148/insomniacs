import Vue from 'vue';
import Multiselect from 'vue-multiselect'; // Multi select library
import VueSweetalert2 from 'vue-sweetalert2';
import searchMovie from './components/searchMovie.vue';

window.Vue = Vue;

Vue.component('search-movie', searchMovie);
Vue.component('multiselect', Multiselect);
Vue.use(VueSweetalert2);

const import_movie = new Vue({
	el: '#import_movie',
	data: {
		title: 'Import Movie / TV Show from TMDB - 1.0',
	},
	methods: {},
});
