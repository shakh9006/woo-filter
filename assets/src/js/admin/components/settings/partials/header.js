import { mapGetters, mapActions, mapMutations } from "vuex";
import { getRequest } from '@utils/index';

export default {
	computed: {
		...mapGetters([
			'getPropertyByName'
		]),

		step: {
			get() {
				return this.getPropertyByName('step')
			},

			set(value) {
				this.setPropertyByName({name: 'step', value})
			}
		},

		title: {
			get() {
				return this.getPropertyByName('title') || 'Empty'
			},

			set(value) {
				this.setPropertyByName({name: 'title', value})
			}
		},

		id: {
			get() {
				return this.getPropertyByName('filter_id') || null
			},

			set(value) {
				this.setPropertyByName({name: 'filter_id', value})
			}
		}
	},

	methods: {
		...mapActions([
			'saveSettings',
			'resetSettings',
			'getAdminData'
		]),

		...mapMutations([
			'setPropertyByName'
		]),

		save(id) {
			const url = this.getPropertyByName('ajaxUrl')
			this.saveSettings({url, id})
		},

		save_title() {
			this.show_input = false
		},

		back_to_lit() {
			const id = this.id
			this.setPropertyByName({name: 'filter_id', value: null})
			this.setPropertyByName({name: 'returned', value: true})

			if ( confirm('Save this filter?') ) {
				this.save(id)
				setTimeout(() => {
					this.update_list()
					this.step = 'home'
				})
			} else {
				this.step = 'home'
			}

			this.delete_id()
		},

		delete_id() {
			let params = new URLSearchParams(location.search)
			params.delete('id')
			history.replaceState(null, '', '?' + params + location.hash)
		},

		update_list() {
			const url = this.getPropertyByName('ajaxUrl')
			getRequest(url, {action: 'wf_filter_list'}, response => {
				const lists = response.filters || []
				this.setPropertyByName({name: 'list', value: lists})
			})
		},

		reset() {
			this.resetSettings()
			const url = this.getPropertyByName('ajaxUrl')
			this.getAdminData({url, data: { action: 'wf_get_settings_data' }})
		},

		create_new() {
			if ( this.getPropertyByName('returned') ) {
				this.setPropertyByName({name: 'filter_id', value: null})
				this.reset()
			}
			this.step     = 'builder'
			this.returned = false
		}
	},

	data() {
		return {
			show_input: false,
		}
	},

	template: `
		<div class="wf-settings-header">
			<div v-if="step === 'builder'" style="display: flex; align-items: center">
				<span class="wf-filter-title-left">Edit Filter:</span> 
				<span class="wf-filter-title-fixed" @click.prevent="show_input = true" v-if="!show_input">
					{{ title }}
					<i class="icon--350"></i>
				</span>
				<input type="text" class="wf-filter-title-editable" v-model="title" v-if="show_input">
				<button class="btn btn-success btn-sm" v-if="show_input" @click="save_title">Save</button>
			</div>
			<div v-else>Home Page</div>
			<div v-if="step === 'builder'">
				<span class="wf-short-code" v-if="id">
					[wf-filter id='{{ id }}']
				</span>
				<button type="button" class="btn btn-secondary btn-lg" @click.prevent="back_to_lit">Back to List</button>
				<button type="button" class="btn btn-primary btn-lg" @click.prevent="save(id)">Save Settings</button>		
			</div>
			<button type="button" class="btn btn-primary btn-lg" @click.prevent="create_new" v-else>Create New</button>
		</div>
	`
}