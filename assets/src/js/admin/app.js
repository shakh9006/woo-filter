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
import color from 'vue-color'
import wfSelect from '@js/libs/select-wrapper'
import loader from './components/partials/loader'
Vue.component('wf-loader', loader)
Vue.component('wf-select2', wfSelect)
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
			this.setPropertyByName({ name: 'ajaxUrl',   value: wf_settings_data.ajaxUrl })
			this.setPropertyByName({ name: 'ajaxNonce', value: wf_settings_data.ajaxNonce })
			this.setPropertyByName({ name: 'filter_id', value: wf_settings_data.id || null })
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
