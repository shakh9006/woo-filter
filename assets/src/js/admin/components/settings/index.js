import addField from './partials/add-field'
import content  from './partials/content'
import header   from './partials/header'
import sidebar  from './partials/sidebar'
import existing from './partials/existing'

import {mapActions, mapGetters, mapMutations} from 'vuex'

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
			loaded		 : true,
			logic_options: null,
			view_options : null,
		}
	},

	created() {
		if ( this.getPropertyByName('filter_id') )
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
		},

		tabs: {
			get() {
				return this.getPropertyByName('tabs')
			},

			set(value) {
				this.setPropertyByName({name: 'tabs', value})
			}
		},

		types: {
			get() {
				return this.getPropertyByName('types')
			},

			set(value) {
				this.setPropertyByName({name: 'types', value})
			}
		},

		loader: {
			get() {
				return this.getPropertyByName('loader')
			},

			set(value) {
				this.setPropertyByName({name: 'loader', value})
			}
		},

	},

	methods: {
		...mapMutations([
			'setPropertyByName',
		]),

		...mapActions([
			'getAdminData',
		]),

		renderData() {
			if ( this.loaded ) {
				const url   = this.getPropertyByName('ajaxUrl')
				const data  = { action: 'wf_get_settings_data', id: this.getPropertyByName('filter_id') }
				this.loaded = false
				this.getAdminData({url, data})
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
			
			<div class="wf-settings-container builder" v-if="getPropertyByName('logic_options') && step === 'builder' && !loader">
				<wf-sidebar :tabs="tabs"></wf-sidebar>
				<wf-content></wf-content>
				<wf-add-field :types="types"></wf-add-field>
			</div>
			<wf-loader v-if="loader"></wf-loader>
		</div>
	`
}