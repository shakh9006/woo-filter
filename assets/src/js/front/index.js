import main from './components/main'

/**
 * Import packages
 */
import Vue   from 'vue'
import state from './store/index'
import Vuex, { mapMutations, mapGetters } from 'vuex'

/**
 * Register global components
 */
// import vueSelect2 from '@dist/vue-select2'
import vSelect    from "vue-select";
Vue.component("v-select", vSelect);
// Vue.component('ulisting-select2', vueSelect2)

/**
 * Register Vuex and State
 */
Vue.use(Vuex)
const store = new Vuex.Store(state)

/**
 * Create Vue instance
 */
new Vue({
	store,
	el: '#wf-wrapper',

	mounted() {
		if ( typeof wf_front_data !== "undefined" ) {
			this.setStateByName({name: 'ajaxUrl', value: wf_front_data.ajaxUrl})
			this.setStateByName({name: 'ajaxNonce', value: wf_front_data.ajaxNonce})
			this.setStateByName({name: 'id', value: wf_front_data.id})
			this.setStateByName({name: 'fields', value: wf_front_data.fields})
		}
	},

	computed: {
		...mapGetters([
			'getStateByName'
		])
	},

	methods: {
		...mapMutations([
			'setStateByName'
		]),
	},

	components: {
		'main-filter': main,
	},
})