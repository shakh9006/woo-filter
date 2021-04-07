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

import wfSelect from '@js/libs/select-wrapper'
import loader from '@js/admin/components/partials/loader'

Vue.component('wf-loader', loader)
Vue.component('wf-select2', wfSelect)
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