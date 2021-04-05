import Vue from 'vue'
export default {
	state: {
		ajaxUrl: '',
		ajaxNonce: '',
		id: null,
		fields: [],
		settings: {},
		loader: false,
		product_categories: [],
		product_tags: [],
		sort_by: [],
	},

	getters: {
		getStateByName: s => name => {
			if ( s.hasOwnProperty(name) )
				return s[name]
		},
	},

	mutations: {
		setStateByName(state, {name, value}) {
			if ( state.hasOwnProperty(name) ) {
				Vue.set(state, name, value)
			}
		}
	},

	actions: {},
}