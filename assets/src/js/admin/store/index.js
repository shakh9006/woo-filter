import Vue from 'vue'
import {getRequest, postRequest, renderToast} from '@utils/index';

export default {
	state: {
		filter_id	  : null,
		returned      : false,
		list          : [],
		tabs          : [],
		types         : [],
		title		  : '',
		loader		  : false,
		step	   	  : 'home',
		used	   	  : [],
		tags       	  : [],
		fields	   	  : [],
		ajaxUrl	   	  : '',
		ajaxNonce  	  : '',
		sort_type	  : null,
		active_tab 	  : 'custom-fields',
		categories 	  : [],
		view_options  : [],
		logic_options : [],


		order_by_list : [],
		order_list    : [],
		position_list : [],
		ajax_list     : [],

		filter_settings: null,
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
				save_data.title		   = element.title || 'Empty'
				save_data.description  = element.description
				save_data.label_toggle = element.label_toggle

				const keys = ['tag', 'type', 'name', 'title', 'label_toggle', 'description']
				for ( let key in  element )
					if ( keys.indexOf(key) === -1 )
						meta[key] = element[key]
				result.push({save_data, meta})
			})

			return result;
		}
	},

	actions: {
		addField({state}, {type}) {
			const field  = Object.values(state.fields).find(field => field.type === type)
			if ( typeof field !== "undefined" ) {
				let used      = JSON.parse(JSON.stringify(state.used))
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
			state.loader = true
			const data = {
				action	: 'wf_save_settings',
				fields	: getters.getReadyState,
				title 	: state.title,
				id		: state.filter_id,
				settings: state.filter_settings,
			}

			postRequest(url, data, response => {
				state.loader = false
				state.filter_id = response?.filter_id || null
				renderToast(response.message, response.status)
			})
		},

		resetSettings({state}) {
			state.title		      = ''
			state.used	   	      = []
			state.tags       	  = []
			state.active_tab 	  = 'custom-fields'
			state.filter_settings = null
		},

		getAdminData({state, getters}, {url, data}) {
			state.loader = true
			getRequest(url, data, response => {
				const {data} = response
				state.tags 			= data?.tags || []
				state.tabs 			= data?.tabs || []
				state.used 			= data?.used || []
				state.types 		= data?.types || []
				state.fields 		= data?.fields || []
				state.order_list 	= data?.order_list || []
				state.position_list = data?.position_list || []
				state.ajax_list 	= data?.ajax_list || []
				state.title 	    = data?.title || 'Empty'
				state.sort_type 	= data?.sort_type || []
				state.filter_id 	= data?.filter_id || null
				state.categories    = data?.categories || []
				state.view_options  = data?.view_options || []
				state.logic_options = data?.logic_options || []
				state.order_by_list = data?.order_by_list || []


				state.filter_settings = data?.filter_settings || {}
				setTimeout(() => state.loader = false)
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