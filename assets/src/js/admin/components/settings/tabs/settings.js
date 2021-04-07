import { mapGetters, mapMutations } from "vuex";

export default {
	props: ['content'],
	components: {

	},

	data() {
		return {

		}
	},

	methods: {
		...mapMutations([
			'setPropertyByName'
		]),
	},

	computed: {
		...mapGetters([
			'getPropertyByName'
		]),

		settings: {
			get() {
				return this.getPropertyByName('filter_settings')
			},

			set(data) {
				const settings = JSON.parse(JSON.stringify(this.getPropertyByName('filter_settings')))
				settings[data.name] = data.value
				this.setPropertyByName({name: 'filter_settings', value: settings})
			}
		},

		order: {
			get() {
				return this.settings?.order
			},

			set(value) {
				this.settings = { name: 'order', value }
			}
		},

		order_by: {
			get() {
				return this.settings?.order_by
			},

			set(value) {
				this.settings = { name: 'order_by', value }
			}
		},

		count: {
			get() {
				return this.settings?.count
			},

			set(value) {
				return this.settings = { name: 'count', value }
			}
		},

		column: {
			get() {
				return this.settings?.column
			},

			set(value) {
				return this.settings = { name: 'column', value }
			}
		},

		position: {
			get() {
				return this.settings?.position
			},

			set(value) {
				return this.settings = {name: 'position', value}
			}
		},

		is_ajax: {
			get() {
				return this.settings?.is_ajax
			},

			set(value) {
				return this.settings = { name: 'is_ajax', value }
			}
		},

		order_by_list() {
			return this.getPropertyByName('order_by_list')
		},

		order_list() {
			return this.getPropertyByName('order_list')
		},

		position_list() {
			return this.getPropertyByName('position_list')
		},

		ajax_list() {
			return this.getPropertyByName('ajax_list')
		}
	},

	template: `
		<div class="wf-settings-tab col-12">
			<div class="wf-settings-row">
				<h3>View Options</h3>
				<div class="container">
					<div class="row">
						<div class="col-4">
							<div class="wf-input-field wf-field">
								<span class="wf-title">Products Column</span> 
								<input placeholder="Enter Label"  type="text" v-model="column">
							</div>
						</div>
						<div class="col-4">
							<div class="wf-input-field wf-field">
								<span class="wf-title">Products count</span> 
								<input placeholder="Enter Label"  type="text" v-model="count">
							</div>
						</div>
						<div class="col-4">
							<div class="wf-admin-select wf-field">
								<span class="wf-title wf-admin-field-title">Filter Position</span>
								<select class="wf-select-box wf-select-box-text wf-normalize" v-model="position">
									<option v-for="pos in position_list" :key="pos.id" :value="pos.id">{{ pos.text }}</option>	
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="wf-settings-row">
				<h3>Filter Options</h3>
				<div class="container">
					<div class="row">
						<div class="col-4">
							<div class="wf-admin-select wf-field">
								<span class="wf-title wf-admin-field-title">Order By</span>
								<select class="wf-select-box wf-select-box-text wf-normalize" v-model="order_by">
									<option v-for="o in order_by_list" :key="o.id" :value="o.id">{{ o.text }}</option>	
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="wf-admin-select wf-field">
								<span class="wf-title wf-admin-field-title">Order</span>
								<select class="wf-select-box wf-select-box-text wf-normalize" v-model="order">
									<option v-for="o in order_list" :key="o.id" :value="o.id">{{ o.text }}</option>	
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="wf-admin-select wf-field">
								<span class="wf-title wf-admin-field-title">is Ajax</span>
								<select class="wf-select-box wf-select-box-text wf-normalize" v-model="is_ajax">
									<option v-for="ajax in ajax_list" :key="ajax.id" :value="ajax.id">{{ ajax.text }}</option>	
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	`
}