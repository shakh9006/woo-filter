import { mapActions } from "vuex"

export default {
	props: ['types'],

	data() {
		return {
			type: '',
		}
	},

	methods: {
		...mapActions([
			'addField'
		]),

		add() {
			this.addField({ type: this.type })
			this.type = ''
		},
	},

	template: `
		<div class="wf-settings-add-field">
			<div class="wf-settings-add-field-container">
				<h3>New Field</h3>
				<div class="wf-select-wrapper">
					<select v-model="type">
						<option value="" selected="selected">Select Type</option>
						<option v-for="(type, key) in types" :key="key" :value="key">{{ type }}</option>
					</select>
				</div>
				<button type="button" class="btn btn-success" data-toggle="button" aria-pressed="false" autocomplete="off" @click.prevent="add">
				  Add field
				</button>
			</div>
		</div>
	`
}