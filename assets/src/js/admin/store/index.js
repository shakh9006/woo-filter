import Vue from 'vue'
import { postRequest, renderToast } from '@utils/index';

export default {
	state: {
		step: 'home',
		ajaxUrl: '',
		ajaxNonce: '',
		used: [],
		fields: [],
		active_tab: 'custom-fields',
	},

	getters: {
		getPropertyByName: state => name => {
			if ( typeof state[name] !== "undefined" ) {
				return state[name]
			}
		},

		getReadyState(state) {
			const result = [];
			state.used.forEach(element => {
				let meta = {}
				let save_data = {}
				delete element.is_open

				save_data.tag   	   = element.tag
				save_data.type  	   = element.type
				save_data.name  	   = element.type
				save_data.title		   = element.label || 'Empty'
				save_data.description  = element.description
				save_data.label_toggle = element.label_toggle

				const keys = ['tag', 'type', 'name', 'title', 'label_toggle']
				for ( let key in  element)
					if ( keys.indexOf(key) === -1 && typeof element[key] !== "object")
						meta[key] = element[key]

				result.push({save_data, meta})
			})

			return result;
		}
	},

	actions: {
		addField({state}, {type}) {
			const fields = JSON.parse(JSON.stringify(state.fields))
			const field  = Object.values(fields).find(field => field.type === type)

			if ( typeof field !== "undefined" ) {
				console.log('used: ', state.used)
				let used      = JSON.parse(JSON.stringify(state.used)) || []
				used 		  = used.map(u => {
					u.is_open = false
					return u
				})

				field.is_open = true
				used.push(field)
				state.used = used
			}
		},

		saveSettings({state, getters}, {url}) {
			const data = {
				action: 'wf_save_settings',
				fields: getters.getReadyState
			}

			postRequest(url, data, response => {
				renderToast(response.message, response.status)
			})
		}
	},

	mutations: {
		setPropertyByName(state, {name, value}) {
			if ( state.hasOwnProperty(name) ) {
				Vue.set(state, name, value)
			}
		}
	},
}