/**
 * Import Components
 */
import mainSettings from './components/settings'

/**
 * Import packages
 */
import Vue   from 'vue'
import state from '@store/index'
import Vuex, { mapMutations, mapGetters } from 'vuex'

/**
 * Register Validator if vue-validate is defined
 */
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

/**
 * Register global components
 */
// import vueSelect2 from '@dist/vue-select2'
import vSelect    from "vue-select";
import color from 'vue-color'

Vue.component("v-select", vSelect);
// Vue.component('ulisting-select2', vueSelect2)
Vue.component('colour-picker', color.Slider)

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
	el: '#wf-app',

	mounted() {
		if ( typeof wf_settings_data !== "undefined" ) {
			this.setPropertyByName({name: 'ajaxUrl', value: wf_settings_data.ajaxUrl})
			this.setPropertyByName({name: 'ajaxNonce', value: wf_settings_data.ajaxNonce})
		}
	},

	computed: {
		...mapGetters([
			'getPropertyByName'
		])
	},

	methods: {
		...mapMutations([
			'setPropertyByName'
		]),
	},

	components: {
		'main-settings': mainSettings,
	},
})
