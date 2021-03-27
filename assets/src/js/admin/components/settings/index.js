import addField from './partials/add-field'
import content  from './partials/content'
import header   from './partials/header'
import sidebar  from './partials/sidebar'
import existing from './partials/existing'

import { getRequest } from '@utils/index';
import {mapGetters, mapMutations} from 'vuex'

export default {
	components: {
		'wf-add-field': addField,
		'wf-content'  : content,
		'wf-header'   : header,
		'wf-sidebar'  : sidebar,
		'wf-existing' : existing,
	},

	data() {
		return {
			loader       : true,
			loaded		 : true,
			tabs	     : [],
			types	     : [],
			logic_options: null,
			view_options : null,
		}
	},

	created() {
		if ( typeof wf_settings_data !== "undefined" && wf_settings_data?.id )
			this.step = 'builder'
	},

	computed: {
		...mapGetters([
			'getPropertyByName'
		]),

		step: {
			get() {
				const value = this.getPropertyByName('step')
				if ( value === 'builder' )
					this.renderData()
				return value
			},

			set(value) {
				if ( value === 'builder' )
					this.renderData()
				this.setPropertyByName({name: 'step', value})
			}
		}
	},

	methods: {
		...mapMutations([
			'setPropertyByName'
		]),

		renderData() {
			if ( this.loaded ) {
				const url  = this.getPropertyByName('ajaxUrl')
				const data = { action: 'wf_get_settings_data', id: wf_settings_data?.id }
				this.loaded = false

				getRequest(url, data, response => {
					const { data }     = response
					this.tabs 	       = data?.tabs || []
					this.types 	   	   = data?.types || []
					this.view_options  = data?.view_options
					this.logic_options = data?.logic_options

					this.setPropertyByName({name: 'used',   value: data?.used   || []})
					this.setPropertyByName({name: 'fields', value: data?.fields || []})
					setTimeout(() => this.loader = false)
				})
			}
		}
	},

	template: `
		<div class="wf-settings">
			<wf-header></wf-header>

			<div class="wf-settings-container home" v-if="step === 'home'">
				<div class="wf-settings-content"></div>
				<wf-existing></wf-existing>
			</div>
			
			<div class="wf-settings-container builder" v-if="logic_options && step === 'builder' && !loader">
				<wf-sidebar :tabs="tabs"></wf-sidebar>
				<wf-content :logic_options="logic_options" :view_options="view_options"></wf-content>
				<wf-add-field :types="types"></wf-add-field>
			</div>
		</div>
	`
}